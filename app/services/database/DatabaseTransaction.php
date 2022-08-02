<?php

namespace Commerce\App\Services\Database;

/**
 * Class responsible for querying the table passed in the constructor.
 *
 */
class DatabaseTransaction implements TransactionableInterface {

    /**
     * Start a transaction.
     *
     * @return void
     */
    public static function start() {

        global $wpdb;
        $wpdb->query( 'SET autocommit = 0;' );
        $wpdb->query( 'START TRANSACTION;' );
    }

    /**
     * Commit a transaction.
     *
     * @return void
     */
    public static function commit() {

        global $wpdb;
        $wpdb->query( 'COMMIT;' );
        $wpdb->query( 'SET autocommit = 1;' );
    }

    /**
     * Rollback a transaction.
     *
     * @return void
     */
    public static function rollback() {

        global $wpdb;
        $wpdb->query( 'ROLLBACK;' );
    }

}
