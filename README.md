# Block Autoload

Simple block autoloader for define and autoload wordpress gutemberg blocks using OOP paradigm.

WARNING! This is currently in development and many upcoming features will include is not in the package. The usage documentation will be update for new development releases, or pre releases.

## Getting started

### Setting up composer

NOTE: Skip this step is not required if you have a composer based wordpress instalation or you have composer in your theme.

* Initialize composer in your current theme
    ```shell script
    $ cd wp-content/themes/current-theme
    $ composer init
    ```

* Require composer autoloader for autoload composer dependencies at the first line of functions.php
    ```php
    require_once 'vendor/autoload.php';
    ```

### Instalation and usage

Install the library using composer
```shell script
$ composer require magonxesp/block-autoload
```
#### Creating the blocks directory and your first block structure

Create the block directory in your theme
```shell script
$ mkdir blocks
```
Inside the blocks directory create your first block example
```shell script
$ cd blocks
$ mkdir example
$ touch example/Example.php # the block class
$ touch example/example.template.php # the block template
```

Define the example block class 

```php
// example/Example.php
namespace YourThemeNamespace\Blocks;

use MagonxESP\BlockAutoload\Annotation\Block;
use MagonxESP\BlockAutoload\Block\BlockBase;

/**
 * Class Example
 * 
 * @Block(
 *     name="example",
 *     title="Example",
 *     description="Example block",
 *     icon="",
 *     domain="my-site",
 *     category="custom",
 *     keywords={"keyword1", "keyword2"},
 *     template="example.template.php"
 * )
 */
class Example extends BlockBase {
    
    public $hello;

    public function setup() {
        $this->hello = 'Hello wordpress';
    }
}
```

Adding content to the template, and use defined ``$context`` variable for access to block class public properties

```
<!-- example/example.template.php -->
<h1><?php echo $context['hello']; ?></h1>
```

#### Autoloading blocks by the ACF PRO plugin block API
```php
// functions.php
use MagonxESP\BlockAutoload\BlockAutoload;
use MagonxESP\BlockAutoload\Block\BlockPlugin;
    
add_action('init', function() {
    // autoload blocks registering them using the ACF PRO Block API (Require ACF PRO plugin installed and activated)
    $block_autoloader = new BlockAutoload(BlockPlugin::ACF_PRO, __DIR__ . '/blocks');
    $block_autoloader->setBlockNamespace('YourThemeNamespace\\Blocks\\');
    $block_autoloader->load();
});
```

## Upcoming features

- Using block class properties as template variables
- Compatibility with the wordpress block api (defined in current version but does nothing)
- Compatibility with Twig templates (using Timber plugin)
- Possibility to alter the render output with wordpress filters