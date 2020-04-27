<?php


namespace MagonxESP\BlockAutoload\Block;


use MagonxESP\BlockAutoload\Annotation\Block;

interface BlockInterface {
    /**
     * Render the block content
     *
     * @param array $context
     *
     * @return void
     */
    public function render($context);
    
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