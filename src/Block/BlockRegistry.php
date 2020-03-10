<?php


namespace MagonxESP\BlockAutoload\Block;


use MagonxESP\BlockAutoload\Exception\BlockAutoloadException;
use MagonxESP\BlockAutoload\Annotation\Block;

class BlockRegistry {

    /**
     * Block to register
     *
     * @var BlockBase $block
     */
    private $block;

    /**
     * The block info
     *
     * @var Block $blockInfo
     */
    private $blockInfo;

    public function __construct(BlockBase $block) {
        $this->block = $block;
        $this->blockInfo = $block->getBlockInfo();
    }

    public function acfRegister() {
        if (function_exists('acf_register_block')) {
            acf_register_block( array(
                'name'            => $this->blockInfo->name,
                'title'           => __( $this->blockInfo->title, $this->blockInfo->domain ),
                'description'     => __( $this->blockInfo->description, $this->blockInfo->domain ),
                'render_callback' => [$this->block, 'render'],
                'category'        => $this->blockInfo->category,
                'icon'            => $this->blockInfo->icon,
                'keywords'        => $this->blockInfo->keywords,
            ));
        }
    }

    public static function register(BlockBase $block, string $blockApi) {
        $registry = new BlockRegistry($block);

        switch ($blockApi) {
            case BlockPlugin::ACF_PRO:
                $registry->acfRegister();
                break;
            case BlockPlugin::WP_BLOCK_API:
                break;
            default:
                throw new BlockAutoloadException('Unsupported block api ' . $blockApi . 'for register block ' . $block->name);
        }
    }

}