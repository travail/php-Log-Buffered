<?php

namespace Log;

class Buffered extends \Log\Minimal
{
    const MIN_BUFFER_SIZE     = 1024;
    const MAX_BUFFER_SIZE     = 5120000;
    const DEFAULT_BUFFER_SIZE = 5120;

    protected $buffer      = null;
    protected $buffer_size = self::DEFAULT_BUFFER_SIZE;

    function __construct($attrs = array())
    {
        if (isset($attrs['color']))
            parent::$color = $attrs['color'];
        if (isset($attrs['debug']))
            parent::$debug = $attrs['debug'];
        if (isset($attrs['log_level']))
            parent::$log_level = $attrs['log_level'];
        if (isset($attrs['trace_level']))
            parent::$trace_level = $attrs['trace_level'];

        if (isset($attrs['buffer_size']) && $attrs['buffer_size'] < self::MIN_BUFFER_SIZE)
            throw new \Exception('buffer_size must be more than ' . self::MIN_BUFFER_SIZE);
        if (isset($attrs['buffer_size']) && $attrs['buffer_size'] > self::MAX_BUFFER_SIZE)
            throw new \Exception('buffer_size must be less than ' . self::MAX_BUFFER_SIZE);
        if (isset($attrs['buffer_size']))
            $this->buffer_size = $attrs['buffer_size'];
        
        $obj = $this;
        parent::$print = function($time, $type, $msg, $trace, $raw_msg) use ($obj)
        {
            $obj->append(sprintf("%s [%s] %s at %s line %s\n",
                    $time, $type, $msg, $trace['file'], $trace['line']));
        };
    }

    public function append($message)
    {
        $this->buffer .= $message;
        if ($this->length() > $this->buffer_size) {
            $this->overflow();
        }
    }

    public function flush()
    {
        fputs(STDERR, $this->buffer);
        $this->clear();
    }

    public function clear()
    { 
        $this->buffer = null;
    }

    public function length()
    {
        return strlen($this->buffer);
    }

    protected function overflow()
    {
        $this->buffer .= sprintf(
            "***************************** CAUTION!!! **********************************\n" .
            "Flush the buffer, because the size of buffered messages reached %d.\n" .
            "If you want to buffer more messages, set the more large size to buffer_size\n" .
            "in the constructor or by calling \Log\Buffered::setBufferSize().\n" .
            "***************************************************************************\n",
            $this->buffer_size);
        $this->flush();
    }

    public function getMinBufferSize()
    {
        return self::MIN_BUFFER_SIZE;
    }

    public function getMaxBufferSize()
    {
        return self::MAX_BUFFER_SIZE;
    }

    public function getBufferSize()
    {
        return $this->buffer_size;
    }

    public function setBufferSize($size)
    {
        $this->buffer_size = $size;
    }

    function __destruct()
    {
        if ($this->length() > 0) $this->flush();
    }
}
