<?php

namespace Log;

use InvalidArgumentException;

class Buffered extends Minimal
{
    /**
     * @var string The version of this package
     */
    public const VERSION = '0.2.0';

    /**
     * @var int Min buffer size
     */
    public const MIN_BUFFER_SIZE = 1024; // 1K

    /**
     * @var int Max buffer size
     */
    public const MAX_BUFFER_SIZE = 5120000; // 5M

    /**
     *  @var int Default buffer size
     */
    public const DEFAULT_BUFFER_SIZE = 5120; // 5K

    /**
     * @var string Default file path to which logger write
     */
    public const DEFAULT_FILE = 'php://stderr';

    /**
     * @var string Buffer in which logger store messages
     */
    protected $buffer= '';

    /**
     * @var int Buffer size
     */
    protected $buffer_size = self::DEFAULT_BUFFER_SIZE;

    /**
     * @var string File path to which logger write, self::DEFAULT_FILE by default
     */
    protected $file = self::DEFAULT_FILE;

    /**
     * @var resource File handle
     */
    protected $fd;

    /**
     * Create a new instance of Log\Buffered
     *
     * @param array $attrs
     * @throws InvalidArgumentException
     */
    public function __construct($attrs = [])
    {
        if (isset($attrs['color'])) {
            parent::$color = $attrs['color'];
        }
        if (isset($attrs['debug'])) {
            parent::$debug = $attrs['debug'];
        }
        if (isset($attrs['log_level'])) {
            parent::$log_level = $attrs['log_level'];
        }
        if (isset($attrs['trace_level'])) {
            parent::$trace_level = $attrs['trace_level'];
        }

        if (isset($attrs['file']) && file_exists($attrs['file'])) {
            if (!is_readable($attrs['file'])) {
                throw new InvalidArgumentException(sprintf('%s is not readable', $attrs['file']));
            }
            if (!is_writable($attrs['file'])) {
                throw new InvalidArgumentException(sprintf('%s is not writable', $attrs['file']));
            }
        }

        if (isset($attrs['file'])) {
            $this->file = $attrs['file'];
        }
        $this->fd = fopen($this->file, 'a+');

        if (isset($attrs['buffer_size']) && $attrs['buffer_size'] < self::MIN_BUFFER_SIZE) {
            throw new InvalidArgumentException('buffer_size must be more than ' . self::MIN_BUFFER_SIZE);
        }
        if (isset($attrs['buffer_size']) && $attrs['buffer_size'] > self::MAX_BUFFER_SIZE) {
            throw new InvalidArgumentException('buffer_size must be less than ' . self::MAX_BUFFER_SIZE);
        }
        if (isset($attrs['buffer_size'])) {
            $this->buffer_size = $attrs['buffer_size'];
        }

        $obj = $this;
        parent::$print = static function($time, $type, $msg, $trace, $raw_msg) use ($obj)
        {
            $obj->append(sprintf("%s [%s] %s at %s line %s\n",
                    $time, $type, $msg, $trace['file'], $trace['line']));
        };
    }

    /**
     * Append a message into the buffer.
     *
     * @param string $message A message
     * @return void
     */
    public function append($message): void
    {
        $this->buffer .= $message;
        if ($this->getBufferedSize() > $this->buffer_size) {
            $this->overflow();
        }
    }

    /**
     * Flush messages.
     *
     * @return void
     */
    public function flush(): void
    {
        fwrite($this->fd, $this->buffer);
        $this->clear();
    }

    /**
     * Clear the buffer.
     *
     * @return void
     */
    public function clear(): void
    { 
        $this->buffer = '';
    }

    /**
     * Print the caution and flush the buffer
     * when buffered messages reach Log\Buffered::MAX_BUFFER_SIZE.
     *
     * @return void
     */
    protected function overflow(): void
    {
        $this->buffer .= sprintf(
            "***************************** CAUTION!!! **********************************\n" .
            "Flush the buffer, because the size of buffered messages reached %d.\n" .
            "If you want to buffer more messages, set the more large size to buffer_size\n" .
            "in the constructor or by calling Log\Buffered::setBufferSize().\n" .
            "***************************************************************************\n",
            $this->buffer_size);
        $this->flush();
    }

    /**
     * Return Log\Buffered::MIN_BUFFER_SIZE.
     *
     * @return int
     */
    public function getMinBufferSize(): int
    {
        return self::MIN_BUFFER_SIZE;
    }

    /**
     * Return Log\Buffered::MAX_BUFFER_SIZE.
     *
     * @return int
     */
    public function getMaxBufferSize(): int
    {
        return self::MAX_BUFFER_SIZE;
    }

    /**
     * Return the size of buffer.
     *
     * @return int Size of buffer
     */
    public function getBufferSize(): int
    {
        return $this->buffer_size;
    }

    /**
     * Set passed size to buffer size.
     *
     * @param int $size Size of the buffer
     * @return void
     */
    public function setBufferSize($size): void
    {
        $this->buffer_size = $size;
    }

    /**
     * Return current size of buffered messages.
     *
     * @return int Current size of buffered messages
     */
    public function getBufferedSize(): int
    {
        if ($this->buffer === null) {
            return 0;
        }
        return strlen($this->buffer);
    }

    /**
     * Return the path to file to which logger print messages.
     *
     * @return string The path to file to which logger print messages
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Flush the buffer when messages buffered.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->getBufferedSize() > 0) {
            $this->flush();
        }
    }
}
