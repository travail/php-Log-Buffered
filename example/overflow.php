<?php

use \Log\Buffered;

require_once __DIR__ . '/../vendor/autoload.php';

main();
exit;

function main()
{
    $log = new Buffered(
        array(
            'buffer_size' => Buffered::MIN_BUFFER_SIZE,
        )
    );
    for ($i = 0; $i < 10; $i++) {
        $log->infof(
            'If the size of buffered messages reaches %d, ' .
            'the logger flushes the messages automatically ' .
            'with the caution.', $log->getBufferSize());
    }
    $log->flush();
}
