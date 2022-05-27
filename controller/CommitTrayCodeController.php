<?php
include_once ("../service/ScanTrayCode.php");
$pdaCode = $_GET["pdaCode"];
$userId = $_GET["userId"];
$fstId = $_GET["fstId"];
$scanId = $_GET["scanId"];
$whId = $_GET["whId"];
$sNo = $_GET["sNo"];
$invId = $_GET["invId"];
$sums = $_GET["sums"];
$proId = $_GET["proId"];
$barCode = $_GET["barCode"];
$createPerson = $_GET["createPerson"];
//    echo $fstId . "|" . $pdaCode . "|" . $userId . "|" . $scanId . "|" . $whId;
$scanTrayCode = new ScanTrayCode();
//$result = $scanTrayCode->commitTrayCodeList($pdaCode, $userId, $fstId, $scanId, $whId, $createPerson);
    $result = $scanTrayCode->commitTrayCodeList($fstId, $whId,$createPerson,$sNo,$invId,$sums,$proId,$barCode);


//if(count($result > 0))
//    $result = json_encode($result);
//else
//    $result = "[" .json_encode($result) . "]";

echo $result;