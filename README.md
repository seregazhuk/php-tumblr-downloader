# PHP Tumblr Downloader

## Installation
The recommended way to install this library is via [Composer](https://getcomposer.org). 
[New to Composer?](https://getcomposer.org/doc/00-intro.md)

```
composer require seregazhuk/tumblr-downloader
```

## Quick Start

### Script

```php
require "vendor/autoload.php";

use Tumblr\API\Client;
use seregazhuk\TumblrDownloader\Downloader;

$client = new Client(
    'YourConsumerKey', 
    'YourConsumerSecret', 
    'YourToken', 
    'YourSecret'
);

$downloader = new Downloader($client);
$downloader->save('some_blog.tumblr.com');
```

## Console command

You can also use console command `tumblr-downloader` in your terminal. Setup your API
credentials in `config.php` file, and you are ready to go:

```php
return [
    'consumer_key'    => 'YourConsumerKey',
    'consumer_secret' => 'YourConsumerSecret',
    'token'           => 'YourToken',
    'token_secret'    => 'YourSecret',
];
```

This command will start downloading images from `catsof.tumblr.com` blog:

```bash
$ ./tumblr-downloader save catsof.tumblr.com
```
