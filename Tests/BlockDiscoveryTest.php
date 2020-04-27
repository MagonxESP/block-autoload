<?php

use PHPUnit\Framework\TestCase;
use MagonxESP\BlockAutoload\Block\BlockDiscovery;
use Doctrine\Common\Annotations\AnnotationReader;
use MagonxESP\BlockAutoload\Block\BlockInterface;
use MagonxESP\BlockAutoload\Block\BlockPlugin;
use MagonxESP\BlockAutoload\Annotation\Block;

class BlockDiscoveryTest extends TestCase {

    private $namespace = 'MagonxESP\\Tests\\SampleBlocks\\';

    private $directory = __DIR__ . '/SampleBlocks';

    private $directoryNoNS = __DIR__ . '/SampleBlocksNoNS';

    public function testBlockIsAvailable() {
        $discovery = new BlockDiscovery($this->namespace, $this->directory, new AnnotationReader());
        $blocks = $discovery->getBlocks();

        $this->assertArrayHasKey('sample_php_block', $blocks);
    }

    public function testNoGetInvalidBlock() {
        $discovery = new BlockDiscovery($this->namespace, $this->directory, new AnnotationReader());
        $blocks = $discovery->getBlocks();

        $this->assertArrayNotHasKey('sample_php_bad_block', $blocks);
    }

    public function testNoGetBlockWithoutNS() {
        $discovery = new BlockDiscovery($this->namespace, $this->directory, new AnnotationReader());
        $blocks = $discovery->getBlocks();

        $this->assertArrayNotHasKey('sample_php_block_no_ns', $blocks);
    }

    public function testBlockHasAnnotation() {
        $discovery = new BlockDiscovery($this->namespace, $this->directory, new AnnotationReader());
        $blocks = $discovery->getBlocks();
        $annotation = $blocks['sample_php_block']['annotation'];

        $this->assertInstanceOf(Block::class, $annotation);
    }

    public function testBlockCanBeLoaded() {
        $discovery = new BlockDiscovery($this->namespace, $this->directory, new AnnotationReader());
        $blocks = $discovery->getBlocks();
        $block_class = $blocks['sample_php_block']['class'];
        $annotation = $blocks['sample_php_block']['annotation'];
        $blockObject = new $block_class($annotation, BlockPlugin::ACF_PRO, $blocks['sample_php_block']['absolute_path']);

        $this->assertInstanceOf(BlockInterface::class, $blockObject);
    }

    public function testBlockIsAvailableWithoutNS() {
        $discovery = new BlockDiscovery('', $this->directoryNoNS, new AnnotationReader());
        $blocks = $discovery->getBlocks();

        $this->assertArrayHasKey('sample_php_block', $blocks);
    }

    public function testNoGetInvalidBlockWithoutNS() {
        $discovery = new BlockDiscovery('', $this->directoryNoNS, new AnnotationReader());
        $blocks = $discovery->getBlocks();

        $this->assertArrayNotHasKey('sample_php_bad_block', $blocks);
    }

    public function testBlockHasAnnotationWithoutNS() {
        $discovery = new BlockDiscovery('', $this->directoryNoNS, new AnnotationReader());
        $blocks = $discovery->getBlocks();
        $annotation = $blocks['sample_php_block']['annotation'];

        $this->assertInstanceOf(Block::class, $annotation);
    }

    public function testBlockCanBeLoadedWithoutNS() {
        $discovery = new BlockDiscovery('', $this->directoryNoNS, new AnnotationReader());
        $blocks = $discovery->getBlocks();
        $block_class = $blocks['sample_php_block']['class'];
        $annotation = $blocks['sample_php_block']['annotation'];
        $blockObject = new $block_class($annotation, BlockPlugin::ACF_PRO, $blocks['sample_php_block']['absolute_path']);

        $this->assertInstanceOf(BlockInterface::class, $blockObject);
    }

    public function testNoGetBlockWithNS() {
        $discovery = new BlockDiscovery('', $this->directoryNoNS, new AnnotationReader());
        $blocks = $discovery->getBlocks();

        $this->assertArrayNotHasKey('sample_php_block_with_ns', $blocks);
    }

}