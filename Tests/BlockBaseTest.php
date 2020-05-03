<?php

use MagonxESP\BlockAutoload\Block\BlockBase;
use MagonxESP\BlockAutoload\BlockAutoload;
use MagonxESP\BlockAutoload\Block\BlockPlugin;
use MagonxESP\BlockAutoload\Annotation\Block;
use PHPUnit\Framework\TestCase;

class BlockBaseTest extends TestCase {

    /**
     * @var BlockBase $blockObject
     */
    private $blockObject;

    public function setUp(): void {
        $blockAutoload = new BlockAutoload(BlockPlugin::ACF_PRO, __DIR__ . '/SampleBlocks/WithNamespace');
        $blockAutoload->setBlockNamespace('MagonxESP\\Tests\\SampleBlocks\\');
        $discovery = $blockAutoload->getBlockDiscoveryInstance();
        $blocks = $discovery->getBlocks();
        $this->blockObject = $blockAutoload->createBlockInstance($blocks['sample_php_block']);
    }

    public function testGetPropertiesBeforeSetup() {
        $context = $this->blockObject->getContext();
        $this->assertArrayHasKey('myPublicProperty', $context);
        $this->assertNull($context['myPublicProperty']);
    }

    public function testGetPropertyValuesAfterSetup() {
        $this->blockObject->setup();
        $context = $this->blockObject->getContext();
        $this->assertSame('Sample Block', $context['myPublicProperty']);
    }

    public function testPrivateAndProtectedProperties() {
        $context = $this->blockObject->getContext();
        $this->assertArrayNotHasKey('myPrivateProperty', $context);
        $this->assertArrayNotHasKey('myProtectedProperty', $context);
    }

    public function testTemplateRender() {
        $this->expectOutputString('<h1>Sample Block</h1>');
        $this->blockObject->doRender([]);
    }

    public function testBlockAnnotationInstance() {
        $this->assertInstanceOf(Block::class, $this->blockObject->getBlockInfo());
    }

}
