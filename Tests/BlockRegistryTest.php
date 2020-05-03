<?php


use MagonxESP\BlockAutoload\Block\BlockRegistry;
use MagonxESP\BlockAutoload\Exception\BlockAutoloadException;
use MagonxESP\BlockAutoload\BlockAutoload;
use MagonxESP\BlockAutoload\Block\BlockPlugin;
use PHPUnit\Framework\TestCase;

class BlockRegistryTest extends TestCase {

    private $blockObject;

    public function setUp(): void {
        $blockAutoload = new BlockAutoload(BlockPlugin::ACF_PRO, __DIR__ . '/SampleBlocks/WithNamespace');
        $blockAutoload->setBlockNamespace('MagonxESP\\Tests\\SampleBlocks\\');
        $discovery = $blockAutoload->getBlockDiscoveryInstance();
        $blocks = $discovery->getBlocks();
        $this->blockObject = $blockAutoload->createBlockInstance($blocks['sample_php_block']);
    }

    public function testNotSupportedBlockApi() {
        $this->expectException(BlockAutoloadException::class);
        $this->expectExceptionCode(BlockAutoloadException::NOT_SUPPORTED_BLOCK_API);
        BlockRegistry::register($this->blockObject, 'unknown_api');
    }

    public function testSupportedBlockApi() {
        try {
            BlockRegistry::register($this->blockObject, BlockPlugin::ACF_PRO);
            $this->assertNull(null);
        } catch (BlockAutoloadException $e) {
            $this->fail($e->getMessage());
        }
    }

}
