<?php
// подключаем файлы ядра
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';

require_once 'core/config.php';

require_once 'core/temporary_functions.php';

require_once 'utility/Session.php';

require_once 'utility/Testing.php';

define('SITE_NAME', $_SERVER['SERVER_NAME']);
define('ROOT', '/var/www/');  

require_once 'core/route.php';

require_once __DIR__ . '/../vendor/autoload.php';

Route::start(); // запускаем маршрутизатор

