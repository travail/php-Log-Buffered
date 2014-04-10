<?php

require_once __DIR__ . '/../vendor/autoload.php';

main();
exit;

function main()
{
    $log = new \Log\Buffered();
    for ($i = 0; $i < 3; $i++) {
        $log->debugf("%d times %s message", $i + 1, 'debug');
        $log->infof("%d times %s message", $i + 1, 'info');
        $log->warnf("%d times %s message", $i + 1, 'warn');
        $log->critf("%d times %s message", $i + 1, 'crit');
    }
    $log->flush();
}
