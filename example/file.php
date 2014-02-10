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
            'file' => '/tmp/log-buffered.log'
        )
    );
    for ($i = 0; $i < 3; $i++) {
        $log->infof('This message is written into %s', $log->getFile());
        $log->debugf('%d times %s message', $i + 1, 'debug');
        $log->infof('%d times %s message', $i + 1, 'info');
        $log->warnf('%d times %s message', $i + 1, 'warn');
        $log->critf('%d times %s message', $i + 1, 'crit');
    }
    $log->flush();
}
