<?php

namespace Commerce\Backend\Modules\Api\V1\Accounts;

class AccountController {
    /**
     * Singleton instance.
     *
     * @var AccountController
     */
    protected static $instance = null;

    /**
     * Instantiate the singleton.
     *
     * @return AccountController
     */
    public static function singletone( AccountRepository $repository ) {

        if ( self::$instance === null ) {
            self::$instance = new AccountController( $repository );
        }

        return self::$instance;
    }

    public function __construct( AccountRepository $repository ) {
        $this->repository = $repository;
    }

}
