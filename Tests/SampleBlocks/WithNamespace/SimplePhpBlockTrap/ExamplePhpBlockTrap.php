<?php


use MagonxESP\BlockAutoload\Annotation\Block;
use MagonxESP\BlockAutoload\Block\BlockBase;

/**
 * Class ExamplePhpBlock
 * @package Tests\SampleBlocks
 *
 * @Block(
 *     name="sample_php_block_no_ns",
 *     title="Sample PHP Block Without NS",
 *     description="Sample PHP Block Without NS",
 *     category="TestBlock",
 *     domain="my-domain",
 *     keywords={"Sample", "Block"},
 *     template="examplephpblock.template.php"
 * )
 */
class ExamplePhpBlockTrap extends BlockBase {

    public function setup() {
        // TODO: Implement setup() method.
    }

}