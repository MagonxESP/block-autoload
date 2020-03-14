<?php


namespace MagonxESP\BlockAutoload\Block;


class BlockPlugin {
    const ACF_PRO = 'acf_pro';
    const WP_BLOCK_API = 'wp_block_api';

    /**
     * Create an associative array with positional arguments values
     *
     * @param array $map
     *      The positional argumants map, the key is the argument position and the value is the argumant name
     * @param array $args
     *      The positional arguments to map to an associative array
     */
    private static function mapArgs($map, $args) {
        $arguments_array = [];

        foreach ($args as $position => $arg) {
            if (isset($map[$position])) {
                $arguments_array[$map[$position]] = $arg;
            } else {
                $arguments_array['args'][] = $args;
            }
        }

        return $arguments_array;
    }

    /**
     * Map the arguments name and parse the render callback positional arguments to assciative array
     *
     * @param $args
     *      The ACF PRO Block api render callback positional arguments
     * @return array
     */
    private static function parseAcfArgs($args) {
        $arguments_map = [
            0 => 'block_settings',
            1 => 'content',
            2 => 'is_preview',
            3 => 'post_id'
        ];

        return self::mapArgs($arguments_map, $args);
    }

    /**
     * Parse the render callback positional arguments to associative array, defining the key as the argument name
     *
     * @param $blockApi
     *      The block api will inject positional arguments to render callback
     * @param array $args
     *      The callback positional arguments will parse to associative array
     * @return array
     *      The associative array with the arguments values
     */
    public static function parseRenderArgs($blockApi, $args) {
        switch ($blockApi) {
            case self::ACF_PRO:
                return self::parseAcfArgs($args);
//            case self::WP_BLOCK_API:
//                break;
            default:
                return [
                    'args' => $args
                ];
        }
    }
}