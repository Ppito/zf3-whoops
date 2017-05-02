<?php
/**
 * Created by PhpStorm.
 * User: Ppito
 * Date: 11/20/2016
 * Time: 7:38 PM
 *
 * @link      https://github.com/Ppito/zf3-whoops for the canonical source repository
 * @copyright Copyright (c) 2016 Mickael TONNELIER.
 * @license   https://github.com/Ppito/zf3-whoops/blob/master/LICENSE.md The MIT License
 */

namespace WhoopsErrorHandler\Service;

use WhoopsErrorHandler\Handler;
use Interop\Container\ContainerInterface;
use Whoops\Util\Misc;
use Whoops\Run as Whoops;

class WhoopsService extends ServiceAbstract {

    /** @var \Whoops\RunInterface|null */
    protected $service = null;

    /**
     * WhoopsService constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     * @param array                                 $options
     * @return self
     */
    public function __construct(ContainerInterface $container, $options = []) {

        parent::__construct($container, $options);
        $this->service = new Whoops();
        $this->configure();
        return $this;
    }

    /**
     * Configure Whoops service
     */
    public function configure() {
        /**
         * Keep this method to true, in case of error
         * was launched without to be catch be ZF3
         */
        $this->service->writeToOutput(true);
        $this->service->allowQuit(false);

        if ($this->registerHandler($this->getContainer())) {
            $this->service->register();
        } else {
            $this->service = null;
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
            $service = $container->has(Handler\AjaxHandler::class) ?
                $container->get(Handler\AjaxHandler::class) :
                null;
        }
        elseif (Misc::isCommandLine()) {
            $service = $container->has(Handler\ConsoleHandler::class) ?
                $container->get(Handler\ConsoleHandler::class) :
                null;
        }
        else {
            $service = $container->has(Handler\PageHandler::class) ?
                $container->get(Handler\PageHandler::class) :
                null;
        }

        // Do nothing if no handler found
        if (is_null($service)) {
            return null;
        }

        $handler = $service->getHandler();
        if (!$handler instanceof \Whoops\Handler\Handler) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The register handler must be an instance of \Whoops\Handler\Handler; received "%s"',
                    (is_object($handler) ? get_class($handler) : gettype($handler))
                )
            );
        }

        return $this->service->pushHandler($handler);
    }

    /**
     * @return \Whoops\RunInterface
     */
    public function getService() {
        return $this->service;
    }
}