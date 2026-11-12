<?php

$roots = array_slice($argv, 1) ?: ['app', 'bootstrap', 'config', 'database', 'routes', 'tests'];
$changed = 0;

foreach ($roots as $root) {
    if (! is_dir($root)) {
        continue;
    }

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));

    foreach ($files as $file) {
        if ($file->getExtension() !== 'php' || str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }

        $source = file_get_contents($file->getPathname());
        $output = '';

        foreach (token_get_all($source) as $token) {
            if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }
            $output .= is_array($token) ? $token[1] : $token;
        }

        $output = preg_replace('/[ \t]+$/m', '', $output);
        $output = preg_replace("/\n{3,}/", "\n\n", $output);
        $output = preg_replace('/([\{\[\(])\n\n/', "$1\n", $output);
        $output = preg_replace('/\n\n(\s*)\}/', "\n$1}", $output);

        if ($output !== $source) {
            file_put_contents($file->getPathname(), $output);
            $changed++;
        }
    }
}

echo "Comment lines removed from {$changed} PHP files.".PHP_EOL;
