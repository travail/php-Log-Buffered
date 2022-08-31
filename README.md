\Log\Beffered
========

## NAME

Log\Buffered -

## SYNOPSIS

```php
use Log\Buffered;

require_once '/path/to/vendor/autoload.php';

$log = new Buffered(
    [
        'file'        => '/paht/to/myapp.log', // stderr by default
        'buffer_size' => 10240,                // 5120 bytes by default
    ]
);
$log->debugf('This is a %s message', 'debug'); // This message never be output
$log->infof('This is an %s message', 'info');
$log->warnf('This is a %s message', 'warn');
$log->critf('This is a %s message', 'crit');
```

## INSTALLATION
To install this package into your project via composer, add the following snippet to your `composer.json`. Then run `composer install`.

```
"require": {
    "travail/log-buffered": "dev-master"
}
```

If you want to install from gihub, add the following:

```
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:travail/php-Log-Buffered.git"
    }
]
```

## DEPENDENCIES

Log\Buffered has the dependency on the following:

* [Log\Minimal](https://github.com/travail/php-Log-Minimal)

## METHODS

### __construct

`__constract(array $attrs)`

#### Parameters

`$attrs` can contain the keys below:

##### file

Path to a file into which logger writes.

##### color

Outputs colored messages if set a true value, `false` by default.

##### debug

Outputs debug messages if set a true value, `false` by default.

##### log_level

The lower limit of the log level. `info` by default.
**NOTE: To output debug messages set `debug` to `log_level` and a true value to `debug`.**

##### trace_level

The depth of stack trace. `1` by default.

##### buffer_size

Set the size of buffer which allow to buffer messages within given size. `5120 bytes` by default. You must specify this between `Log\Buffered::MIN_BUFFER_SIZE` and `Log\Buffered::MAX_BUFFER_SIZE`, or an `Exception` will be thrown.

### debugf

`void debugf(string $format [, mixed $val [, mixed ...]])`

### infof

`void infof(string $format [, mixed $val [, mixed ...]])`

### warnf

`void infof(string $format [, mixed $val [, mixed ...]])`

### critf

`void infof(string $format [, mixed $val [, mixed ...]])`

These methods above buffer messages until buffered messages reach `buffer_size` you specified. If you try to buffer messages more than `buffer_size` the logger will flush buffered messages automatically with the caution below.

```
***************************** CAUTION!!! **********************************
Flush the buffer, because the size of buffered messages reached 1024.
If you want to buffer more messages, set the more large size to buffer_size
in the constructor or by calling Log\Buffered::setBufferSize().
```

#### Parameters

The same as the built-in function `sprintf`.

### append

`void append(string $message)`

Append a given message to the buffer. Usually you don't have to use this directly.

#### Parameters

##### $message

A string to be buffered.

### flush

`void flush(void)`

Flush buffered messages.

### clear

`void clear(void)`

Clear the buffer.

### getMinBufferSize

`int getMinBufferSize(void)`

Returns the size of min buffer size `1024` bytes.

### getMaxBufferSize

`int getMaxBufferSize(void)`

Returns the size of max buffer size `5120000` bytes.

### getBufferedSize

`int getBufferedSize(void)`

Returns the size of buffered messages.

## ENABLING DEBUG MESSAGES

There are two ways to enable debug messages:

1. By Environment Values
1. By Constructor

### By Environment Values

```php
$_SERVER['LM_DEBUG']     = true;
$_SERVER['LM_LOG_LEVEL'] = 'debug';
$log = new Log\Buffered();
$log->debugf('This is a %s message: %s', 'debug');
```

### By Constructor

```php
$log = new Log\Buffered(
    [
        'debug'     => true,
        'log_level' => 'debug',
    ]
);
$log->debugf('This is a %s message: %s', 'debug');
```

## AUTHOR

travail

## LICENSE

This library is free software. You can redistribute it and/or modify it under the same terms as PHP itself.
