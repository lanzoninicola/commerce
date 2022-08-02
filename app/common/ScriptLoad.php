<?php

namespace Commerce\App\Common;

/**
 * An helper class that let you tag your scripts with the "defer" / "async" / type="module" attribute.
 *
 *  Public methods:
 * - defer()
 * - async()
 * - type_module()
 *
 */
class ScriptLoad {

    /**
     * The handle name used to enqueue the script.
     *
     * @var string
     */
    private $handle;

    /**
     * @param string $handle The handle name used to enqueue the script.
     */
    public function __construct( string $handle ) {
        $this->handle = $handle;
    }

    /**
     * Add the "defer" attribute to the <script> enqueued.
     */
    public function defer() {

        add_filter( 'script_loader_tag', array( $this, 'add_defer_attribute' ), 10, 3 );
    }

    /**
     * Add the "async" attribute to the <script> enqueued.
     */
    public function async() {

        add_filter( 'script_loader_tag', array( $this, 'add_async_attribute' ), 10, 3 );
    }

    /**
     * Add the "module" type attribute to the <script> enqueued.
     */
    public function type_module() {

        add_filter( 'script_loader_tag', array( $this, 'add_module_type_attribute' ), 10, 3 );
    }

    public function add_defer_attribute( $tag, $handle, $src ) {

        if ( strpos( $handle, $this->handle ) !== false ) {

            return str_replace( ' src', ' defer src', $tag );
        }

        return $tag;
    }

    public function add_async_attribute( $tag, $handle, $src ) {

        if ( strpos( $handle, $this->handle ) !== false ) {

            return str_replace( ' src', ' async src', $tag );
        }

        return $tag;
    }

    public function add_module_type_attribute( $tag, $handle, $src ) {

        if ( strpos( $handle, $this->handle ) !== false ) {

            return str_replace( ' src', ' type="module" src', $tag );
        }

        return $tag;
    }

}
