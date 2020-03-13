<?php


namespace MagonxESP\BlockAutoload\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Required;

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
     * @Required
     */
    public $name;

    /**
     * Block title
     *
     * @var string $title
     * @Required
     */
    public $title;

    /**
     * Block description
     *
     * @var string $description
     * @Required
     */
    public $description;

    /**
     * Block category
     *
     * @var string $category
     * @Required
     */
    public $category;

    /**
     * Block icon
     *
     * @var string $icon
     */
    public $icon = '';

    /**
     * Block keywords
     *
     * @var string[] $keywords
     */
    public $keywords = [];

    /**
     * Block domain
     *
     * @var string $domain
     * @Required
     */
    public $domain;

    /**
     * Path to block template
     *
     * @var string $template
     * @Required
     */
    public $template;

}