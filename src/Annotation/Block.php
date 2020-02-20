<?php


namespace MagonxESP\BlockAutoload\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Block
 * @package MagonxESP\BlockAutoload\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class Block {

    /**
     * Block machine name
     *
     * @var string $name
     */
    public $name;

    /**
     * Block title
     *
     * @var string $title
     */
    public $title;

    /**
     * Block description
     *
     * @var string $description
     */
    public $description;

    /**
     * Block category
     *
     * @var string $category
     */
    public $category;

    /**
     * Block icon
     *
     * @var string $icon
     */
    public $icon;

    /**
     * Block keywords
     *
     * @var string[] $keywords
     */
    public $keywords;

    /**
     * Block domain
     *
     * @var string $domain
     */
    public $domain;

    /**
     * Absolute path to block template
     *
     * @var string $template
     */
    public $template;

}