<?php


namespace MagonxESP\Tests\SampleBlocks;

use MagonxESP\BlockAutoload\Annotation\Block;
use MagonxESP\BlockAutoload\Block\BlockBase;

/**
 * Class ExamplePhpBlock
 * @package Tests\SampleBlocks
 *
 * @Block(
 *     name="sample_php_block",
 *     title="Sample PHP Block",
 *     description="Sample PHP Block",
 *     category="TestBlock",
 *     domain="my-domain",
 *     keywords={"Sample", "Block"},
 *     template="examplephpblock.template.php"
 * )
 */
class ExamplePhpBlock extends BlockBase {

    public function setup() {
        // TODO: Implement setup() method.
    }

}