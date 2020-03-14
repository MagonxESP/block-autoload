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
    protected $annotation;

    /**
     * The block api will use for create the block instance
     *
     * @var string $blockApi
     */
    protected $blockApi;

    /**
     * Block absolute path
     *
     * @var string $absolutePath
     */
    protected $absolutePath;

    /**
     * FileSystem service
     *
     * @var Filesystem $fileSystem
     */
    protected $fileSystem;

    /**
     * The template context variables array
     *
     * @var array $context
     */
    protected $context = [];

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
     * @inheritDoc
     */
    public function render($context) {
        if ($this->annotation->template != null && $this->annotation->template != '') {
            $template = $this->annotation->template;

            if (!$this->fileSystem->isAbsolutePath($template)) {
                $template = $this->absolutePath . '/' . $template;
            }

            if ($this->fileSystem->exists($template)) {
                ob_start();
                include_once $template;
                $output = ob_get_clean();

                if ($output !== false) {
                    echo $output;
                    return;
                }
            }

            echo '';
        }
    }

}