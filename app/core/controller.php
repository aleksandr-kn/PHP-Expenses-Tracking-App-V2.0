<?php

class Controller
{

	public $model;
	public $view;

    public $params;
	function __construct()
	{
		$this->view = new View();
		$this->controller_name = get_class($this);
	}

	// действие (action), вызываемое по умолчанию
	public function action_index()
	{
		// todo
	}

    public function setRouteParams($params) {
        $this->params = $params;
    }

    public function exitWithResponseCode($code = 400, $message = 'Неправильные параметры запроса') {
        http_response_code($code);
        echo json_encode(['error' => $message]);
        exit();
    }
}
