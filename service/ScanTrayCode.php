<?php
require_once ("../db/DbManage.php");

class ScanTrayCode{
    //1.loading storageTray里面的数据
    public function loadingTrayCode($pdaCode, $userId, $fstId, $scanId, $whId){
        $dbManage = new DbManage();
        $sqlTxt = " select DISTINCT st.stId, st.trayId, ps.trayCode, iy.invName from StorageTray st  
                      inner join Packages ps on st.trayId = ps.trayId  
                          and st.actFlag = 1 and ps.actFlag = 1 
                      inner join PackageItems pis on pis.trayId = ps.trayId  
                      inner join productItems pts on  pts.proId = pis.proId 
                      inner join inventory iy on iy.invId = pts.invId where st.pdaCode = '" . $pdaCode . "'
                       and st.createPerson = " . $userId . "
                       and st.fstId = " . $fstId . " 
                       and st.scanId = ". $scanId . "
                       and st.whId = " . $whId . " ";


        $result = $dbManage->executeSqlTxt($sqlTxt);
        $trayList = array();

        while ($row = mysqli_fetch_array($result)){
                array_push($trayList,$row);
//            $trayList[] = $row;
        }

        $dbManage ->closeConnection($result);
        return $trayList;
    }
    //2.扫码入库
    public function scanTrayCodeInWareHouse($pdaCode,$userId,$fstId,$scanId,$trayCode){

    }
    //3.查看托马对应下面的条码明细数据
    public function queryBarcodeTrayCode($pdaCode,$userId,$fstId,$scanId,$whId,$trayCode){}

    //扫码入库暂存表
    public function scanTrayCodeInfoStorage($pdaCode,$userId,$fstId,$scanId,$whId,$trayCode)
    {
        //1.建议是否能插入
        $checkTrayCodeStatus = $this->checkScanTrayCodeInfoStorage($pdaCode,
                $userId,$fstId,$scanId,$whId,$trayCode);

        if ($checkTrayCodeStatus != 1){
            return $checkTrayCodeStatus;
        }

        //2、如果能插入，则2-1 托码插入到StorageTray，对应的条码插入到StorageBar
        //2-1 获取当前要插入表的next主键id

        $stId = $this->generateNextPrimeKey("StorageTray");
        //这里应该写一个key调用带有事务性的函数调用，要么都成功，要么都失败
        //根据托码对应的条码数量+1构造数组的长度

        $barcodeSums = $this->getBarCodeSums($trayCode, $fstId, $whId);
        $dbManage = new DbManage();
        $trayId = -1;
        $sqlTxt = "select * from packages where trayCode = '" .$trayCode . "' and
         actFlag = 1";
        $result  = $dbManage -> executeSqlTxt($sqlTxt);
//        $stId = 0;
        while($row = mysqli_fetch_array($result)){
            $trayId = $row["trayId"];
        }

        $createDate = date('y-m-d h:i:s' , time());


//        $sqlTxts = array($barcodeSums + 1);
        $sqlTxts = array(2);
        //对$sqlTxts进行初始化
        $sqlTxts[0] = "insert into storageTray(StId , PdaCode , TrayId , TrayCode ,CreatePerson , CreateDate , ActFlag , ScanId , whId , fstId)
                        values(".
                        $stId . " , '" .$pdaCode . "' ," . $trayId . ", '" . $trayCode . "'," .
                        $userId . " , '" . $createDate . "' ,1 ," . $scanId ." ," . $whId . " ," .
                        $fstId . ")";
        echo $sqlTxts[0] . "<br>";

        $trayId =
        $sqlTxts[1] = " insert into storageBar (stId, proId , trayId , barCode , actFlag)" .
            " select " . $stId . ", proId," . $trayId . ", barCode, 1" .
            " from packageItems " .
            " where trayId =  " .$trayId;
        echo $sqlTxts[1] . "<br>";
        //        for ($i = 0; $i < $barcodeSums;$i++){
//            $sqlTxts[$i] = "insert into storageBar(sbId , stId , proId , taryId , barcode, actFlag)
//            values()";
//        }
//        $dbManage = new Manage();
        $result = $dbManage->executeSqlTxts($sqlTxts);

        return $result;
    }

    //真正应该把该函数放入应该通用工具类，作为应该工具类的函数存在
    private function generateNextPrimeKey($tableName){
        //获取当前代码
        $dbManage = new DbManage();
        $sqlTxt =  "select * from PrimeKeyInfo where tableName = '" . $tableName . "'";
        $result = $dbManage -> executeSqlTxt($sqlTxt);
        $stId = 0;
        while ($row = mysqli_fetch_array($result)){
            $stId = $row["PrimeKey"];
        }

        //把当前key = key + 1
        $sqlTxt = " update PrimeKeyInfo set primeKey = primeKey + 1 where tableName = '" . $tableName ."'";
        $result = $dbManage->executeSqlTxt($sqlTxt);

        return $stId;
    }

    private function generateNextPrimeCode($tableName){
        //获取当前的code
        $keyCode = "";
        $sqlTxt = "select tableCode from PrimeKeyInfo where tableName = " . $tableName ." ";
        //2:update code+1
        $dbManage = new DbManage();

        $result = $dbManage -> executeSqlTxt($sqlTxt);
        while($row = mysqli_fetch_array($result)){
            $keyCode = $row["primeCode"];
            break;
        }
        $keyDate = date('y-m-d',time());
        $tmpCode = number_format($keyCode);
        $tmpCode++;
        $newCode = str_pad($tmpCode,4,"0");//2=>0002
        $sqlTxt = "update PrimekeyInfo set primeCode = '". $newCode .
            "' where tableName = '" . $tableName ."'";
        $result = $dbManage->executeSqlTxt($sqlTxt);
        $primeCode = "wb" . $keyDate . $keyCode;
        return $primeCode;
//        $dbManage = new DbManage();
//        $stId = 0;
//        $sqlTxt =  "select * from PrimeKeyInfo where tableName = '" . $tableName . "'";
//        $result = $dbManage -> executeSqlTxt($sqlTxt);
//        $wbCode = 0;
//        while ($row = mysqli_fetch_array($result)){
//            $wbCode = $row["primeCode"];
//        }
//
//        //把当前key = key + 1
//        $sqlTxt = " update PrimeKeyInfo set primeCode = primeCode + 1 where tableName = '" . $tableName ."'";
//        $result = $dbManage->executeSqlTxt($sqlTxt);
//
//        return $wbCode;
    }

    private function getBarCodeSums($trayCode , $fstId , $whId){
        $checkSqlTxt = "";
        $dbManage = new DbManage();
        //1.根据输入的托码获取条码明细
        $checkSqlTxt = "select * from packages ps where ps.trayCode = '"
                    . $trayCode . "' and ps.pkStatus = 1 and ps.fstId = "
                    . $fstId ." and ps.actFlag = 1";

        $result = $dbManage->executeSqlTxt($checkSqlTxt);
        $count = 0;
        while ($row = mysqli_fetch_array($result)){
            $count++;
        }
        return $count;
    }

    //建议改条码是否能入库
    private function checkScanTrayCodeInfoStorage($pdaCode,$userId,$fstId,$scanId,
                                                 $whId,$trayCode){
        $checkSqlTxt = "";
        $dbManage = new DbManage();
        //1:看托码状态，为1才可入库

        $checkSqlTxt = "select * from packages ps where ps.trayCode = '" . $trayCode . "' and ps.pkStatus = 1 and ps.fstId = " . $fstId
                        . " and ps.actFlag = 1";
//        echo $checkSqlTxt . "<br>";
        $result = $dbManage->executeSqlTxt($checkSqlTxt);
        $count = 0;
        while ($row = mysqli_fetch_array($result)){
            $count++;
        }
        if ($count == 0){
            $msg = "该托码数据不在仓库内，或托码不属于该公司，或托码已被删除，请核实后再扫码";
            return $msg;
        }
        //检测storageTray暂存表内是否已经存在该数据
        $checkSqlTxt = "select * from storageTray st where st.trayCode = '"
                        . $trayCode . "' and st.scanId = '" . $scanId . "' and fstId = '" .$fstId
                        . "' and st.actFlag = 1 ";
//        echo $checkSqlTxt . "<br>";

        $result = $dbManage->executeSqlTxt($checkSqlTxt);
        $count = 0;
        while ($row = mysqli_fetch_array($result)){
            $count++;
        }
        if ($count > 0 ){
            $msg = "该托码已经被其他pds扫入暂存表，请找到该pda删除，请核实后再扫码";
            return $msg;
        }
        //3.是否该pda重复扫码
        $checkSqlTxt = "select * from storageTray  st where st.trayCode = '"
            . $trayCode . "' and st.scanId = " . $scanId . " and fstId = " . $fstId
            . " and st.actFlag = 1 and pdaCode = '" . $pdaCode ."'";

//        echo $checkSqlTxt . "<br>";
        $result = $dbManage->executeSqlTxt($checkSqlTxt);
        $count = 0;
        while ($row = mysqli_fetch_array($result)){
            $count++;
        }
        if ($count > 0 ){
            $msg = "该托码已被该pds扫入暂存表，请不要重复扫码";
            return $msg;
        }
        $msg = 1;
        return $msg;

    }

//    public function commitTrayCodeList($pdaCode, $userId, $fstId, $scanId, $whId,$createPerson){
    public function commitTrayCodeList( $fstId, $whId,$createPerson,$sNo,$invId,$sums,$proId,$barCode){
        //1.提交前的检验
        $dbManage = new DbManage();
        //2.生成入库单
        //2-1 先获取wbId和wbCode
        $wbId = $this->generateNextPrimeKey("whProBolloters");
        $wbCode = $this->generateNextPrimeCode("whProBolloters");
        $createDate = date('Y-m-d H:i:s');
        $remark = 0;
        $actFlag = 1;
        $wbType = 2;
//        //zanshi
//        //2-2 生成表头
//        $sqlBolloters = "insert into WhProBolloters()  ".
//            "select " .$wbId ."," .$wbCode ." ,1, st.fstId,st.whId,st.createPerson,st.createDate,1,".$remark." ".
//            " from storagetray st".
//            " where st.actFlag = 1";
            $sqlBolloters = "insert into WhProBolloters() values (wbId,wbCode,fstId,whId,wbType)".
                            " createPerson ,createDate,actFlag,remark) values(".
                            $wbId . "," .$wbCode ."," .$fstId . "," .$whId. " " . $wbType .
                            "," . $userId . ",'" .$createDate ."',1, 'pda扫码')";
        echo $sqlBolloters;

        $result = $dbManage->executeSqlTxt($sqlBolloters);
        echo "over1";

        //2-3 生成Items汇总
        $sqlBollotersItems =
//            " set @rank = 0;" .
            " insert into WhProBolloterItems(wbId,sNo,invId,sums,remark)".
            " select a.wbId,@rank:=@rank+1 as sNo ,a.invId,a.c sums,a.remark".
            " from ( " .
            " select wb.wbId,ps.proId,ps.fstId,ps.invId,wb.remark ,count(ps.invId) c".
            " from productitems ps".
            " inner join whprobolloters wb on ps.fstId = wb.fstId " .
            " where wb.fstId = " . $fstId .
            " Group by invId) a,(select @rank:=0)b;";
//
//        $sqlBollotersItems = " insert into WhProBolloterItems(wbId ,sNo,invId,sums,remark) ".
//                            " SELECT".$wbId.",1,pis.invId , COUNT(sb.proId) as sums , 'pda扫码' ".
//                                "from storagetray st ".
//                                " INNER JOIN storagebar sb on st.StId = sb.stId ".
//                                " INNER JOIN productitems pis on pis.proId = sb.proId ".
//                                "and pis.actFlag = 1 ".
//                                " WHERE st.ActFlag = 1 and st.ScanId =  ".$scanId.
//                                " and st.whId =  ".$whId.
//                                "and st.fstId =  ".$fstId.
//                                " and st.PdaCode = 'pda:android001' ".
//                             " group by pis.invId ";

        $result = $dbManage->executeSqlTxt($sqlBollotersItems);
        echo $sqlBollotersItems;
        echo "over2";
        //2-4 生成条码明细
        $sqlBollotersBarCode = "insert into whProBolloterBarCodes(wbId, sNo,proId,bAarCode) ".
                                " select a.wbId,@rank:=@rank+1 sNo,a.proId,a.barCode ".
                                " from (".
                                " select wb.wbId,ps.fstId,ps.barCode ".
                                " inner join whproBollters wb on ps.fstId = wb.fstId ".
                                " where wb.fstId = ".$fstId .
                                " )a,(select @rank:=0)b;";
        $result = $dbManage->executeSqlTxt($sqlBollotersBarCode);
        echo $sqlBollotersBarCode;
        echo "over3";

        $sqlFin = "UPDATE WhProBolloters".
                  " set actFlag = 0 where wbId = " . $wbId ." ";
//        $result = $dbManage->executeSqlTxt($sqlFin);
//        echo $sqlFin;

        //2-5 update productItems的每个proId对应的状态进行修改
        //2-6 update仓库对应产品的数量进行修改
        //2-7 把暂存表里对应的scanId，pdaCode等对应的所有数据的actFlag全部置为0
        //
    }
}