<?php
/**
 * Created by PhpStorm.
 * User: Ppito
 * Date: 11/20/2016
 * Time: 1:14 PM
 *
 * @link      https://github.com/Ppito/zf3-whoops for the canonical source repository
 * @copyright Copyright (c) 2016 Mickael TONNELIER.
 * @license   https://github.com/Ppito/zf3-whoops/blob/master/LICENSE.md The MIT License
 */

namespace WhoopsErrorHandler\Handler;


use Interop\Container\ContainerInterface;

abstract class HandlerAbstract {

    /** @var array|null */
    protected $options = [];
    /** @var ContainerInterface */
    protected $container;
    /** @var \Whoops\Handler\HandlerInterface */
    protected $handler;

    /**
     * HandlerAbstract constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     * @param array                                 $options
     * @return self
     */
    public function __construct(ContainerInterface $container, $options = []) {
        $this->options   = $options;
        $this->container = $container;
        return $this;
    }

    /**
     * Configure Service Handler
     *
     * @return void
     */
    abstract public function configure();


    /**
     * @return array|null
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * @return \Whoops\Handler\HandlerInterface
     */
    public function getHandler() {
        return $this->handler;
    }
}
