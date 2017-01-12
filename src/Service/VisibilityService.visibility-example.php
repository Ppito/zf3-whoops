<?php
/**
 * Created by PhpStorm.
 * User: Ppito
 * Date: 01/10/2017
 * Time: 11:04 PM
 */

namespace Application\Service;

use WhoopsErrorHandler\Service\VisibilityServiceAbstract;
use WhoopsErrorHandler\Service\VisibilityServiceInterface;

class VisibilityService extends VisibilityServiceAbstract
    implements VisibilityServiceInterface {

    /**
     * @var Model\User
     */
    protected $connectedUser = null;

    /**
     * Configure Service Handler
     * - Get Connected User
     *
     * @return void
     */
    public function configure() {
        $container = $this->getContainer();

        $this->connectedUser = $container->has('User') ?
            $container->get('User') :
            null;
    }

    /**
     * Verify the role of the user
     *
     * @return boolean
     */
    public function canAttachEvent() {
        return $this->connectedUser ?
            $this->connectedUser->hasRole('Admin') :
            false;
    }
}
