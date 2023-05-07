<?php 

class Model_Login extends Model {
    const API_KEY = 'zaCELgL0imfnc8mVLWwsAbtxYr4Rx-Af50DDqtlx';
    const API_BASE_URL = 'invest-public-api.tinkoff.ru:443';

    public function __construct() {
        $this->db = create_connection(); 
    }

    public function create_investment($investmentData) {

        $id = $_SESSION['id'];

        $spending_category_id = (int)$spending_category_id;
        if (!empty($spending_subcategory_id)) {
            $spending_subcategory_id = (int)$spending_subcategory_id;
        }

        $current_date_param = date("Y/m/d");

        if (is_numeric($investmentData->amount)) {
        $resource = $this->db->query("INSERT INTO investments (user_id, spending_category_id, spending_subcategory_id, spending_source_id,
            name, sum, spending_date)
            VALUES ('$id', '$spending_category_id', '$spending_subcategory_id', '$spending_source_id', 
            '$spending_name', '$spending_amount', '$current_date_param')");

        if ($resource->rowCount()) {
            $result['status'] = true;
            $result["inserted_id"] = $this->db->lastInsertId();
        } else {
            $result['status'] = false;
        }
        } else {
            $result['status'] = false;
        }

        return $result;
    }

    public function get_investments($start_date, $end_date) {
        $this->validateDates($startDate, $endDate);

        $responseBody = $this->getHistoricalDataResponseBody($symbol, self::INTERVAL_1_MONTH, $startDate, $endDate, self::FILTER_SPLITS);

        $historicData = $this->resultDecoder->transformSplitDataResult($responseBody);
        usort($historicData, function (SplitData $a, SplitData $b): int {
            // Data is not necessary in order, so ensure ascending order by date
            return $a->getDate() <=> $b->getDate();
        });

        return $historicData;
    }

    public function delete_investment($investmentId) {
        $userId = $_SESSION['id'];

        $stmt = $this->db->prepare("DELETE FROM investments WHERE id=? AND user_id=?;");
        $stmt->execute([$investmentId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function get_currencies(array $currencyPairs): array
    {
        $currencySymbols = array_map(function (array $currencies) {
            return implode($currencies).self::CURRENCY_SYMBOL_SUFFIX; // Currency pairs are suffixed with "=X"
        }, $currencyPairs);

        return $this->fetchQuotes($currencySymbols);
    }

    public function get_suggested_tickers($userString) {
        $db = create_connection();

        $res = $this->db->query("SELECT id, name FROM investments WHERE parent_category = '$parent_id';");
        $result = $res->fetchAll();
        if (!empty($result)) {
            $result['status'] = true;
        } else {
            $result['status'] = false;
        }
        
        return $result;
    }
}