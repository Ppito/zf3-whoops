<?php
/**
 * Created by PhpStorm.
 * User: Ppito
 * Date: 01/10/2017
 * Time: 10:36 PM
 *
 * @link      https://github.com/Ppito/zf3-whoops for the canonical source repository
 * @copyright Copyright (c) 2016 Mickael TONNELIER.
 * @license   https://github.com/Ppito/zf3-whoops/blob/master/LICENSE.md The MIT License
 */

namespace WhoopsErrorHandler\Service;

abstract class VisibilityServiceAbstract extends ServiceAbstract
    implements VisibilityServiceInterface {

    /**
     * Verify if the module can be loaded
     *
     * @return boolean
     */
    abstract public function canAttachEvent();
}
