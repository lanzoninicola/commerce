<?php

namespace Commerce\App\Services\Database;

interface TransactionableInterface {

    /**
     * Start a transaction.
     *
     * @return void
     */
    public static function start();

    /**
     * Commit a transaction.
     *
     * @return void
     */
    public static function commit();

    /**
     * Rollback a transaction.
     *
     * @return void
     */
    public static function rollback();
}