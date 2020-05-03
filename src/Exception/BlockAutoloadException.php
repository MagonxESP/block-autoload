<?php


namespace MagonxESP\BlockAutoload\Exception;

/**
 * Class BlockAutoloadException
 * @package MagonxESP\BlockAutoload\Exception
 */
class BlockAutoloadException extends \Exception {

    public const NOT_EXIST_PATH = 1;
    public const NOT_ABSOLUTE_PATH = 2;
    public const NOT_SUPPORTED_BLOCK_API = 3;

}