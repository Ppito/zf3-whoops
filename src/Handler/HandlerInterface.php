<?php
/**
 * Created by PhpStorm.
 * User: Ppito
 * Date: 11/20/2016
 * Time: 1:20 PM
 *
 * @link      https://github.com/Ppito/zf3-whoops for the canonical source repository
 * @copyright Copyright (c) 2016 Mickael TONNELIER.
 * @license   https://github.com/Ppito/zf3-whoops/blob/master/LICENSE.md The MIT License
 */

namespace WhoopsErrorHandler\Handler;

interface HandlerInterface {

    /**
     * Configure Service Handler
     *
     * @return void
     */
    public function configure();
}
