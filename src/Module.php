<?php
/**
 * Created by PhpStorm.
 * User: Ppito
 * Date: 11/12/2016
 * Time: 1:12 PM
 *
 * @link      https://github.com/Ppito/zf3-whoops for the canonical source repository
 * @copyright Copyright (c) 2016 Mickael TONNELIER.
 * @license   https://github.com/Ppito/zf3-whoops/blob/master/LICENSE.md The MIT License
 */

namespace WhoopsErrorHandler;

use Interop\Container\ContainerInterface;
use Whoops\Handler\Handler;
use Whoops\Util\Misc;
use Whoops\Run as Whoops;

use Zend\Http\Response;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class Module implements ConfigProviderInterface, BootstrapListenerInterface {

    /** @var \Whoops\Run */
    protected $whoops;

    /**
     * Return default zend-serializer configuration for zend-mvc applications.
     */
    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listen to the bootstrap event
     *
     * @param \Zend\Mvc\MvcEvent|EventInterface $e
     * @return void
     */
    public function onBootstrap(EventInterface $e) {

        $application  = $e->getApplication();
        /** @var ServiceManager $serviceManager */
        $serviceManager = $application->getServiceManager();

        $this->whoops = new Whoops();
        $this->whoops->writeToOutput(false);
        $this->whoops->allowQuit(false);

        if ($this->registerHandler($serviceManager)) {
            $this->whoops->register();

            $eventManager = $application->getEventManager();
            $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [
                $this,
                'prepareException',
            ]);
            $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [
                $this,
                'prepareException',
            ]);
        }
    }

    /**
     * Register Handler
     *
     * @param ContainerInterface $container
     * @return Whoops|null
     * @throws \InvalidArgumentException if not an instance of \Whoops\Handler\Handler
     */
    private function registerHandler(ContainerInterface $container) {

        if (Misc::isAjaxRequest()) {
            $handler = $container->has('WhoopsErrorHandler\Handler\Ajax')
                ? $container->get('WhoopsErrorHandler\Handler\Ajax')
                : null;
        } elseif (Misc::isCommandLine()) {
            $handler = $container->has('WhoopsErrorHandler\Handler\Console')
                ? $container->get('WhoopsErrorHandler\Handler\Console')
                : null;
        } else {
            $handler = $container->has('WhoopsErrorHandler\Handler\Page')
                ? $container->get('WhoopsErrorHandler\Handler\Page')
                : null;
        }

        // Do nothing if no handler found
        if (is_null($handler)) {
            return null;
        }

        if (!$handler instanceof Handler) {
            throw new \InvalidArgumentException(sprintf(
                'The register handler must be an instance of \Whoops\Handler\Handler; received "%s"',
                (is_object($handler) ? get_class($handler) : gettype($handler))
            ));
        }

        return $this->whoops->pushHandler($handler);
    }

    /**
     * Whoops handle exceptions
     *
     * @param MvcEvent $e
     */
    public function prepareException(MvcEvent $e) {

        // Do nothing if no error in the event
        $error = $e->getError();
        if (empty($error)) {
            return;
        }

        // Do nothing if the result is a response object
        $result = $e->getResult();
        if ($result instanceof Response) {
            return;
        }

        switch ($error) {
            case Application::ERROR_CONTROLLER_NOT_FOUND:
            case Application::ERROR_CONTROLLER_INVALID:
            case Application::ERROR_ROUTER_NO_MATCH:
                // Specifically not handling these
                return;

            case Application::ERROR_EXCEPTION:
            default:
                /** @var Response $response */
                $response = $e->getResponse();
                if (!$response || $response->getStatusCode() === 200) {
                    header('HTTP/1.0 500 Internal Server Error', true, 500);
                }
                die($this->whoops->handleException($e->getParam('exception')));
                break;
        }
    }
}
