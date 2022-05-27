<?php
    require_once("../db/DbManage.php");
        function insertTestTable($testId,$testName){
            $dbManage = new DbManage();
            $sqlTxt = "insert into test(testId,testName) values('" . $testId . "','"
                . $testName . "')";

            echo @$sqlTxt;
            $result = false;
            $result = $dbManage->excuteSqlTxt($sqlTxt);

            return $result;
        }
