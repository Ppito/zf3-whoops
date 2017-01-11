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

use WhoopsErrorHandler\Service\ServiceAbstract;

abstract class HandlerAbstract extends ServiceAbstract
    implements HandlerInterface {

    /** @var \Whoops\Handler\HandlerInterface */
    protected $handler;

    /**
     * @return \Whoops\Handler\HandlerInterface
     */
    public function getHandler() {
        return $this->handler;
    }
}
