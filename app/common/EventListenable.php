<?php
namespace Commerce\App\Common;

interface EventListenable {

    /**
     * Attach an event listener to the given action name.
     *
     * @param string $event_name
     * @return void
     */
    public function on( string $event_name );
}
