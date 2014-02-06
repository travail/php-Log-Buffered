<?php

require_once dirname(dirname(__FILE__)) . '/vendor/Term-ANSIColor/lib/Term/ANSIColor.php';
require_once dirname(dirname(__FILE__)) . '/vendor/Log-Minimal/lib/Log/Minimal.php';
require_once dirname(dirname(__FILE__)) . '/lib/Log/Buffered.php';

main();
exit;

function main()
{
    $log = new \Log\Buffered(
        array(
            'buffer_size' => \Log\Buffered::MIN_BUFFER_SIZE,
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
