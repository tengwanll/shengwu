<?php
/**
 * Created by PhpStorm.
 * User: rpzhan
 * Date: 15/12/1
 * Time: 11:46
 */

namespace Mirror\ApiBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Exception\OAuthException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Session登录状态监听处理
 * Class OAuthListener
 * @package Mirror\ApiBundle\EventListener
 */
class OAuthListener {

    /**
     * @var $reader
     */
    private $reader;

    /**
     * OAuthListener constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }

    /**
     * Modifies the Request object to apply configuration information found in
     * controllers annotations like the template to render or HTTP caching
     * configuration.
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     * @throws OAuthException
     */
    public function onKernelController(FilterControllerEvent $event) {
        if (!is_array($controller = $event->getController())) {
            return;
        }
        $methodObj = new \ReflectionMethod($controller[0], $controller[1]);
        // 页面权限校验
        $auth = $this->reader->getMethodAnnotation($methodObj,'Mirror\ApiBundle\Annotation\OAuth');
        $session = $event->getRequest()->getSession();

        if ($auth) {
            if (!$session->get(Constant::$login_entity)) {
                // 需要登录验证时, session中不包含登录信息(未通过校验)
                throw new OAuthException();
            }
        }
    }
}