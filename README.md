# Basic PHP Logger
:memo: Simple logger. Composer package.

## Install
```
composer require greezlu/ws-logger
```
## Log example
```
[2022-01-23 13:30:24] INFO: Hello world
[2022-01-23 13:30:24] WARNING: Hello world
[2022-01-23 13:30:24] CRITICAL: Hello world
```

## Basic usage
Create Logger instance with file name.
If file name was not provided, it will create a file with current date as name.
Use **log** method to write log.

You can also extend Logger class to set your own **WORKING_DIR** and **MESSAGE_TYPE_LIST** constants.
### Example
Extension *.log* to file name will be added automatically.

```php
$logger = new \WebServer\Core\Logger();
/* or */
$logger = new \WebServer\Core\Logger('my-file');

$logger->log('Hello world');
/* or */
$logger->log('Hello world', 3);
```

### Logger object

```php
/**
 * File name without extension.
 * Current date by default. 
 */
public Logger::__construct(string $fileName = null)
```

```php
/* Default message type list. */
protected Logger::MESSAGE_TYPE_LIST = [
    'INFO',
    'WARNING',
    'CRITICAL'
];
```

```php
/* Default working directory for log files. */
protected Logger::WORKING_DIR = '../var/log/';
```

```php
/* Write a log.*/
public Logger::log(string $message, int $code = 0): void
```

### Extend example
```php
class MyLogger extends \WebServer\Core\Logger
{
    protected const MESSAGE_TYPE_LIST = [
        'CREATE',
        'UPDATE',
        'DESTROY'
    ];

    protected const WORKING_DIR = '../my_var/my_log/';
}
```
