<?php


namespace MagonxESP\BlockAutoload\Block;


use MagonxESP\BlockAutoload\Annotation\Block;
use Symfony\Component\Filesystem\Filesystem;

abstract class BlockBase implements BlockInterface {

    /**
     * Block properties
     *
     * @var Block $annotation
     */
    private $annotation;

    /**
     * The block api will use for create the block instance
     *
     * @var string $blockApi
     */
    private $blockApi;

    /**
     * Block absolute path
     *
     * @var string $absolutePath
     */
    private $absolutePath;

    /**
     * FileSystem service
     *
     * @var Filesystem $fileSystem
     */
    private $fileSystem;

    /**
     * AbstractBlock constructor.
     *
     * @param Block $annotation
     * @param string $blockApi
     * @param string $absolutePath
     */
    public function __construct(Block $annotation, $blockApi, $absolutePath) {
        $this->fileSystem = new Filesystem();
        $this->annotation = $annotation;
        $this->blockApi = $blockApi;
        $this->absolutePath = $absolutePath;
    }

    /**
     * @inheritDoc
     */
    public function getBlockInfo() {
        return $this->annotation;
    }

    /**
     * Get the class public properties
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getProperties() {
        $props = [];

        try {
            $reflection = new \ReflectionClass($this);
            $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

            foreach ($properties as $property) {
                $props[$property->getName()] = $property->getValue($this);
            }
        } catch (\ReflectionException $e) {
            error_log($e->getMessage(), 0);
        }

        return $props;
    }

    /**
     * @inheritDoc
     */
    public function getContext() {
        return $this->getProperties();
    }

    /**
     * @inheritDoc
     */
    public function doRender(...$args) {
        $this->setup();
        $context = BlockPLugin::parseRenderArgs($this->blockApi, $args);
        $context += $this->getContext();
        // alter the block context if necesary
        $context = apply_filters('block_autoload_before_render', $context, $this->annotation->name);
        // render the block
        $this->render($context);
        // after render block action
        do_action('block_autoload_after_render', $context, $this->annotation->name);
    }

    /**
     * Call a render callback for render the block template.
     *
     * @param callable $render_callback
     *      The function callback on render the block template.
     *      Requires the $template argument and recomend to use $context for template variables
     *
     * @example
     *      $this->createRenderScope(function($template) use ($context) {
     *          // use $context if you will inject variables on the rendered template
     *          include_once $template;
     *      });
     */
    protected function createRenderScope($render_callback) {
        if ($this->annotation->template != null && $this->annotation->template != '') {
            $template = $this->annotation->template;

            if (!$this->fileSystem->isAbsolutePath($template)) {
                $template = $this->absolutePath . '/' . $template;
            }

            if ($this->fileSystem->exists($template)) {
                $render_callback($template);
            }

            echo '';
        }
    }

    /**
     * @inheritDoc
     */
    public function render($context) {
        $this->createRenderScope(function($template) use ($context) {
            ob_start();
            include_once $template;
            $output = ob_get_clean();

            if ($output !== false) {
                echo $output;
            }
        });
    }

}