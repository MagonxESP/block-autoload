<?php


use MagonxESP\BlockAutoload\Annotation\Block;
use MagonxESP\BlockAutoload\Block\BlockBase;

/**
 * Class ExamplePhpBlock
 * @package Tests\SampleBlocks
 *
 * @Block(
 *     name="sample_php_bad_block",
 *     title="Sample PHP Bad Block",
 *     description="Sample PHP Bad Block",
 *     category="TestBlock",
 *     domain="my-domain",
 *     keywords={"Sample", "Block"},
 *     template="examplephpblock.template.php"
 * )
 */
class ExamplePhpBadBlockBad extends BlockBase {

    public function setup() {
        // TODO: Implement setup() method.
    }

}