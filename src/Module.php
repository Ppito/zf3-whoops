<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace WhoopsErrorHandler;

use Whoops\Run as Whoops;
use Whoops\Util\Misc;
use Whoops\Handler\JsonResponseHandler as WhoopsAjaxHandler;
use Whoops\Handler\PlainTextHandler as WhoopsConsoleHandler;
use Whoops\Handler\PrettyPageHandler as WhoopsPageHandler;

use Zend\EventManager\EventInterface;
use Zend\Http\Response;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class Module implements ConfigProviderInterface, BootstrapListenerInterface {

    /** @var \Whoops\Run */
    protected $whoops;

    /**
     * Listen to the bootstrap event
     *
     * @param \Zend\Mvc\MvcEvent|EventInterface $e
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e) {
        $application  = $e->getApplication();

        /** @var ServiceManager $serviceManager */
        $serviceManager = $application->getServiceManager();
        $config         = $serviceManager->get('Config');
        $config         = isset($config['whoops']) ? $config['whoops'] : [];

        $this->whoops = new Whoops();
        $this->whoops->writeToOutput(false);
        $this->whoops->allowQuit(false);
        $this->registerHandler($config);
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

    /**
     * Configure Handler
     *
     * @param array $config
     */
    private function registerHandler(array $config) {

        if (Misc::isAjaxRequest()) {
            $handler = new WhoopsAjaxHandler();

            if (isset($config['show_trace']) && isset($config['show_trace']['ajax_display'])) {
                $handler->addTraceToOutput($config['show_trace']['ajax_display']);
            }

            $handler->setJsonApi(true);
        } elseif (Misc::isCommandLine()) {
            $handler = new WhoopsConsoleHandler();

            if (isset($config['show_trace']) && isset($config['show_trace']['cli_display'])) {
                $handler->addTraceToOutput($config['show_trace']['cli_display']);
            }
        } else {
            $handler = new WhoopsPageHandler();

            $handler->setApplicationPaths([__FILE__]);

            if (isset($config['editor'])) {
                $handler->setEditor($config['editor']);
            }
        }
        $this->whoops->pushHandler($handler);
    }

    /**
     * Whoops handle exceptions
     *
     * @param MvcEvent $e
     */
    public function prepareException(MvcEvent $e) {
        /** @var Response $response */
        $response = $e->getResponse();
        if (!$response || $response->getStatusCode() === 200) {
            header('HTTP/1.0 500 Internal Server Error', true, 500);
        }
        $this->whoops->handleException($e->getParam('exception'));
    }

    /**
     * Return default zend-serializer configuration for zend-mvc applications.
     */
    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }
}
