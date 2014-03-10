<?php

/**
 * @group functional
 */
class FunctionalLogTest extends PHPUnit_Framework_TestCase
{
    /**
     * Messages will be not buffered unless enable debug mode.
     */
    public function testDebugf()
    {
        $logger = new \Log\Buffered();
        $this->assertTrue($logger->getBufferedSize() === 0);
        $logger->debugf('debug');
        $this->assertTrue($logger->getBufferedSize() === 0);
        $logger->flush();
        $this->assertTrue($logger->getBufferedSize() === 0);
    }

    public function testInfof()
    {
        $logger = new \Log\Buffered();
        $this->assertTrue($logger->getBufferedSize() === 0);
        $logger->infof('info');
        $this->assertTrue($logger->getBufferedSize() !== 0);
        $logger->flush();
        $this->assertTrue($logger->getBufferedSize() === 0);
    }

    public function testWarnf()
    {
        $logger = new \Log\Buffered();
        $this->assertTrue($logger->getBufferedSize() === 0);
        $logger->warnf('warn');
        $this->assertTrue($logger->getBufferedSize() !== 0);
        $logger->flush();
        $this->assertTrue($logger->getBufferedSize() === 0);
    }

    public function testCritf()
    {
        $logger = new \Log\Buffered();
        $this->assertTrue($logger->getBufferedSize() === 0);
        $logger->critf('critical');
        $this->assertTrue($logger->getBufferedSize() !== 0);
        $logger->flush();
        $this->assertTrue($logger->getBufferedSize() === 0);
    }
}
