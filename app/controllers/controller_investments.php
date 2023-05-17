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
        $data['investments'] = $this->model->getInvestments();

        $this->view->generate('investments_view.php', 'template_view.php', $data);
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

    public function action_add_investment() {
        $required = array('name', 'ticker', 'date', 'amount');

        $error = false;
        foreach($required as $field) {
            if (empty($_POST[$field])) {
                $error = true;
            }
        }

        if ($error) {
            http_response_code(400 );
            echo json_encode(['error' => 'Неправильные параметры запроса']);
            exit();
        }

        $name = trim($_POST['name']);
        $ticker = trim($_POST['ticker']);
        $date = trim($_POST['date']);
        $amount = trim($_POST['amount']);

        $result = $this->model->storeInvestment([
            'date' => date('Y-m-d', (DateTime::createFromFormat('Y-m-d', sanitize_input($date))->getTimestamp())),
            'name' => sanitize_input($name),
            'ticker' => sanitize_input($ticker),
            'amount' => sanitize_input($amount),
        ]);

        echo json_encode(['result' => $result]);
    }

    public function action_get_investments() {
        echo json_encode($this->model->getInvestments());
    }

    public function action_delete_investment() {
        if (empty($_POST['id'])) {
            http_response_code(400 );
            echo json_encode(['error' => 'Неправильные параметры запроса']);
            exit();
        }
        $investmentId = sanitize_input($_POST['id']);

        $result = $this->model->deleteInvestment($investmentId);

        echo json_encode(['result' => $result]);
    }
}