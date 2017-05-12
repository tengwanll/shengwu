<?php
/**
 * Created by PhpStorm.
 * User: tengwanll
 * Date: 2016/1/4
 * Time: 16:17
 */

namespace Mirror\ApiBundle\EventListener;


use Mirror\ApiBundle\Service\CallTraceService;
use Mirror\ApiBundle\Service\SystemSettingService;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CallTraceListener
{
    private $settingService;
    private $callTraceService;
    public function __construct(SystemSettingService $settingService,CallTraceService $callTraceService)
    {
        $this->settingService=$settingService;
        $this->callTraceService=$callTraceService;
    }
    public function onKernelController(FilterControllerEvent $event) {
        $request=$event->getRequest();
        $time=microtime(true);
        $request->getSession()->set('microtime',$time);
    }
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $callTrace = $this->settingService->findByName('CALL_TRACE_LEVEL', 0);
        if ($callTrace) {
            $memSession = $request->getSession();
            $microtime = microtime(true) - $memSession->get('microtime', microtime(true));
            $memSession->set('microtime', $microtime);

            $session = '';
            foreach ($memSession->all() as $key => $value) {
                $type = gettype($value);
                if ($type == 'array') {
                    $value = json_encode($value);
                }
                $session .= sprintf("%-12s %s\n", $key.':', $value);
            }
            $this->callTraceService->create($request->getRequestUri(), $session, $request, $response);
        }
    }
}