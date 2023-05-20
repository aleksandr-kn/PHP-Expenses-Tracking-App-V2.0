<?php

class Model_Investments extends Model {

    public function __construct()
    {
        $this->db = create_connection();
        $this->apiBaseUrl = 'https://www.alphavantage.co';
        $this->apiKey = 'AYV5YWAEN97JXDIY';
        $this->userId = $_SESSION['id'];
    }

    // Использует AlphaAdvantage API
    public function fetchItemsByCompanyName($companyName) {
        $json = file_get_contents("{$this->apiBaseUrl}/query?function=SYMBOL_SEARCH&keywords={$companyName}&apikey={$this->apiKey}");

        if (!$json) {
            return false;
        }

        $result = json_decode($json, true)['bestMatches'];

        // Убираем индексы из ключей
        $cleanResult = array_map(function($tickerItem) {
            $keys = array_keys( $tickerItem );
            foreach ($keys as $key) {
                $newKey = preg_replace("/[^a-zA-Z]+/", "", $key);
                $tickerItem[$newKey] = $tickerItem[$key];
                unset($tickerItem[$key]);
            }
            return $tickerItem;
        }, $result);

        return $cleanResult;
    }

    public function storeInvestment($params) {
        $userId = $_SESSION['id'];
        $stmt = $this->db->prepare("INSERT INTO investments (id, user_id, start_price, ticker, name, date, quantity) VALUES (DEFAULT, ?, ?, ?, ?, ?, ?);");
        $stmt->execute([$userId, $params['amount'], $params['ticker'], $params['name'], $params['date'], $params['quantity']]);
        return ($stmt->rowCount() > 0);
    }

    public function getInvestments() {
        $userId = $_SESSION['id'];

        $stmt = $this->db->query("SELECT * FROM investments WHERE user_id = {$this->userId}");

        $investments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $investments;
    }

    public function deleteInvestment($investmentId) {
        $userId = $_SESSION['id'];
        return $this->db->query("DELETE FROM investments WHERE id = '{$investmentId}' AND user_id = '{$userId}';")->rowCount();
    }

    public function getInvestmentById($id) {
        $stmt = $this->db->query("SELECT * FROM investments WHERE id = {$id} AND user_id = {$this->userId}");
        $investments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($investments) > 0 ? $investments[0] : null;
    }

    public function getInvestmentsByTicker($ticker) {
        $stmt = $this->db->query("SELECT * FROM investments WHERE ticker = '$ticker' AND user_id = '$this->userId'");
        $investments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($investments) > 0 ? $investments : null;
    }

    public function getInvestmentHistoricalData($ticker, $inteval = '60min') {
        $json = file_get_contents("{$this->apiBaseUrl}/query?function=TIME_SERIES_INTRADAY&symbol={$ticker}&interval=60min&apikey={$this->apiKey}");
        if (!$json) {
            return false;
        }

        return json_decode($json, true);
    }

    public function getTickerCurrentPrice($ticker) {
        $recentData = $this->getInvestmentHistoricalData($ticker);
        //TODO Пока забито хардкодом по индексам, т.к. api возвращает ключи в неудобном формате
        $series = array_values($recentData)[1];
        $lastRecord = reset($series);
        $lastRecordClosedPrice = array_values($lastRecord)[3];

        return $lastRecordClosedPrice;
    }

    // Получает сумму всех инвестиций по тикеру у текущего пользователя
    public function getTickerTotalData($ticker) {
        $stmt = $this->db->query("SELECT start_price, quantity FROM investments WHERE ticker = '$ticker' AND user_id = '$this->userId'");

        $investments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalAmount = 0;
        $totalQuantity = 0;
        foreach ($investments as $investment) {
            $totalQuantity += $investment['quantity'];
            $totalAmount += $investment['start_price'] * $investment['quantity'];
        }

        return ['quantity' => $totalQuantity, 'amount' => $totalAmount];
    }
}