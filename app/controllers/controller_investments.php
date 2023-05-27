<?php

require_once ROOT .  '/app/core/Cacher.php';
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
        $this->cacher = new Cacher();
    }

    public function action_index() {
        // get investment data from DB
        // TODO
        $data['investments'] = $this->model->getInvestments();

        $this->view->generate('investments_view.php', 'template_view.php', $data);
    }

    public function action_get_suggested_tickers() {
        $companyName = $_GET['company_name'];

        // Получаем тикеры либо из кэша, либо с API
        $result = $this->cacher->getCachedSuggestedTicker($companyName);
        if (empty($result)) {
            $result = $this->model->fetchItemsByCompanyName($companyName);
        }

        if (!$result) {
            http_response_code(503);
            echo json_encode(['error' => 'Не удалось получить список токенов']);
            exit();
        }

        echo json_encode($result);
    }

    public function action_add_investment() {
        $required = array('name', 'ticker', 'date', 'amount', 'quantity');

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
        $quantity = trim($_POST['quantity']);

        $result = $this->model->storeInvestment([
            'date' => date('Y-m-d', (DateTime::createFromFormat('Y-m-d', sanitize_input($date))->getTimestamp())),
            'name' => sanitize_input($name),
            'ticker' => sanitize_input($ticker),
            'amount' => sanitize_input($amount),
            'quantity' => sanitize_input($quantity),
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

    public function action_investment() {
        $data = [];
        if (!isset($this->params[0]) || !(is_numeric($this->params[0]))) {
            $this->exitWithResponseCode(400);
        }

        $investmentId = (int)$this->params[0];
        $investment = $this->model->getInvestmentById($investmentId);

        if (!$investment) {
            $this->exitWithResponseCode(404, 'Не удалось найти инвестицию');
        }
        // Инвестиция по которой пришли на данную страницу
        $data['investment'] = $investment;
        // Список всех инвестиций по данному тикеру
        $data['investments'] = $this->model->getInvestmentsByTicker($investment['ticker']);

        // Доходность по тикеру
        $totalDataWhenBought = $this->model->getTickerTotalData($investment['ticker']);
        $currentPrice = $this->model->getTickerCurrentPrice($investment['ticker']);
        $data['income'] = round((($currentPrice * $totalDataWhenBought['quantity']) - $totalDataWhenBought['amount']), 2);

        // Свечи за последнее время по тикеру
        $data['history'] = $this->cacher->getCachedTickerHistory($investment['ticker']);
        if (empty($data['history'])) {
            $data['history'] = $this->model->getInvestmentHistoricalData($investment['ticker']);
        }

        $this->view->generate('investment_view.php', 'template_view.php', $data);
    }

    public function action_test() {
    }
}