<?php
// disable opcache since it doesn't work with .phar archives. StS 2017-06-27 15:35
ini_set('opcache.enable', '0');

if (file_exists(__DIR__. '/zpserver.phar')) require_once(__DIR__. '/zpserver.phar');
else require_once(__DIR__. '/afx.core.inc.php');

?>