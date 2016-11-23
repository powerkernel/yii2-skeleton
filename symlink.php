<?php
/**
 * Windows: run as administrator
 */

$iconLink = __DIR__ . '/backend/web/icons';
$imageLink = __DIR__ . '/backend/web/images';
if (!is_link($iconLink)) {
    symlink(__DIR__ . '/frontend/web/icons', $iconLink);
}
if (!is_link($imageLink)) {
    symlink(__DIR__ . '/frontend/web/images', $imageLink);
}
