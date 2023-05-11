<?php

class Controller_Investments extends Controller
{
    function __construct()
    {
        Session::start();
        if (!Session::is_logged_in()) {
            header('Location:/login/');
            exit();
        }

        $this->model = new Model_Investments();
        $this->view = new View();
    }

    public function action_index() {
        // get investment data from DB
        // TODO

        $this->view->generate('investments_view.php', 'template_view.php');
    }

    public function action_get_suggested_tickers() {
        $companyName = $_GET['company_name'];
        $result = $this->model->fetchItemsByCompanyName($companyName);

        if (!$result) {
            http_response_code(503);
            echo json_encode(['error' => 'Не удалось получить список токенов']);
            exit();
        }

        echo json_encode($result);
    }
}