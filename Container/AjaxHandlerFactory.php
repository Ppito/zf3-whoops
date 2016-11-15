<?php
/**
 * Created by PhpStorm.
 * User: Ppito
 * Date: 11/15/2016
 * Time: 8:26 PM
 *
 * @link      https://github.com/Ppito/zf3-whoops for the canonical source repository
 * @copyright Copyright (c) 2016 Mickael TONNELIER.
 * @license   https://github.com/Ppito/zf3-whoops/blob/master/LICENSE.md The MIT License
 */

namespace WhoopsErrorHandler\Container;

use Interop\Container\ContainerInterface;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler as WhoopsAjaxHandler;
use Zend\ServiceManager\Factory\FactoryInterface;

class AjaxHandlerFactory implements FactoryInterface  {

    /**
     * Invoke Ajax Handler
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @returns Handler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        $config = $container->has('config') ? $container->get('config') : [];
        $config = isset($config['whoops']) ? $config['whoops'] : [];

        $pageHandler = new WhoopsAjaxHandler();

        $pageHandler->setJsonApi(true);
        $this->showTrace($pageHandler, $config);

        return $pageHandler;
    }

    /**
     * Inject an editor into the whoops configuration.
     *
     * @param WhoopsAjaxHandler  $handler
     * @param array|\ArrayAccess $config
     * @throws \InvalidArgumentException for an invalid show trace option.
     */
    private function showTrace(WhoopsAjaxHandler $handler, $config) {

        if (!isset($config['show_trace']) || !isset($config['show_trace']['ajax_display'])) {
            return;
        }

        $show_trace = $config['show_trace']['ajax_display'];

        if (! is_bool($show_trace)) {
            throw new \InvalidArgumentException(sprintf(
                'Whoops show trace option must be a boolean; received "%s"',
                (is_object($show_trace) ? get_class($show_trace) : gettype($show_trace))
            ));
        }
        $handler->addTraceToOutput($show_trace);
    }
}