<?php
function sanitize_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function load_scripts($view_name)
{

  if (is_dir(__DIR__ . "/../../public/assets/js/" . $view_name)) {
    $all_files = scandir(__DIR__ . "/../../public/assets/js/" . $view_name);
    $filtered_files = array_diff($all_files, array('.', '..'));

    if (empty($filtered_files)) {
      return false;
    } else {
      return $filtered_files;
    }
  } else {
    return false;
  }
}

function load_styles($view_name)
{
  if (is_dir(__DIR__ . "/../../public/assets/css/" . $view_name)) {
    $all_files = scandir(__DIR__ . "/../../public/assets/css/" . $view_name);
    $filtered_files = array_diff($all_files, array('.', '..'));

    if (empty($filtered_files)) {
      return false;
    } else {
      return $filtered_files;
    }
  } else {
    return false;
  }
}

function prettyPrint($data) {
  echo "<pre>";
  var_dump($data);
  echo "</pre>";
}

function dd($data) {
    prettyPrint($data);
    exit();
}

// Returns a random string
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string
{
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

function esc($args) {
  return htmlspecialchars($args, ENT_QUOTES, 'UTF-8');
}

function abbreviate($string) {
    $words = preg_split("/\s+/", $string);
    $acronym = "";

    foreach ($words as $w) {
        $acronym .= mb_substr($w, 0, 1);
    }
    return $acronym;
}
