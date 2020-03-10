<?php


namespace MagonxESP\BlockAutoload\Block;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use MagonxESP\BlockAutoload\Annotation\Block;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class BlockDiscovery {

    /**
     * The annotations namespace
     *
     * @var string $namespace
     */
    private $namespace;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var array
     */
    private $blocks = [];


    /**
     * WorkerDiscovery constructor.
     *
     * @param $namespace
     *   The namespace of the blocks
     * @param $directory
     *   The directory of the blocks
     * @param Reader $annotationReader
     */
    public function __construct($namespace, $directory, Reader $annotationReader) {
        $this->namespace = $namespace;
        $this->annotationReader = $annotationReader;
        $this->directory = $directory;
    }

    private function discoverBlocks() {
        $finder = new Finder();
        $finder->files()->in($this->directory);
        $namespace = $this->namespace;

        if (substr($namespace, -1) !== '\\') {
            $namespace .= '\\';
        }

        AnnotationRegistry::registerLoader('class_exists');

        /** @var SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            try {
                if ($fileInfo->getExtension() == 'php') {
                    $class = $namespace . $fileInfo->getBasename('.php');

                    if (!class_exists($class)) {
                        require_once $fileInfo->getRealPath();
                    }

                    /** @var Block|null $annotation */
                    $annotation = $this->annotationReader->getClassAnnotation(
                        new \ReflectionClass($class),
                        'MagonxESP\\BlockAutoload\\Annotation\\Block'
                    );

                    if ($annotation) {
                        $this->blocks[$annotation->name] = [
                            'class' => $class,
                            'annotation' => $annotation
                        ];
                    }
                }
            } catch (\ReflectionException $e) {
                continue;
            }
        }
    }

    /**
     * Returns all the workers
     *
     * @return array
     */
    public function getBlocks() {
        if(empty($this->blocks)) {
            $this->discoverBlocks();
        }

        return $this->blocks;
    }

}