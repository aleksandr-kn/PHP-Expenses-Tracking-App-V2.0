<?php

class Model_Profile
{

  function __construct()
  {
    $this->resultData = array(
      "status" => false,
      "user_data" => array(),
      "errors" => array(),
    );

    $this->db = create_connection();
  }

  public function get_user_info()
  {
    $id_param = $_SESSION["id"];

    $stmt = $this->db->prepare("SELECT username, email, info FROM users WHERE id=?;");
    $stmt->execute([$id_param]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    $user_info = $result;

    return $user_info;
  }

  private function get_categories($quantity = 'default')
  {
    $id_param = $_SESSION["id"];

    $stmt = $this->db->prepare("SELECT id, name FROM spending_category WHERE user_id = ?;");
    $stmt->execute([$id_param]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


    return $categories;
  }

  public function get_sources($quantity = 'default')
  {
    $id_param = $_SESSION["id"];

    $stmt = $this->db->prepare("SELECT id, name, description FROM spending_source WHERE user_id = ?;");
    $stmt->execute([$id_param]);
    $sources = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $sources;
  }

  public function delete_source($source_id)
  {
    $db = create_connection();
    $source_id = (int)$source_id;

    if (is_int($source_id) && $this->db->query("DELETE FROM spending_source WHERE id = '{$source_id}';")->rowCount()) {
      $result['status'] = true;
    } else {
      $result['status'] = false;
    }
    return $result;
  }

  public function get_spendings($quantity = 25, $pageToLoad = 1)
  {
    $db = create_connection();

    $id_param = $_SESSION["id"];

    // // Замер времени запроса
    // $db->query('set profiling=1'); //optional if profiling is already enabled
    // $sql = "SELECT limitedSpendings.id, spending_category.name as category_name, spending_subcategory.name as subcategory_name,
    //   spending_source.name as source_name,
    //   limitedSpendings.spending_date, limitedSpendings.name, limitedSpendings.sum, limitedSpendings.user_id
    //   FROM (SELECT * FROM spendings WHERE spendings.user_id = '$id_param' ORDER BY spendings.id DESC LIMIT 25) as limitedSpendings
    //   LEFT JOIN spending_category ON spending_category.id = limitedSpendings.spending_category_id
    //   LEFT JOIN spending_subcategory ON spending_subcategory.id = limitedSpendings.spending_subcategory_id
    //   LEFT JOIN spending_source ON spending_source.id = limitedSpendings.spending_source_id;";
    // $db->query($sql);
    // $res = $db->query('show profiles');
    // $records = $res->fetchAll(PDO::FETCH_ASSOC);
    // $duration = $records[0]['Duration'];  // get the first record [0] and the Duration column ['Duration'] from the first record
    // echo "SQL: " . $sql;
    // echo "<br>";
    // echo "Поиск занял: " . $duration . " секунд";

    $offset =  ($quantity * $pageToLoad) - $quantity;

    $res = $this->db->query(
      "SELECT limitedSpendings.id, spending_category.name as category_name, spending_subcategory.name as subcategory_name, 
      spending_source.name as source_name,
      limitedSpendings.spending_date, limitedSpendings.name, limitedSpendings.sum, limitedSpendings.user_id
      FROM (SELECT * FROM spendings WHERE spendings.user_id = '$id_param' ORDER BY spendings.id DESC LIMIT $offset, $quantity) as limitedSpendings
      LEFT JOIN spending_category ON spending_category.id = limitedSpendings.spending_category_id
      LEFT JOIN spending_subcategory ON spending_subcategory.id = limitedSpendings.spending_subcategory_id
      LEFT JOIN spending_source ON spending_source.id = limitedSpendings.spending_source_id;"
    );

    // Старый запрос
    // $res = $this->db->query(
    //   "SELECT spendings.id, spending_category.name as category_name, spending_subcategory.name as subcategory_name,
    //   spending_source.name as source_name,
    //   spendings.spending_date, spendings.name, spendings.sum
    //   FROM spendings
    //   LEFT JOIN spending_category ON spending_category.id = spendings.spending_category_id
    //   LEFT JOIN spending_subcategory ON spending_subcategory.id = spendings.spending_subcategory_id
    //   LEFT JOIN spending_source ON spending_source.id = spendings.spending_source_id
    //   WHERE spendings.user_id = '$id_param'
    //   ORDER BY spendings.id DESC
    //   LIMIT 25;"
    // );

    // $res = $this->db->query(
    //   "SELECT *
    //   FROM GetSpendings
    //   WHERE user_id = '$id_param'
    //   ORDER BY id DESC
    //   LIMIT 25;
    //   "
    // );

    $spendings = $res->fetchAll(PDO::FETCH_ASSOC);
    return $spendings;
  }

  public function get_filtered_spendings($first_date, $last_date,
  $spending_category_id = null,
  $spending_subcategory_id = null,
  $spending_source_id = null,
  $min_sum = 0,
  $max_sum = 9999999 )
  {
    $db = create_connection();
    $id_param = $_SESSION['id'];

    //validate dates
    //*****
    //to do

    if (empty($first_date)) {
      $first_date = $this->db->query("SELECT registration_date FROM users WHERE id = '{$id_param}';")->fetch();
    }

    if (empty($spending_category_id)) {
      $spending_category_id = " LIKE '%'";
    } else {
      $spending_category_id = "=" . $spending_category_id;
    }

    if (empty($spending_subcategory_id)) {
      $spending_subcategory_id = " LIKE '%'";
    } else {
      $spending_subcategory_id = "=" . $spending_subcategory_id;
    }

    if (empty($spending_source_id)) {
      $spending_source_id = " LIKE '%'";
    } else {
      $spending_source_id = "=" . $spending_source_id;
    }
    //fixing date problem
    // pg_query('SET datestyle = dmy;');

    $first_date = date('Y-m-d H:i:s',strtotime($first_date));
    $last_date = date('Y-m-d H:i:s',strtotime($last_date));

    // Old query
    // $resource = $this->db->query(
    // "SELECT spendings.id, spending_category.name as category_name, spending_subcategory.name as subcategory_name,
    // spending_source.name as source_name,
    // spendings.spending_date, spendings.name, spendings.sum
    // FROM spendings
    // LEFT JOIN spending_category ON spending_category.id = spendings.spending_category_id
    // LEFT JOIN spending_subcategory ON spending_subcategory.id = spendings.spending_subcategory_id
    // LEFT JOIN spending_source ON spending_source.id = spendings.spending_source_id
    // WHERE spendings.user_id = '$id_param' AND spendings.spending_date >= '$first_date' AND spendings.spending_date <= '$last_date'
    // AND spendings.sum >= '$min_sum' AND spendings.sum <= '$max_sum'
    // AND spendings.spending_category_id{$spending_category_id}
    // AND spendings.spending_subcategory_id{$spending_subcategory_id}
    // AND spendings.spending_source_id{$spending_source_id}
    // ORDER BY spendings.id DESC;");

    $resource = $this->db->query(
    "SELECT filteredSpendings.id, spending_category.name as category_name, spending_subcategory.name as subcategory_name, 
    spending_source.name as source_name,
    filteredSpendings.spending_date, filteredSpendings.name, filteredSpendings.sum, filteredSpendings.user_id
    FROM (SELECT * from spendings WHERE spendings.user_id = '$id_param' AND spendings.spending_date >= '$first_date' AND spendings.spending_date <= '$last_date'
    AND spendings.sum >= '$min_sum' AND spendings.sum <= '$max_sum'
    AND spendings.spending_category_id{$spending_category_id}
    AND spendings.spending_subcategory_id{$spending_subcategory_id}
    AND spendings.spending_source_id{$spending_source_id}
    ORDER BY spendings.id DESC) as filteredSpendings
    INNER JOIN spending_category ON spending_category.id = filteredSpendings.spending_category_id
    INNER JOIN spending_subcategory ON spending_subcategory.id = filteredSpendings.spending_subcategory_id
    INNER JOIN spending_source ON spending_source.id = filteredSpendings.spending_source_id;");

    $filteredSpendings = $resource->fetchAll(PDO::FETCH_ASSOC);

    if ($filteredSpendings) {
      $filteredSpendings['status'] = true;
    } else {
      $filteredSpendings['status'] = false;
    }
    return $filteredSpendings;
  }

  public function get_this_week_spendings()
  {
    $start_week = date('d-m-Y', strtotime('monday this week'));
    $end_week = date('d-m-Y', strtotime('sunday this week'));

    $filteredSpendings = $this->get_filtered_spendings($start_week, $end_week);

    return $filteredSpendings;
  }

  public function add_spending($spending_name, $spending_amount, $spending_category_id, $spending_subcategory_id = null, $spending_source_id = null)
  {
    $db = create_connection();
    $id = $_SESSION['id'];
    // $spending_name = pg_escape_string($spending_name);
    $spending_category_id = (int)$spending_category_id;
    if (!empty($spending_subcategory_id)) {
      $spending_subcategory_id = (int)$spending_subcategory_id;
    }

    // $spending_source_id = pg_escape_string($spending_source_id);

    // $spending_amount = pg_escape_string($spending_amount);
    $current_date_param = date("Y/m/d");

    if (is_numeric($spending_amount)) {
      // ???
      // pg_query('SET datestyle = dmy;');
      $resource = $this->db->query("INSERT INTO spendings (user_id, spending_category_id, spending_subcategory_id, spending_source_id,
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

  public function delete_spending($spending_id)
  {
    $db = create_connection();

    $spending_id = (int)$spending_id;

    if (is_int($spending_id) && $this->db->query("DELETE FROM spendings WHERE id = '{$spending_id}';")->rowCount()) {
      $result['status'] = true;
    } else {
      $result['status'] = false;
    }
    // ????
    // $this->resultData = $this->get_initial_data();
    return $result;
  }

  public function add_category($new_category_name)
  {
    $id = $_SESSION['id'];

    // TODO
    // $new_category_name = pg_escape_string($new_category_name);

    $resource = $this->db->query("INSERT INTO spending_category(user_id, name) VALUES ('$id', '$new_category_name')");
    if ($resource->rowCount()) {
      $result['status'] = true;
      $result["inserted_id"] = $this->db->lastInsertId();
    } else {
      $result['status'] = false;
    }
    return $result;
  }

  public function delete_category($category_id)
  {
    $id = $_SESSION['id'];
    $category_id = (int)$category_id;

    if (is_int($category_id) &&  $this->db->query("DELETE FROM spending_category WHERE user_id = '$id' AND id = '{$category_id}';")->rowCount()) {
      $result['status'] = true;
    } else {
      $result['status'] = false;
    }
    return $result;
  }

  public function add_subcategory($new_subcategory_name, $new_subcategory_parent_id)
  {
    // $new_subcategory_name = pg_escape_string($new_subcategory_name);
    // TOOD prepare statements
    $new_subcategory_parent_id = (int)$new_subcategory_parent_id;

    $resource = $this->db->query("INSERT INTO spending_subcategory(parent_category, name) VALUES ('$new_subcategory_parent_id', '$new_subcategory_name')");
    if ($resource->rowCount()) {
      $result['status'] = true;
      $result["inserted_id"] = $this->db->lastInsertId();
    } else {
      $result['status'] = false;
    }
    return $result;
  }

  public function add_source($new_source_name, $new_source_description)
  {
    $id_param = $_SESSION["id"];

    // $new_source_name = pg_escape_string($new_source_name);
    if (!empty($new_source_description)) {
      // $new_source_description = pg_escape_string($new_source_description);
    }

    $resource = $this->db->query("INSERT INTO spending_source(user_id, name, description) VALUES ('$id_param', '$new_source_name', '$new_source_description');");
    if ($this->db->rowCount()) {
      $result['status'] = true;
    } else {
      $result['status'] = false;
    }
    return $result;
  }

  public function delete_subcategory($subcategory_id)
  {
    $subcategory_id = (int)$subcategory_id;
    // Проверяем что это не последняя подкатегория
    if ($this->db->query("SELECT COUNT(*) FROM spending_subcategory WHERE parent_category = (SELECT parent_category FROM spending_subcategory WHERE id = '$subcategory_id');")->fetchColumn() > 1 &&
      is_int($subcategory_id) &&
      $this->db->query("DELETE FROM spending_subcategory WHERE id = '{$subcategory_id}';")->rowCount()) {
      $result['status'] = true;
    } else {
      $result['status'] = false;
    }
    return $result;
  }

  public function get_subcategories($parent_id) {
    $db = create_connection();
    $res = $this->db->query("SELECT id, name FROM spending_subcategory WHERE parent_category = '$parent_id';");
    $result = $res->fetchAll();
    if (!empty($result)) {
      $result['status'] = true;
    } else {
      $result['status'] = false;
    }

    return $result;
  }

  public function update_profile($new_username, $new_email, $new_info)
  {

    $result = array(
      "status" => "false",
      "errors" => array(),
    );

    $resource = $this->db->query("SELECT username, email, info FROM users WHERE id = {$_SESSION['id']};");
    $current_fields = $resource->fetchAll(PDO::FETCH_ASSOC);

    $new_username = sanitize_input($new_username);
    $new_email = sanitize_input($new_email);
    $new_info = sanitize_input($new_info);

    $email_err = "";
    $username_err = "";

    if (empty(sanitize_input($new_username))) {
      $username_err = "Введите имя пользователя";
    }
    if (($this->db->query("SELECT EXISTS(SELECT username FROM users WHERE username = '$new_username');")->fetch()[0]) && ($new_username != $current_fields[0]['username'])) {
      $username_err = "Это имя пользователя уже занято";
    }
    if (empty(sanitize_input($new_email))) {
      $email_err = "Введите Email";
    }
    if (($this->db->query("SELECT EXISTS(SELECT email FROM users WHERE email = '$new_email');")->fetch()[0]) && ($new_email != $current_fields[0]['email'])) {
      $email_err = "Этот уже Email использован";
    }

    if (empty($username_err) && empty($email_err)) {
      $this->db->query("UPDATE users SET username='$new_username', email = '$new_email', info = '$new_info' WHERE id = {$_SESSION['id']};");
      $result['status'] = true;
    } else {
      $result["errors"]["email_err"] = $email_err;
      $result["errors"]["username_err"] = $username_err;
    }

    return $result;
  }

  private function get_spendings_sum($spendings_list)
  {
    return array_sum(array_column($spendings_list, 'sum'));
  }

  private function get_spendings_quantity($spendings_list) {
    array_pop($spendings_list);
    return count($spendings_list);
  }
  //rounded average of spendings sums for given period
  private static function get_spendings_average(array $array, bool $includeEmpties = true): float
  {
      $array = array_filter($array, fn($v) => (
          $includeEmpties ? is_numeric($v) : is_numeric($v) && ($v > 0)
      ));

      if (count($array) != 0) {
        return round((array_sum($array) / count($array)), 0, PHP_ROUND_HALF_UP);
      } else {
        return 0;
      }

  }


  private function pct_change($old, $new, int $precision = 2): float
  {
    if ($old == 0) {
      $old++;
      $new++;
    }

    $change = (($new - $old) / $old) * 100;

    return round($change, $precision);
  }

  private function spendings_change_status($spendings_difference)
  {
    if ($spendings_difference > 0) {
      $change_status = "Увеличились";
    } else if ($spendings_difference < 0) {
      $change_status = "Уменьшились";
    } else {
      $change_status = "Не изменились";
    }
    return $change_status;
  }

  private function get_min_max_sum()
  {
    $db = create_connection();
    $id_param = $_SESSION["id"];

    $res = $this->db->query("SELECT MAX(sum), MIN(sum)
    FROM spendings WHERE user_id = {$id_param};");
    $min_max = $res->fetchAll(PDO::FETCH_ASSOC);
    return $min_max;
  }

  public function get_initial_data()
  {
    $this->resultData["user_data"]["spendings"] = $this->get_spendings();
    $this->resultData["user_data"]["categories"] = $this->get_categories();
    $this->resultData["user_data"]["sources"] = $this->get_sources();
    $this->resultData["user_data"]["user_info"] = $this->get_user_info();

    $this->resultData["user_data"]["min_max"] = $this->get_min_max_sum();

    //total spendigns of this week
    $this_week_spendings = $this->get_this_week_spendings();
    $this_week_spendings_sum = $this->get_spendings_sum($this_week_spendings);
    $this->resultData["user_data"]["this_week_spendings_sum"] = $this_week_spendings_sum;

    //last week spendings
    $last_week_start = date('d-m-Y', strtotime('monday last week'));
    $last_week_end = date('d-m-Y', strtotime('sunday last week'));
    $last_week_spendings = $this->get_filtered_spendings($last_week_start, $last_week_end);
    $last_week_spendings_sum =  $this->get_spendings_sum($last_week_spendings);
    $this->resultData["user_data"]["last_week_spendings_sum"] = $last_week_spendings_sum;

    //average
    $this_week_average_sum = $this->get_spendings_average(array_column($this_week_spendings, 'sum'));
    $last_week_average_sum = $this->get_spendings_average(array_column($last_week_spendings, 'sum'));
    $this->resultData["user_data"]["this_week_average_sum"] = $this_week_average_sum;

    $percentage_difference_average_sum = $this->pct_change($last_week_average_sum, $this_week_average_sum);
    $this->resultData["user_data"]["percentage_difference_average_sum"] = abs($percentage_difference_average_sum);
    $this->resultData["user_data"]["percentage_difference_average_sum_status"] = $this->spendings_change_status($percentage_difference_average_sum);

    //quantity of spendings this week
    $this_week_spendings_quantity = $this->get_spendings_quantity($this_week_spendings);
    $last_week_spendings_quantity = $this->get_spendings_quantity($last_week_spendings);

    //quantity info
    $percentage_difference_quantity = $this->pct_change($last_week_spendings_quantity, $this_week_spendings_quantity);
    $this->resultData["user_data"]["percentage_difference_quantity"] = abs($percentage_difference_quantity);
    $this->resultData["user_data"]["this_week_spendings_quantity"] = $this_week_spendings_quantity;
    $this->resultData["user_data"]["percentage_difference_quantity_status"] = $this->spendings_change_status($percentage_difference_quantity);

    //amount info
    $percentage_difference_amount = $this->pct_change($last_week_spendings_sum, $this_week_spendings_sum);
    $this->resultData["user_data"]["percentage_difference_amount"] = abs($percentage_difference_amount);
    $this->resultData["user_data"]["percentage_difference_amount_status"] = $this->spendings_change_status($percentage_difference_amount);

    $this->resultData["user_data"]["current_page"] = 1;

    return $this->resultData;
  }
}
