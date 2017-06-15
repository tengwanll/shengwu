<?php

namespace Mirror\ApiBundle\Controller;

use Mirror\ApiBundle\Annotation\AAuth;
use Mirror\ApiBundle\Common\Code;
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
}