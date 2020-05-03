<?php


namespace MagonxESP\BlockAutoload;

use Doctrine\Common\Annotations\AnnotationReader;
use MagonxESP\BlockAutoload\Annotation\Block;
use MagonxESP\BlockAutoload\Block\BlockDiscovery;
use MagonxESP\BlockAutoload\Block\BlockInterface;
use MagonxESP\BlockAutoload\Block\BlockRegistry;
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
    private $blocksNamespace = '';

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
            throw new BlockAutoloadException(
                "The blocks directory path should be absolute",
                BlockAutoloadException::NOT_ABSOLUTE_PATH
            );
        }

        if (!$exists) {
            throw new BlockAutoloadException(
                "The blocks directory does not exists",
                BlockAutoloadException::NOT_EXIST_PATH
            );
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
     * Create the instance of discovered block
     *
     * @param array $block The block array
     * @return BlockInterface|null
     */
    public function createBlockInstance(array $block) {
        /** @var Block $annotation */
        $annotation = $block['annotation'];
        $class = $block['class'];
        /** @var BlockInterface $blockInstance */
        $blockObject = new $class($annotation, $this->blockApi, $block['absolute_path']);

        if ($blockObject instanceof BlockInterface) {
            return $blockObject;
        }

        return null;
    }

    /**
     * Get the prepared BlockDiscovery instance
     *
     * @return BlockDiscovery
     */
    public function getBlockDiscoveryInstance() {
        return new BlockDiscovery($this->blocksNamespace, $this->blocksDirectory, new AnnotationReader());
    }

    /**
     * Discover and register the blocks
     */
    public function load() {
        $discovery = $this->getBlockDiscoveryInstance();
        $blocks = $discovery->getBlocks();

        foreach ($blocks as $block) {
            $blockObject = $this->createBlockInstance($block);

            if ($blockObject) {
                BlockRegistry::register($blockObject, $this->blockApi);
            }
        }
    }

}