<?php


namespace MagonxESP\BlockAutoload\Block;


interface BlockInterface {
    /**
     * Render the block content
     *
     * @param mixed $settings
     * @param string $content
     * @param bool $is_preview
     *
     * @return void
     */
    public function render($settings, $content = '', $is_preview = false);
    
    /**
     * Get the block render context variables
     *
     * @return array
     */
    public function getContext();

    /**
     * Setup the block
     *
     * @return void
     */
    public function setup();

    /**
     * Execute all render phases
     *
     * @param mixed ...$args
     * @return void
     */
    public function doRender(...$args);

    /**
     * Get the block info
     *
     * @return Block
     */
    public function getBlockInfo();
}