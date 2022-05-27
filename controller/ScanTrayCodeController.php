<?php
    include_once ("../service/ScanTrayCode.php");
    $pdaCode = $_GET["pdaCode"];
    $userId = $_GET["userId"];
    $fstId = $_GET["fstId"];
    $scanId = $_GET["scanId"];
    $whId = $_GET["whId"];
    $trayCode = $_GET["trayCode"];
//echo $fstId . "|" . $pdaCode . "|" . $userId . "|" . $scanId . "|" . $whId;
    $scanTrayCode = new ScanTrayCode();
//    $result = $scanTrayCode->loadingTrayCode($pdaCode, $userId, $fstId, $scanId, $whId);

    $result = $scanTrayCode->scanTrayCodeInfoStorage($pdaCode, $userId, $fstId, $scanId, $whId,$trayCode);
//    if(count($result > 0))
//        $result = json_encode($result);
//    else
//        $result = "[" .json_encode($result) . "]";
//
//    echo $result;