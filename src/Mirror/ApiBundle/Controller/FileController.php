<?php

namespace Mirror\ApiBundle\Controller;

use Mirror\ApiBundle\Annotation\AAuth;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 文件管理
 * @Route("/file")
 * Class FileController
 * @package Mirror\ApiBundle\Controller
 */
class FileController extends BaseController {

    /**
     * 上传文件
     * @Route("/upload")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function uploadMoreAction(Request $request) {
        $file = $request->files->get('file', '');
        $rr=array();
        if (is_array($file)) {
            foreach ($file as $fileObj){
                $file = $this->get('file_service')->saveFile($request,$fileObj);
                if (!$file) {
                    return $this->buildResponse(new ReturnResult(Code::$file_not_exist));
                }
                $rr[] = array(
                    'fileId' => $file->getId(),
                    'size' => $file->getSize(),
                    'md5' => $file->getMd5(),
                    'url' => $request->getHttpHost().$file->getUrl(),
                );
            }
        } else {
            $file = $this->get('file_service')->saveFile($request,$file);
            if (!$file) {
                return $this->buildResponse(new ReturnResult(Code::$file_not_exist));
            }
            $rr = array(
                'fileId' => $file->getId(),
                'size' => $file->getSize(),
                'md5' => $file->getMd5(),
                'url' => $request->getHttpHost().$file->getUrl(),
            );
        }
        $result=array(
            'result'=>$rr
        );
        return $this->buildResponse(new ReturnResult(0,$result));
    }

    /**
     * @Route("/import")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function import(Request $request){
        $file = $request->files->get('file', '');
        $userId=$this->sessionGet($request,'userId',0);
        $file = $this->get('file_service')->saveFile($request,$file);
        if (!$file) {
            return $this->buildResponse(new ReturnResult(Code::$file_not_exist));
        }
        $url='./'.$file->getUrl();
        $conn=$this->get('database_connection');
        $rr=$this->get('car_service')->import($url,$userId,$conn);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/BImport")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function BImport(Request $request){
        $file = $request->files->get('file', '');
        $file = $this->get('file_service')->saveFile($request,$file);
        if (!$file) {
            return $this->buildResponse(new ReturnResult(Code::$file_not_exist));
        }
        $url='./'.$file->getUrl();
        $rr=$this->get('biology_service')->BImport($url);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/FImport")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function FImport(Request $request){
        $file = $request->files->get('file', '');
        $file = $this->get('file_service')->saveFile($request,$file);
        if (!$file) {
            return $this->buildResponse(new ReturnResult(Code::$file_not_exist));
        }
        $id=$file->getId();
        $boxId=$request->get('boxId','');
        $conn=$this->get('database_connection');
        $sql="update weixin.box set report=$id,status=4 where id=$boxId";
        $conn->exec($sql);
        return $this->buildResponse(new ReturnResult());
    }

    /**
     * @Route("/HImport")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function HImport(Request $request){
        $file = $request->files->get('file', '');
        $file = $this->get('file_service')->saveFile($request,$file);
        if (!$file) {
            return $this->buildResponse(new ReturnResult(Code::$file_not_exist));
        }
        $id=$file->getId();
        $orderId=$request->get('orderId','');
        $conn=$this->get('database_connection');
        $sql="update hpv.orders set report=$id,status=3 where id=$orderId";
        $conn->exec($sql);
        return $this->buildResponse(new ReturnResult());
    }
}