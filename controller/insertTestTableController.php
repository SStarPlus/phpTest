<?php
    require_once("../service/testTableService.php");

    $testId = $_POST{"testId"};
    $testName = $_POST{"testName"};

//    echo $testId. "|".$testName;
    $result = insertTestTable($testId,$testName);
    echo $result;

    if ($result == true){
        header("location:../web/main.php");
    }
    else{
        header("location:../web/error.php");
    }