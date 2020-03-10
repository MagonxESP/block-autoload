<?php


namespace MagonxESP\BlockAutoload\Block;


use MagonxESP\BlockAutoload\Annotation\Block;

abstract class BlockBase implements BlockInterface {

    /**
     * @var Block $annotation
     */
    private $annotation;

    /**
     * @var string $blockApi
     */
    private $blockApi;

    /**
     * AbstractBlock constructor.
     *
     * @param Block $annotation
     */
    public function __construct(Block $annotation, $blockApi) {
        $this->annotation = $annotation;
        $this->blockApi = $blockApi;
    }

    /**
     * Get the block info
     *
     * @return Block
     */
    public function getBlockInfo() {
        return $this->annotation;
    }

    /**
     * @inheritDoc
     */
    public function register() {
        BlockRegistry::register($this, $this->blockApi);
    }

    public function render($settings, $content = '', $is_preview = false) {
        if ($this->annotation->template != null && $this->annotation->template != '') {
            ob_start();
            include_once $this->annotation->template;
            $output = ob_get_clean();

            if ($output !== false) {
                echo $output;
            }
        }
    }

}