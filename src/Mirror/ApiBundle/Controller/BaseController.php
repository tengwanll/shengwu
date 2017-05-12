<?php

namespace Mirror\ApiBundle\Controller;

use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Util\Helper;
use Mirror\ApiBundle\ViewModel\Pageable;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Mirror\ApiBundle\ViewModel\Sortable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller {

    /**
     * 解析json对象
     * @param Request $request
     * @param $className
     * @return object
     */
    public function serializerJson(Request $request, $className) {
        $json = $request->getContent();
        $serializer = $this->get('serializer');

        return $serializer->deserialize($json, 'Mirror\\ApiBundle\\AppModel\\'.$className, 'json');
    }

    /**
     * 解析json对象
     * @param Request $request
     * @param $className
     * @return object
     */
    public function serializerByJson(Request $request, $className) {
        $json = $request->getContent();
        $serializer = $this->get('serializer');
        return $serializer->deserialize($json, 'Mirror\\ApiBundle\\Entity\\'.$className, 'json');
    }

    public function getUserIdBySession(Request $request){
        $userId=$this->sessionGet($request,'userId','');

        return $userId;
    }

    /**
     * 判断变量是否为空
     * @param $var
     * @return bool
     */
    public final function isEmpty($var) {
        return empty($var);
    }

    /**
     * 判断变量不为空
     * @param $var
     * @return bool
     */
    public final function isNotEmpty($var) {
        return !$this->isEmpty($var);
    }

    /**
     * 从GET中获取分页信息
     * @param Request $request
     * @return \Mirror\ApiBundle\ViewModel\Pageable
     */
    public function getPage(Request $request) {
        $page = $request->get('page', 0);
        $rows = $request->get('rows', 0);
        if ($page == 0 && $rows == 0) {
            return null;
        }
        return new Pageable ($page, $rows);
    }

    public function getPageInfoByJson(Request $request, $page = 1, $rows = 10) {
        $json = $this->get('json_parse')->parse($request);
        $pageable = new Pageable ();
        $page = $json->get('page', $page, '');
        $rows = $json->get('rows', $rows, '');
        if ($page == 0 && $rows == 0) {
            return null;
        }
        $pageable->setPage($page);
        $pageable->setRows($rows);

        return $pageable;
    }

    public function getSortInfo($sort = '', $order = '') {
        $request = $this->getRequest();
        $sort = $request->request->get('sort', $sort);
        $order = $request->request->get('order', $order);
        if (($sort != '') && ($order != '')) {
            $sortable = new Sortable ();
            $sortable->setSort(
                array(
                    $sort,
                )
            );
            $sortable->setOrder(
                array(
                    $order,
                )
            );

            return $sortable;
        }

        return null;
    }

    public function buildExcelResponse($fullName) {
        $fileSize = filesize('excel/'.$fullName);
        $response = new Response ('', 200);
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Accept-Length', $fileSize);
        $response->headers->set('Content-Disposition', "attachment; filename=".$fullName);
        $content = file_get_contents('excel/'.$fullName);
        $response->setContent($content);

        return $response;
    }

    public function buildAttachmentResponse($filePath, $size, $name) {
        $fileSize = filesize($filePath);
        $response = new Response ('', 200);
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Accept-Length', $size);
        $response->headers->set('Content-Disposition', "attachment; filename=".$name);
        $content = file_get_contents($filePath);
        $response->setContent($content);

        return $response;

    }

    public function buildResponse(ReturnResult $rr) {
        $errNo=$rr->errno;
        $jsonArray = $rr->result;
        $errorMessageCN = $rr->errmsg;
        if ($errNo != 0&&$errorMessageCN=='') {
            $errorMessageCN = $this->get('error_definition_service')->findByErrorCode($errNo);
        }
        $errorArray = array(
            'errno' => $errNo,
            'errmsg' => $errorMessageCN,
        );
        $respArray = array_merge($errorArray, $jsonArray);
        // 使用array_filter 取出null的item
        $response = new JsonResponse (
                array_filter(
                    $respArray,
                    function ($value) {
                        return !is_null($value);
                    }
                )
        );
        return $response;
    }

    public function buildHtmlResponse($html) {
        $response = new Response ($html, 200);
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    public function buildXmlResponse($xml) {
        $response = new Response ($xml, 200);
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

    public function sessionSet(Request $request, $key, $value) {
        $request->getSession()->set($key, $value);
    }

    public function sessionGet(Request $request, $key, $default = '') {
        return $request->getSession()->get($key, $default);
    }

    /**
     * 获取登录IP
     * @param Request $request
     * @return mixed
     */
    public function getIpAddress(Request $request) {
        $ipAddr = $request->server->get('HTTP_X_REAL_IP');
        if (!$ipAddr) {
            $ipAddr = $request->server->get('HTTP_X_FORWARDED_FOR');
        }
        if (!$ipAddr) {
            $ipAddr = $request->server->get('REMOTE_ADDR');
        }

        return $ipAddr;
    }

    /**
     * @param Request $request
     * @param string $method
     * @return \Mirror\ApiBundle\Util\JsonParser
     */
    public function getJson(Request $request, $method = 'json') {
        $json = $this->get('json_parse')->parse($request, $method);

        return $json;
    }

    public function inRoleReturn($roleList, $adminId, $result = 0) {
        $roleMap = array();
        foreach ($roleList as $role) {
            $roleMap [$role] = 1;
        }
        $adminRoleList = $this->get('admin_service')->getAdminRoleList($adminId);
        foreach ($adminRoleList as $adminRole) {
            $roleId = $adminRole->getRoleId();
            $has = Helper::getc($roleMap, $roleId, 0);
            if ($has) {
                return $result;
            }
        }

        return $adminId;
    }

    public function cookieGet(Request $request, $name, $default = '') {
        $cookie = $this->getCookie($request);

        return $cookie->get($name, $default);
    }

    public function getCookie(Request $request) {
        return $request->cookies;
    }

    /**
     * @param $userId
     * @param $telephone
     * @param $time
     * @return array
     */
    public function initToken($userId,$telephone,$time){
        $access_token=md5(sha1($userId.$telephone).$time);
        $refresh_token=sha1(md5($userId.$telephone).$time);
        return array('access_token'=>array('token'=>$access_token,'expires'=>$time+Constant::$access_token_expires),'refresh_token'=>array('token'=>$refresh_token,'expires'=>$time+Constant::$refresh_token_expires));
    }

    public function getUserIdByToken(Request $request){
        $redis=$this->get('snc_redis.default');
        $redis->auth('Taobao3344');
        $accessToken=$request->get('access_token','');
        $userId=$redis->get('app:api:'.$accessToken);
        return $userId;
    }
}