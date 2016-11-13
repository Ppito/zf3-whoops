# Whoops-ZF3, integrated [whoops](https://github.com/filp/whoops) in ZF3 Framework

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
             "ppito/zf3-whoops": "^1.0"
         }
     }
     ```
  3. install composer via `curl -s http://getcomposer.org/installer | php` (on windows, download
     http://getcomposer.org/installer and execute it with PHP)
  4. run `php composer.phar install`
  5. open `my/project/directory/configs/application.config.php` and add the following key to your `modules`, :

     ```php
     'WhoopsErrorHandler',   // must be added as the first module
     ```
  6. copy `config/module.config.php` in `my/project/directory/config/autoload/zf3-whoops.local.php`
  7. edit `my/project/directory/config/autoload/zf3-whoops.local.php`
  
  

## License

**ppito/zf3-whoops** is licensed under the MIT License - See the [LICENSE](LICENSE.md) file for details.

