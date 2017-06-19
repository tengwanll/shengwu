<?php

namespace Mirror\ApiBundle\EventListener;

use Mirror\ApiBundle\Exception\CustomException;
use Mirror\ApiBundle\Exception\LogicException;
use Mirror\ApiBundle\Response\AccessResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 异常处理
 * Class ExceptionListener
 * @package Mirror\ApiBundle\EventListener
 */
class ExceptionListener {

    public function onKernelException(GetResponseForExceptionEvent $event) {
        $exception = $event->getException();
        $request=$event->getRequest();
        $response = new Response ();
        if ($exception instanceof LogicException) {
            if($request->headers->get('X-Requested-With')!='XMLHttpRequest'&&$exception->getErrno()=='20116'){
                $url='http://'.$_SERVER['HTTP_HOST'].'/adm/login/index';
                $response=new AccessResponse($url);
            }else{
                $jsonStr = $exception->toString();
                $response->setContent($jsonStr);
                $response->headers->set('X-Content-Type', 'application/json;charset=UTF-8');
                $response->headers->set('Content-Type', 'application/json;charset=UTF-8');
            }
        } else {
            if ($exception instanceof NotFoundHttpException) {
                $json_r = array(
                    'errno' => 10404,
                    'errmsg' => 'router not found',
                );
                $response->headers->set('Content-Type', 'application/json;charset=UTF-8');
                $response->setContent(json_encode($json_r, JSON_UNESCAPED_UNICODE));
            } else if($exception instanceof MethodNotAllowedHttpException){
                $json_r = array(
                    'errno' => 10405,
                    'errmsg' => 'method not allowed',
                );
                $response->headers->set('Content-Type', 'application/json;charset=UTF-8');
                $response->setContent(json_encode($json_r, JSON_UNESCAPED_UNICODE));
            }else {
                if ($exception instanceof CustomException) {
                    $json_r = array(
                        'errno' => $exception->getCode(),
                        'errmsg' => $exception->getMessage(),
                    );
                    $response->headers->set('Content-Type', 'application/json;charset=UTF-8');
                    $response->setContent(json_encode($json_r, JSON_UNESCAPED_UNICODE));
                } else {
                    $message = $exception->getMessage();
                    var_dump($message);
                    $file = $exception->getFile();
                    $line = $exception->getLine();
                    // $exception->getTraceAsString();
                    $format = 'file:%s line:%d message:%s<br/>';
                    $format = $format.$exception->getTraceAsString();
                    $message = sprintf($format, $file, $line, $message);
                    $response->setContent($message);
                }
            }
        }
        $response->headers->set('X-Status-Code', 200);
        $event->setResponse($response);
    }
}

?>