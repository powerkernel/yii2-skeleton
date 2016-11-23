<?php
/**
 * Windows: run as administrator
 */

$faviconLink = __DIR__ . '/backend/web/favicon';
$imageLink = __DIR__ . '/backend/web/images';
$backendLink = __DIR__ . '/frontend/web/backend';
if (!is_link($faviconLink)) {
    symlink(__DIR__ . '/frontend/web/favicon', $faviconLink);
}
if (!is_link($imageLink)) {
    symlink(__DIR__ . '/frontend/web/images', $imageLink);
}
if (!is_link($backendLink)) {
    symlink(__DIR__ . '/backend/web', $backendLink);
}
