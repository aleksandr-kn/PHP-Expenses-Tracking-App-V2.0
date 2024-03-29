<?php

class Route
{

	static function start()
	{
		$controller_name = 'login';
		$action_name = 'index';

  	    $routes = explode('/', $_SERVER['REQUEST_URI']);

		if (!empty($routes[1])) {
			$controller_name = $routes[1];
		}

		if (!empty($routes[2])) {
			$action_name = explode('?', $routes[2])[0];
		}

		$model_name = 'Model_' . $controller_name;
		$controller_name = 'Controller_' . $controller_name;
		$action_name = 'action_' . $action_name;

		$model_file = strtolower($model_name) . '.php';
		$model_path = __DIR__ . "/../models/" . $model_file;
		if (file_exists($model_path)) {
			include __DIR__ . "/../models/" . $model_file;
		}

		$controller_file = strtolower($controller_name) . '.php';
		$controller_path = __DIR__ . "/../controllers/" . $controller_file;
		if (file_exists($controller_path)) {
			include __DIR__ . "/../controllers/" . $controller_file;
		} else {
			Route::ErrorPage404();
		}

		$controller = new $controller_name();
		$action = $action_name;

        // Доставем параметры если они есть
        $params = [];
        if (count($routes) > 3) {
            $params = array_slice($routes, 3);
            $controller->setRouteParams($params);
        }

		if (method_exists($controller, $action)) {
			$controller->$action();
		} else {
			Route::ErrorPage404();
		}
	}

	static function ErrorPage404()
	{
		$host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:' . $host . '404');
	}
}
