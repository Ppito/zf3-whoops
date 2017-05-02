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
use Zend\Http\Response;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ConsoleModel;
use Zend\Http\Response as HttpResponse;

class Module implements ConfigProviderInterface, BootstrapListenerInterface {

    /** @var string */
    protected $template = 'zf3_whoops/simple_error';

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

        $application = $e->getApplication();
        /** @var ServiceManager $serviceManager */
        $serviceManager = $application->getServiceManager();

        $this->configureService($serviceManager);

        if ($this->whoops) {
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
     * Configure Whoops Service
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    protected function configureService(ContainerInterface $container) {
        $config = $container->has('config') ? $container->get('config') : [];
        $config = isset($config['whoops']) ? $config['whoops'] : [];

        $serviceName = array_key_exists('visibility_service_name', $config) && !empty($config['visibility_service_name']) ?
            $config['visibility_service_name'] :
            null;
        /** @var Service\VisibilityServiceInterface $visibilityService */
        $visibilityService = $serviceName && $container->has($serviceName) ?
            $container->get($serviceName) :
            null;

        if ($visibilityService instanceof Service\VisibilityServiceInterface) {
            if (!$visibilityService->canAttachEvent()) {
                return ;
            }
        }

        if (isset($config['template_render'])) {
            $this->setTemplate($config['template_render']);
        }

        /** @var Service\WhoopsService $service */
        $service = $container->has(Service\WhoopsService::class) ?
            $container->get(Service\WhoopsService::class) :
            null;

        if ($service) {
            $this->whoops = $service->getService();
        }
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
                // Set writeToOutput to false for rendered output with zend-view
                $this->whoops->writeToOutput(false);
                $result = $this->whoops->handleException($e->getParam('exception'));
                $model  = new ConsoleModel([
                    'result' => $result,
                ]);

                $model->setTemplate($this->getTemplate());
                $e->setResult($model);

                /** @var HttpResponse $response */
                $response = $e->getResponse();
                if (!$response) {
                    $response = new HttpResponse();
                    $response->setStatusCode(500);
                    $e->setResponse($response);
                } else {
                    $statusCode = $response->getStatusCode();
                    if ($statusCode === 200) {
                        $response->setStatusCode(500);
                    }
                }
                break;
        }
    }

    /**
     * Set Template
     *
     * @param string $template
     * @return self
     */
    public function setTemplate($template) {
        $this->template = $template;
        return $this;
    }

    /**
     * Retrieve the template
     *
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }
}
