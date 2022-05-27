<?php
    require_once("../db/DbManage.php");
    $invId = $_POST["invId"];
    $funcId = $_POST["funcId"];
    $invCode = $_POST["invCode"];
    $invName = $_POST["invName"];
    $invSpecies = $_POST["invSpecies"];
    $invClassId = $_POST["invClassId"];
    $gwDiff = $_POST["gwDiff"];
    $thicknessInMM = $_POST["thicknessInMM"];
    $widthInMM = $_POST["widthInMM"];
    $lengthInMM = $_POST["lengthInMM"];
    $remark = $_POST["remark"];
    $createPerson = $_POST["createPerson"];
    $createDate = $_POST["createDate"];

    $dbManage = new DbManage();
    $sqlTxt = "insert into inventory1(invId, funcId, invCode, invName, invSpecies,".
    "invClassId, gwDiff, thicknessInMM, widthInMM, lengthInMM, remark, createPerson,".
    "createDate) values('" . $invId . "','"
                          . $funcId . "','"
                          . $invCode . "','"
                          . $invName . "','"
                          . $invSpecies . "','"
                          . $invClassId . "','"
                          . $gwDiff . "','"
                          . $thicknessInMM . "','"
                          . $widthInMM . "','"
                          . $lengthInMM . "','"
                          . $remark . "','"
                          . $createPerson . "','"
                          . $createDate . "')";
//
//    $sqlTxt = "insert into inventory(invId,funcId) value ('" . $invId . "','"
//                          . $funcId . "')";
    $result = $dbManage->excuteSqlTxt($sqlTxt);

    if ($result==true)
        header("location:../lookUp.php");
    else
        header("location:../web/error.php");

