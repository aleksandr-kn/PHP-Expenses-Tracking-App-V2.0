<?php

require_once __DIR__.'/../core/PDFHandler.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

class Controller_Test extends Controller
{
  function action_index()
  {
    // Session::start();

    $dbTest = create_connection();
    
    
    // prettyPrint($rndUsername);


    for ($i= 0; $i < 2500; $i++) {
      $rndString = random_str(16);
      echo $rndString;
      echo "<br>";
      $dbTest->query("INSERT INTO spendings (user_id, spending_category_id, spending_subcategory_id, spending_source_id,
      name, sum, spending_date)
      VALUES (19, 25, 1, 3, '{$rndString}', 250, '2010-06-05');");
    }


    // $this->view->generate('test_view.php', 'template_view.php');
  }

  function action_testUsernames()
  {
    // Session::start();

    $dbTest = create_connection();
    
    
    // prettyPrint($rndUsername);


    // for ($i= 0; $i < 250000; $i++) {
      // $rndString = random_str(16);
      // echo $rndString;
      // echo "<br>";
      $rndString = "testUsername9";
      echo $rndString;
      $dbTest->query("INSERT INTO spendings (user_id, spending_category_id, spending_subcategory_id, spending_source_id,
      name, sum, spending_date)
      VALUES (19, 25, 1, 3, '{$rndString}', 250, '2010-06-05');");
    // }


    // $this->view->generate('test_view.php', 'template_view.php');
  }
  function action_queryTime()
  {
    // Session::start();

    $dbTest = create_connection();
    

    //Explain
    $explainSQL = "EXPLAIN SELECT * FROM spendings WHERE name='testUsername9'";
    $explainStmt = $dbTest->query($explainSQL);
    prettyPrint($explainStmt->fetch());
    //Explain

    $dbTest->query('set profiling=1'); //optional if profiling is already enabled
    $sql = "SELECT * FROM spendings WHERE name='testUsername9'";
    $dbTest->query($sql);
    $res = $dbTest->query('show profiles');
    $records = $res->fetchAll(PDO::FETCH_ASSOC);
    $duration = $records[0]['Duration'];  // get the first record [0] and the Duration column ['Duration'] from the first record
    echo "SQL: " . $sql;
    echo "<br>";
    echo "Поиск занял: " . $duration . " секунд";
    
    // $this->view->generate('test_view.php', 'template_view.php');
  }

  function action_testpdf() {
    Testing::prettyPrint($_SERVER['HTTP_HOST']);
    $pdfHanlder = new PDFHandler();
    
    $templatePath = ROOT . "app/views/PDF/test.php";

    $html = $pdfHanlder->populateTemplate($templatePath, ["testKey" => "123"]);

    $pdfHanlder->generatePDF($html);
    // echo $html;
  }

  function action_tesseract() {
    // foreach((new TesseractOCR())->availableLanguages() as $lang) echo $lang; 
    echo "<pre>";
    echo (new TesseractOCR('images/tesseract/big-check.jpg'))
      ->lang('rus')
      ->run();
    echo "</pre>";

    // $output=null;  
    // $retval=null;
    // exec('/usr/bin/tesseract', $output, $retval);
    // echo "Вернёт статус $retval и значение:\n";
    // echo exec('/usr/bin/tesseract');
    // print_r($output);
  }
}
