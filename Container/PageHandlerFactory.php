<?php
/**
 * Created by PhpStorm.
 * User: Ppito
 * Date: 11/15/2016
 * Time: 8:29 PM
 *
 * @link      https://github.com/Ppito/zf3-whoops for the canonical source repository
 * @copyright Copyright (c) 2016 Mickael TONNELIER.
 * @license   https://github.com/Ppito/zf3-whoops/blob/master/LICENSE.md The MIT License
 */

namespace WhoopsErrorHandler\Container;

use Interop\Container\ContainerInterface;
use Whoops\Handler\Handler;
use Whoops\Handler\PrettyPageHandler as WhoopsPageHandler;
use Zend\ServiceManager\Factory\FactoryInterface;

class PageHandlerFactory implements FactoryInterface  {

    /**
     * Invoke Page Handler
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     * @returns Handler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        $config = $container->has('config') ? $container->get('config') : [];
        $config = isset($config['whoops']) ? $config['whoops'] : [];

        $pageHandler = new WhoopsPageHandler();

        $pageHandler->setApplicationPaths([__FILE__]);
        $this->injectEditor($pageHandler, $config, $container);

        return $pageHandler;
    }

    /**
     * Inject an editor into the whoops configuration.
     *
     * @param WhoopsPageHandler  $handler
     * @param array|\ArrayAccess $config
     * @param ContainerInterface $container
     * @throws \InvalidArgumentException for an invalid editor definition.
     */
    private function injectEditor(WhoopsPageHandler $handler, $config, ContainerInterface $container) {

        if (! isset($config['editor'])) {
            return;
        }

        $editor = $config['editor'];
        if (!is_callable($editor) && !is_string($editor)) {
            throw new \InvalidArgumentException(sprintf(
                'Whoops editor must be a string editor name, string service name, or callable; received "%s"',
                (is_object($editor) ? get_class($editor) : gettype($editor))
            ));
        }

        if (is_string($editor) && $container->has($editor)) {
            $editor = $container->get($editor);
        }

        $handler->setEditor($editor);
    }
}