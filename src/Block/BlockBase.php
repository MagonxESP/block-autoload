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
     * @inheritDoc
     */
    public function register() {
        BlockRegistry::register($this, $this->blockApi);
    }

    /**
     * @inheritDoc
     */
    public function getContext() {
        $reflection = new \ReflectionClass($this);
        $propertyes = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        $context = [];

        foreach ($propertyes as $property) {
            $context[$property->getName()] = $property->getValue();
        }

        return $context;
    }

    /**
     * @inheritDoc
     */
    public function doRender(...$args) {
        global $context;

        $this->setup();
        $context = $this->getContext();
        $block_vars = [
            'context' => $context,
            'args' => $args
        ];

        $block_vars = apply_filters('block_autoload_before_render', $this->annotation->name, $block_vars);
        $context = $block_vars['context'];
        $args = $block_vars['args'];

        $this->render(...$args);

        do_action('block_autoload_after_render', $this->annotation->name, $block_vars);
    }

    /**
     * @inheritDoc
     */
    public function render($settings, $content = '', $is_preview = false) {
        if ($this->annotation->template != null && $this->annotation->template != '') {
            $template = $this->annotation->template;

            if (!$this->fileSystem->isAbsolutePath($template)) {
                $template = $this->absolutePath . '/' . $template;
            }

            ob_start();
            include_once $template;
            $output = ob_get_clean();

            if ($output !== false) {
                echo $output;
            }
        }
    }

}