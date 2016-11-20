# ZF3-Whoops, integrated [whoops](https://github.com/filp/whoops) in Zend Framework 3

-----

![Whoops!](http://i.imgur.com/xiZ1tUU.png)

**whoops** is an error handler base/framework for PHP. Out-of-the-box, it provides a pretty
error interface that helps you debug your web projects, but at heart it's a simple yet
powerful stacked error handling system.

## Module installation
  1. `cd my/project/directory`
  2. create a `composer.json` file with following contents:

     ```json
     {
         "require": {
             "ppito/zf3-whoops": "^1.2"
         }
     }
     ```
  3. install composer via `curl -s http://getcomposer.org/installer | php` (on windows, download
     http://getcomposer.org/installer and execute it with PHP)
  4. run `php composer.phar install`
  5. open `my/project/directory/configs/modules.config.php` and add the following key :

     ```php
     'WhoopsErrorHandler', // must be added as the first module
     ```
  6. optional : copy `config/module.config.php` in `my/project/directory/config/autoload/zf3-whoops.local.php`
  7. optional : edit `my/project/directory/config/autoload/zf3-whoops.local.php`
  
## View Manager - Twig Support 
######(zf3-whoops >= 1.2.0)

By default this module use the simple php render, but you can now specify your favorite render.

`Twig render` has been add on this module, you just need to change the `template_render` configuration :
```php
'template_render' => 'zf3_whoops/twig_error',
```


## License

**ppito/zf3-whoops** is licensed under the MIT License - See the [LICENSE](LICENSE.md) file for details.

