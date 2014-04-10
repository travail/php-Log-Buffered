<?php

/**
 * @group unit
 */
class UnitNewTest extends PHPUnit_Framework_TestCase
{
    public function testNew()
    {
        $logger = null;
        try {
            $logger = new \Log\Buffered();
        }
        catch (\Exception $e) {
        }

        $this->assertTrue($logger instanceof \Log\Buffered);
    }

    public function testNewWithParameter()
    {
        $logger = null;
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
        $logger = null;
        $file = '/tmp/log-buffered-unreadable.log';
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
            $this->fail();
        }
        catch (\Exception $e) {
            $this->assertTrue(true);
        }
        unlink($file);

        $this->assertFalse($logger instanceof \Log\Buffered);
    }

    public function testNewWithInvalidMinBufferSize()
    {
        $logger = null;
        try {
            $logger = new \Log\Buffered(
                array(
                    'buffer_size' => \Log\Buffered::MIN_BUFFER_SIZE - 1
                )
            );
            $this->fail();
        }
        catch (\Exception $e) {
            $this->assertTrue(true);
        }

        $this->assertFalse($logger instanceof \Log\Buffered);
    }

    public function testNewWithInvalidMaxBufferSize()
    {
        $logger = null;
        try {
            $logger = new \Log\Buffered(
                array(
                    'buffer_size' => \Log\Buffered::MAX_BUFFER_SIZE + 1
                )
            );
            $this->fail();
        }
        catch (\Exception $e) {
            $this->assertTrue(true);
        }

        $this->assertFalse($logger instanceof \Log\Buffered);
    }
}
