<?php

class Model_Investments extends Model {

    public function __construct()
    {
        $this->db = create_connection();
        $this->apiBaseUrl = 'https://www.alphavantage.co';
        $this->apiKey = 'AYV5YWAEN97JXDIY';
    }

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
        $stmt = $this->db->prepare("INSERT INTO investments (id, user_id, start_price, ticker, name) VALUES (DEFAULT, ?, ?, ?, ?);");
        $stmt->execute([$userId, $params['amount'], $params['ticker'], $params['name']]);
        return ($stmt->rowCount() > 0);
    }
}