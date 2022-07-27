<?php

namespace Commerce\Backend\App\Services\RestApi;

class BaseController {

    protected $repository;

    public function __construct( RepositoryInterface $repository ) {
        $this->repository = $repository;
    }

}
