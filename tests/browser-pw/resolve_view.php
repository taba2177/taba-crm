<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$finder = $app['view']->getFinder();
echo "PATHS:\n";
print_r($finder->getPaths());
echo "\nHINTS:\n";
$hints = $finder->getHints();
foreach ($hints as $k => $v) { echo "  $k => " . implode('; ', $v) . "\n"; }

echo "\nview('components.logo'):\n";
try {
    $v = view('components.logo');
    echo "  Resolved path: " . $v->getPath() . "\n";
} catch (\Throwable $e) {
    echo "  EXCEPTION (".get_class($e)."): " . $e->getMessage() . "\n";
}

echo "\nview('crm::components.logo'):\n";
try {
    $v = view('crm::components.logo');
    echo "  Resolved path: " . $v->getPath() . "\n";
} catch (\Throwable $e) {
    echo "  EXCEPTION (".get_class($e)."): " . $e->getMessage() . "\n";
}
