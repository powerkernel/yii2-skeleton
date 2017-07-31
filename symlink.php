<?php
/**
 * Windows: run as administrator
 * php symlink.php
 */

$cssLink = __DIR__ . '/backend/web/css';
$faviconLink = __DIR__ . '/backend/web/favicon';
$imageLink = __DIR__ . '/backend/web/images';
$backendLink = __DIR__ . '/frontend/web/backend';

/* rm */
deleteDirectory($cssLink);
deleteDirectory($faviconLink);
deleteDirectory($imageLink);
deleteDirectory($backendLink);


if (!is_link($cssLink)) {
    symlink(__DIR__ . '/frontend/web/css', $cssLink);
}
if (!is_link($faviconLink)) {
    symlink(__DIR__ . '/frontend/web/favicon', $faviconLink);
}
if (!is_link($imageLink)) {
    symlink(__DIR__ . '/frontend/web/images', $imageLink);
}
if (!is_link($backendLink)) {
    symlink(__DIR__ . '/backend/web', $backendLink);
}


function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}