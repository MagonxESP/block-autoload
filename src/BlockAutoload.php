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
            $blockInstance = new $class($annotation, $this->blockApi, $block['absolute_path']);
            $blockInstance->register();
        }
    }

}