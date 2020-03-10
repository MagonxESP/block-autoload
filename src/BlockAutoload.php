<?php


namespace MagonxESP\BlockAutoload;

use Doctrine\Common\Annotations\AnnotationReader;
use MagonxESP\BlockAutoload\Block\BlockBase;
use MagonxESP\BlockAutoload\Block\BlockDiscovery;
use MagonxESP\BlockAutoload\Block\BlockInterface;
use MagonxESP\BlockAutoload\Block\BlockPlugin;
use MagonxESP\BlockAutoload\Exception\BlockAutoloadException;
use Symfony\Component\Filesystem\Filesystem;

class BlockAutoload {

    /**
     * The block api will use
     *
     * @var string $blockApi
     */
    private $blockApi;

    /**
     * Absolute path of blocks directory
     *
     * @var string $blocksDirectory
     */
    private $blocksDirectory;

    /**
     * @var Filesystem $fileSystem
     */
    private $fileSystem;

    /**
     * Blocks namespace
     *
     * @var string $blocksNamespace
     */
    private $blocksNamespace;

    /**
     * BlockAutoload constructor.
     *
     * @param string $blockApi
     * @param string $blocksDirectoryAbsPath Blocks directory absolute path
     *
     * @throws BlockAutoloadException
     */
    public function __construct($blockApi, $blocksDirectoryAbsPath) {
        $this->blockApi = $blockApi;
        $this->fileSystem = new Filesystem();
        $this->setBlocksDirectory($blocksDirectoryAbsPath);
    }

    /**
     * Set the blocks directory will autoload
     *
     * @param string $blocksDirectoryAbsPath
     *
     * @throws BlockAutoloadException
     */
    public function setBlocksDirectory(string $blocksDirectoryAbsPath) {
        $is_absolute = $this->fileSystem->isAbsolutePath($blocksDirectoryAbsPath);
        $exists = $this->fileSystem->exists($blocksDirectoryAbsPath);

        if (!$is_absolute) {
            throw new BlockAutoloadException("The blocks directory path should be absolute");
        }

        if (!$exists) {
            throw new BlockAutoloadException("The blocks directory does not exists");
        }

        if (substr($blocksDirectoryAbsPath, -1) === '/') {
            $blocksDirectoryAbsPath = substr($blocksDirectoryAbsPath, 0, strlen($blocksDirectoryAbsPath) - 1);
        }

        $this->blocksDirectory = $blocksDirectoryAbsPath;
    }

    /**
     * Set the blocks namespace
     *
     * @param string $blocksNamespace
     */
    public function setBlockNamespace($blocksNamespace) {
        $this->blocksNamespace = $blocksNamespace;
    }

    /**
     * Create block instance
     */
    private function createBlockInstance() {
        $instance = null;

        switch($this->blockApi) {
            case BlockPlugin::ACF_PRO:
                break;
        }

        return $instance;
    }

    /**
     * Return the block instance if it has a user defined class
     *
     * @param string $blockName
     *
     * @return BlockInterface|null
     */
    private function hasBlockClass($blockName) {
        $blockFile = $this->blocksDirectory . '/' . $blockName . '/' . $blockName . '.php';
        $instance = null;

        if ($this->fileSystem->exists($blockFile)) {
            require_once $blockFile;

            $class = ucfirst(strtolower($blockName));

            if ($this->blocksNamespace != null && $this->blocksNamespace != '') {
                $class = $this->blocksNamespace . $class;
            }

            if (class_exists($class)) {
                $instance = new $class();
            }
        }

        return $instance;
    }

    /**
     * Scan block directory and initialize the blocks
     */
    private function scanBlocksDirectory() {
        $blocks = scandir($this->blocksDirectory);

        foreach ($blocks as $blockDirectory) {
            $blockAbsPath = $this->blocksDirectory . '/' . $blockDirectory;
            $blockName = $blockDirectory;
            $block = $this->hasBlockClass($blockName);

            if (!$block) {
                // TODO instance simple block
            }
        }
    }

    /**
     * Load the blocks
     */
    public function load() {
        $discovery = new BlockDiscovery($this->blocksNamespace, $this->blocksDirectory, new AnnotationReader());
        $blocks = $discovery->getBlocks();

        foreach ($blocks as $block) {
            /** @var Block $annotation */
            $annotation = $block['annotation'];
            $class = $block['class'];
            /** @var BlockBase $blockInstance */
            $blockInstance = new $class($annotation, $this->blockApi);
            $blockInstance->register();
        }
    }

}