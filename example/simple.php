<?php

// TODO: Delete later
require_once '/home/travail/git/php-Term-ANSIColor/lib/Term/ANSIColor.php';
require_once '/home/travail/git/php-Log-Minimal/lib/Log/Minimal.php';

require_once dirname(dirname(__FILE__)) . '/lib/Log/Buffered.php';

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
