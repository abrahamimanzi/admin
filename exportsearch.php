<?php 
require_once 'core/init.php';
$db = DB::getInstance();

    $dataArr = array();
    $event_ID = Input::get('id','get');
    $agentClass = new Participant();

    $_FIELDS = $_SESSION["export"]["fieldArray"];
    $sql_fields = $_SESSION["export"]["fieldSQL"];
    $sql_conditions = $_SESSION["export"]["conditionSQL"];

    $agentClass->selectQuery("SELECT $sql_fields FROM `events_participant` $sql_conditions");
    if($agentClass->count()){
            foreach($agentClass->data() as $agent_data){
                $photo = @end(explode('/',@$agent_data->photo));
//                $photo = $agent_data->photo;
                
                $str_array  = array();
                foreach ($_FIELDS as $key) {
                  $str_array[$key] = $agent_data->$key;
                }
                $dataArr[] = $str_array;
            }

            // print_r($str_array);
            // exit();



          $data = $dataArr;
          function cleanData(&$str)
          {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
          }
    //
    //                                  // file name for download
          $filename = "YCA_" . date('Ymd') . ".xls";
    //
          header("Content-Disposition: attachment; filename=\"$filename\"");
          header("Content-Type: application/vnd.ms-excel");
    //
          $flag = false;
          foreach($data as $row) {
            if(!$flag) {
              // display field/column names as first row
              echo implode("\t", array_keys($row)) . "\n";
              $flag = true;
            }
            array_walk($row, __NAMESPACE__ . '\cleanData');
            echo implode("\t", array_values($row)) . "\n";
          }
    }
?>