<?php


namespace MagonxESP\BlockAutoload\Block;


interface BlockInterface {
    /**
     * Block render callback
     *
     * @return void
     */
    public function render();

    /**
     * Register the block
     *
     * @return void
     */
    public function register();

    /**
     * Block load event
     *
     * @return void
     */
    public function onLoad();
}