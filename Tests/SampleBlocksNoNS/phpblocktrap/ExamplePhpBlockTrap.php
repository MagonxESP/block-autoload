<?php

namespace MagonxESP\Tests\SampleBlocksNoNS;

use MagonxESP\BlockAutoload\Annotation\Block;
use MagonxESP\BlockAutoload\Block\BlockBase;

/**
 * Class ExamplePhpBlock
 * @package Tests\SampleBlocks
 *
 * @Block(
 *     name="sample_php_block_with_ns",
 *     title="Sample PHP Block With NS",
 *     description="Sample PHP Block With NS",
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