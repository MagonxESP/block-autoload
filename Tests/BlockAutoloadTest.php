<?php


use MagonxESP\BlockAutoload\BlockAutoload;
use PHPUnit\Framework\TestCase;
use MagonxESP\BlockAutoload\Block\BlockPlugin;
use MagonxESP\BlockAutoload\Block\BlockInterface;
use MagonxESP\BlockAutoload\Exception\BlockAutoloadException;

class BlockAutoloadTest extends TestCase {

    private $namespace = 'MagonxESP\\Tests\\SampleBlocks\\';

    private $directory = __DIR__ . '/SampleBlocks/WithNamespace';

    private $directoryNoNS = __DIR__ . '/SampleBlocks/WithoutNamespace';

    public function testBlockAutoloadContructorWithValidPath() {
        try {
            $blockAutoload = new BlockAutoload(BlockPlugin::ACF_PRO, $this->directory);
            $this->assertInstanceOf(BlockAutoload::class, $blockAutoload);
        } catch (BlockAutoloadException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testBlockAutoloadContructorWithInvalidPath() {
        $this->expectException(BlockAutoloadException::class);
        $this->expectExceptionCode(BlockAutoloadException::NOT_EXIST_PATH);
        new BlockAutoload(BlockPlugin::ACF_PRO, '/invalid/path');
    }

    public function testBlockAutoloadContructorWithRelativePath() {
        $this->expectException(BlockAutoloadException::class);
        $this->expectExceptionCode(BlockAutoloadException::NOT_ABSOLUTE_PATH);
        new BlockAutoload(BlockPlugin::ACF_PRO, '../relative/path');
    }

    public function testCreateBlockInstanceWithInterfaceImplemented() {
        $blockAutoload = new BlockAutoload(BlockPlugin::ACF_PRO, $this->directory);
        $blockAutoload->setBlockNamespace($this->namespace);
        $discovery = $blockAutoload->getBlockDiscoveryInstance();
        $blocks = $discovery->getBlocks();
        $blockObject = $blockAutoload->createBlockInstance($blocks['sample_php_block']);
        $this->assertInstanceOf(BlockInterface::class, $blockObject);
    }

    public function testCreateBlockInstanceWithoutInterfaceImplemented() {
        $blockAutoload = new BlockAutoload(BlockPlugin::ACF_PRO, $this->directory);
        $blockAutoload->setBlockNamespace($this->namespace);
        $discovery = $blockAutoload->getBlockDiscoveryInstance();
        $blocks = $discovery->getBlocks();
        $blockObject = $blockAutoload->createBlockInstance($blocks['sample_php_block_not_implemented']);
        $this->assertNull($blockObject);
    }

    public function testCreateBlockInstanceWithInterfaceImplementedNoNS() {
        $blockAutoload = new BlockAutoload(BlockPlugin::ACF_PRO, $this->directoryNoNS);
        $discovery = $blockAutoload->getBlockDiscoveryInstance();
        $blocks = $discovery->getBlocks();
        $blockObject = $blockAutoload->createBlockInstance($blocks['sample_php_block']);
        $this->assertInstanceOf(BlockInterface::class, $blockObject);
    }

    public function testCreateBlockInstanceWithoutInterfaceImplementedNoNS() {
        $blockAutoload = new BlockAutoload(BlockPlugin::ACF_PRO, $this->directoryNoNS);
        $discovery = $blockAutoload->getBlockDiscoveryInstance();
        $blocks = $discovery->getBlocks();
        $blockObject = $blockAutoload->createBlockInstance($blocks['sample_php_block_not_implemented']);
        $this->assertNull($blockObject);
    }

}
