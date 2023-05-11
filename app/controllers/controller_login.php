<?php

class Controller_Login extends Controller
{
	function __construct()
	{
		Session::start();

		$this->model = new Model_Login();
		$this->view = new View();
	}

	function action_index()
	{
        if (Session::is_logged_in()) {
			header('Location:/profile/');
			exit();
		}

		$this->view->generate('login_view.php', 'template_view.php', $data = null);
	}

	function action_process()
	{
        if (Session::is_logged_in()) {
			header('Location:/profile/');
			exit();
		}

		$loginResult = $this->model->process();

		if ($loginResult['status'] == true) {
			header('Location:/profile');
			exit();
		} else {
			$this->view->generate('login_view.php', 'template_view.php', $loginResult);
		}
	}

	function action_logout()
	{
		Session::destroy();
		header('Location:/login');
		exit();
	}
}

#TO DO - all the other controllers the same way