<?php

/**
 * @group unit
 */
class UnitNewTest extends PHPUnit_Framework_TestCase
{
    public function testNew()
    {
        try {
            $logger = new \Log\Buffered();
        }
        catch (\Exception $e) {
        }

        $this->assertTrue($logger instanceof \Log\Buffered);
    }

    public function testNewWithParameter()
    {
        $file = '/tmp/log-buffered.log';
        try {
            $logger = new \Log\Buffered(
                array(
                    'debug'       => true,
                    'log_level'   => 'debug',
                    'trace_level' => 1,
                    'file'        => $file,
                    'buffer_size' => \Log\Buffered::DEFAULT_BUFFER_SIZE,
                )
            );
        }
        catch (\Exception $e) {
        }
        unlink($file);

        $this->assertTrue($logger instanceof \Log\Buffered);
    }

    public function testNewWithInvalidFile()
    {
        $logger    = '';
        $file      = '/tmp/log-buffered-unreadable.log';
        $exception = '';
        touch($file);
        chmod($file, 0444);
        try {
            $logger = new \Log\Buffered(
                array(
                    'debug'       => true,
                    'log_level'   => 'debug',
                    'trace_level' => 1,
                    'file'        => $file,
                    'buffer_size' => \Log\Buffered::DEFAULT_BUFFER_SIZE,
                )
            );
        }
        catch (\Exception $e) {
            $exception = $e;
        }
        unlink($file);

        $this->assertTrue($e instanceof \Exception);
        $this->assertFalse($logger instanceof \Log\Buffered);
    }

    public function testNewWithInvalidMinBufferSize()
    {
        $logger    = '';
        $exception = '';
        try {
            $logger = new \Log\Buffered(
                array(
                    'buffer_size' => \Log\Buffered::MIN_BUFFER_SIZE - 1
                )
            );
        }
        catch (\Exception $e) {
            $exception = $e;
        }

        $this->assertTrue($e instanceof \Exception);
        $this->assertFalse($logger instanceof \Log\Buffered);
    }

    public function testNewWithInvalidMaxBufferSize()
    {
        $logger    = '';
        $exception = '';
        try {
            $logger = new \Log\Buffered(
                array(
                    'buffer_size' => \Log\Buffered::MAX_BUFFER_SIZE + 1
                )
            );
        }
        catch (\Exception $e) {
            $exception = $e;
        }

        $this->assertTrue($e instanceof \Exception);
        $this->assertFalse($logger instanceof \Log\Buffered);
    }
}
