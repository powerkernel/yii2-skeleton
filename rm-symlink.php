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
if(file_exists($cssLink)){
	unlink($cssLink);
}
if(file_exists($faviconLink)){
	unlink($faviconLink);
}
if(file_exists($imageLink)){
	unlink($imageLink);
}
if(file_exists($backendLink)){
	unlink($backendLink);
}
