<?php
$dirs = [
    dirname(__DIR__) . '/.git/hooks',
    dirname(dirname(dirname(dirname(__DIR__)))) . '/.git/hooks',
];

$hooks = false;
foreach ($dirs as $dir) {
    echo $dir . PHP_EOL;
    if (is_dir($dir)) {
        $hooks = $dir;
        break;
    }
}

if ($hooks === false) {
    echo "Git hooks directory not found." . PHP_EOL;
    exit(1);
}

$hook = "{$hooks}/pre-commit";
$script = dirname(__DIR__) . '/bin/pre-commit.php';
$cmd = "php {$script}" . PHP_EOL;

if (! is_file($hook)) {
    echo "Creating pre-commit hook with the producer/githooks script." . PHP_EOL;
    file_put_contents($hook, $cmd);
    chmod($hook, 0755);
    exit(0);
}

$code = file_get_contents($hook);
if (strpos($code, $script) === false) {
    echo "Appending the producer/githooks script to existing pre-commit hook." . PHP_EOL;
    file_put_contents($hook, PHP_EOL . $cmd, FILE_APPEND);
    exit(0);
}

echo "The producer/githooks script appears to be in the pre-commit hook already." . PHP_EOL;
exit(0);
