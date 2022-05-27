<?php
class DbManage{
    private $conn;
    public function executeSqlTxts($sqlTxts){
        $this->conn = mysqli_connect("127.0.0.1","root","root","php_warehouse")
        or die("失败". mysqli_error());
        $this->conn->autocommit(false);
        for ($i = 0;$i < count($sqlTxts);$i++){
            mysqli_query($this->conn,$sqlTxts[$i]);
        }
        $result = $this->conn->commit();
        if ($result == false)
            $this->conn->rollback();

        return $result;
     }
    public function executeSqlTxt($sqlTxt){
        $this->conn = mysqli_connect("127.0.0.1","root","root","php_warehouse")
            or die("失败". mysqli_error());
        $result = mysqli_query($this->conn,$sqlTxt);

        return $result;
    }

    public function getFiles($sqlTxt){
        $this->conn = mysqli_connect("127.0.0.1","root","root","php_warehouse")
        or die("失败". mysqli_error());
        $result = mysqli_query($this->conn,$sqlTxt);

        return $result;
    }

    public function closeConnection($result){
        mysqli_free_result($result);

        mysqli_close($this->conn);

    }
}