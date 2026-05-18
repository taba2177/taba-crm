<?php
ini_set('display_errors', 'stderr');
error_reporting(-1);

register_shutdown_function(function () {
    $e = error_get_last();
    if ($e) {
        fwrite(STDERR, 'SHUTDOWN ERR: ' . json_encode($e) . PHP_EOL);
    }
});

set_exception_handler(function ($e) {
    fwrite(STDERR, 'EXC ' . get_class($e) . ': ' . $e->getMessage()
        . ' at ' . $e->getFile() . ':' . $e->getLine() . PHP_EOL
        . $e->getTraceAsString() . PHP_EOL);
});

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo 'OK' . PHP_EOL;
