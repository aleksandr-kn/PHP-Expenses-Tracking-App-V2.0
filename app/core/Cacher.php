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

        $stmt = $this->db->prepare(
            "INSERT INTO tickers_cache (id, symbol, region, currency, name, expires_at) 
            VALUES (DEFAULT, ?, ?, ?, ?, ?);");
        try {
            $stmt->execute([$data['symbol'], $data['region'], $data['currency'], $data['name'], $formatted_expires_at]);
        } catch (PDOException $e) {
            return false;
        }
        return ($stmt->rowCount() > 0);
    }
}