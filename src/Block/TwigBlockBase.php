<?php


namespace MagonxESP\BlockAutoload\Block;

use Timber\Timber;

abstract class TwigBlockBase extends BlockBase {

    /**
     * @inheritDoc
     */
    public function getContext() {
        $context = Timber::context();
        $props = $this->getProperties();

        foreach ($props as $key => $value) {
            $context[$key] = $value;
        }

        return $context;
    }

    /**
     * @inheritDoc
     */
    public function render($context) {
        $this->createRenderScope(function($template) use ($context) {
            Timber::render($template, $context);
        });
    }

}