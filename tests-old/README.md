#Testing FrameworkOnFramework

Testing will require a "special" environment and you should take care of some conventions.
This document will help you on setting up everything in order to be ready to test.

## Setting up

You have to follow these steps:
+ Joomla! installation
+ Install and configure Composer
+ Configure your environment
+ Start testing!

### Joomla! installation
We need a Joomla! installation in order to interact with database and use Joomla! libraries. At the moment, tests are performed against Joomla! 3.x
When you install your Joomla! site your db prefix **must be _jos__**. We have to do that because PHPUnit can import data before every test, but we must use fixed table names, ie `jos_assets`.

You should use **MySQLi** as database driver; if you're getting any problems, try using `127.0.0.1` instead of `localhost` as your server name.

### Install and configure Composer
We need some packages that are available through Composer only, so you have to install it inside FOF root. A copy of
Composer is already included for your convenience.

First, update Composer itself:
`php composer.phar selfupdate`

Now you have to run the installer:  
`php composer.phar install`

Great, now you're ready to go!

### Configure your environment
Inside the tests folder, you'll find a file named `config.dist.php`; you have to rename it to `config.php` and put
the _absolute_ path to your Joomla! guinea pig.

### Start testing!

Go inside the tests directory and run `./run-tests.sh` to run all tests.

To run a specific tests group use `./run-tests.sh --group myGroupName`

Remember that the php binary must be in your path and must meet all FOF requirements, including mcrypt support
