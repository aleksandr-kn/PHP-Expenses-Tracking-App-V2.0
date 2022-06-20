<?php

declare(strict_types=1);

class Testing
{
  public static function prettyPrint(mixed $data) : void {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
  }

  // Dump and exit
  public static function dd(mixed $data) : never {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    exit();
  }

  // Returns a random string
  public static function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
  ): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
  }

  public static function measureQueryTime(string $sql): never {
    $db = create_connection();

    $id_param = $_SESSION["id"];

    // Замер времени запроса
    $db->query('set profiling=1');
    $sql = $sql;
    $db->query($sql);
    $res = $db->query('show profiles');
    $records = $res->fetchAll(PDO::FETCH_ASSOC);
    $duration = $records[0]['Duration']; 
    echo "SQL: " . $sql;
    echo "<br>";
    echo "<strong>Запрос занял: " . $duration . " секунд </strong>";

    exit();
  }
}


  // Script time 
  // $start = microtime(true);
  // $time_elapsed_secs = microtime(true) - $start;