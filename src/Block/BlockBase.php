<?php


namespace MagonxESP\BlockAutoload\Block;


abstract class BlockBase implements BlockInterface {

    /**
     * Block machine name
     *
     * @var string $name
     */
    protected $name;

    /**
     * Block title
     *
     * @var string $title
     */
    protected $title;

    /**
     * Block description
     *
     * @var string $description
     */
    protected $description;

    /**
     * Block category
     *
     * @var string $category
     */
    protected $category;

    /**
     * Block icon
     *
     * @var string $icon
     */
    protected $icon;

    /**
     * Block keywords
     *
     * @var string[] $keywords
     */
    protected $keywords;

    /**
     * Block domain
     *
     * @var string $domain
     */
    protected $domain;

    /**
     * Absolute path to block template
     *
     * @var string $template
     */
    protected $template;

    /**
     * AbstractBlock constructor.
     * @param string $name
     * @param string $title
     * @param string $description
     * @param string $category
     * @param string $icon
     * @param string[] $keywords
     * @param string $domain
     */
    public function __construct(string $name, string $title, string $description, string $domain, string $category = '', string $icon = '', array $keywords = []) {
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->icon = $icon;
        $this->keywords = $keywords;
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category) {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon) {
        $this->icon = $icon;
    }

    /**
     * @return string[]
     */
    public function getKeywords(): array {
        return $this->keywords;
    }

    /**
     * @param string[] $keywords
     */
    public function setKeywords(array $keywords) {
        $this->keywords = $keywords;
    }

    /**
     * @return string
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain) {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template) {
        $this->template = $template;
    }

    public function render() {
        if ($this->template != null && $this->template != '') {
            ob_start();
            include_once $this->template;
            $output = ob_get_clean();

            if ($output !== false) {
                echo $output;
            }
        }
    }

}