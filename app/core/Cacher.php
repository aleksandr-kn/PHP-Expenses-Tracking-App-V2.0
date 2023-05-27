<?php

class Cacher {
    public function __construct() {
        $this->db = create_connection();
    }

    /**
     * Кэширует данные по тикеру, если они еще не были сохранены
     * TODO - Обновлять кэш если новый expires_at свежее существующего
     * @param $data - массив данных по тикеру
     * @return bool - результат кэширования
     */
    public function cacheInvestmentDataByDates($data) {
        // Готовим данные для записи в кэш
        $expires_at = new DateTime();
        $expires_at->modify('+6 month');
        $formatted_expires_at = $expires_at->format('Y-m-d H:i:s');
        $name_metaphone = metaphone($data['name']);
        $name_metaphone_abbreviation = abbreviate($data['name']);

        $stmt = $this->db->prepare(
            "INSERT INTO tickers_cache (id, symbol, region, currency, name, expires_at, name_metaphone, abr_name_metaphone) 
            VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?);");
        try {
            $stmt->execute([$data['symbol'], $data['region'], $data['currency'], $data['name'], $formatted_expires_at, $name_metaphone, $name_metaphone_abbreviation]);
        } catch (PDOException $e) {
            return false;
        }
        return ($stmt->rowCount() > 0);
    }

    public function getCachedSuggestedTicker($inputName) {
        $name_metaphone = strtoupper(metaphone($inputName));
        $abr_name_metaphone = strtoupper(metaphone(abbreviate($inputName)));

        $name_metaphone_sql = '%'.$name_metaphone.'%';
        $abr_name_metaphone_sql = '%'.$abr_name_metaphone.'%';
        $stmt = $this->db->prepare("SELECT id, symbol, region, currency, name FROM tickers_cache WHERE name_metaphone LIKE ? OR abr_name_metaphone LIKE ? LIMIT 15");
        $stmt->execute([$name_metaphone_sql, $abr_name_metaphone_sql]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Сортируем результат по уровню соответствия введенному название
        usort($result, function($a, $b) use ($name_metaphone) {
            $a_levenstein = levenshtein($name_metaphone, $a['name']);
            $b_levenstein = levenshtein($name_metaphone, $b['name']);
            return $a_levenstein - $b_levenstein;
        });
        return $result;
    }

    public function cacheTickerHistory($history, $ticker) {
        $stmt = $this->db->prepare(
            "INSERT INTO ticker_history_cache (id, ticker, datetime, open, high, low, close, volume) 
            VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?);");
        foreach($history as $key => $part) {
            try {
                $stmt->execute([
                    $ticker, $key, $part['open'], $part['high'], $part['low'], $part['close'], $part['volume'],
                ]);
            }
            catch (PDOException $e) {
                continue;
            }
        }

        return ($stmt->rowCount() > 0);
    }

    public function getCachedTickerHistory($ticker) {
        $stmt = $this->db->prepare("SELECT datetime, open, high, low, close, volume FROM ticker_history_cache WHERE ticker = ?");
        $stmt->execute([$ticker]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $adjustedResult = [];
        foreach ($result as $point) {
            $adjustedResult[$point['datetime']] = [
                'open' => $point['open'],
                'high' => $point['high'],
                'low' => $point['low'],
                'close' => $point['close'],
                'volume' => $point['volume'],
            ];
        }
        return $adjustedResult;
    }
}