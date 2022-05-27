<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <?php
        echo "这是内嵌在html里的php"
    ?>
    <form action="secondPhp.php" method="post">
        <p><input type="submit" value="跳转到第二个页面"></p>
    </form>
    <form method="post" action="service/inventoryService.php">
        <table>
            <tr>
                <td>invId</td>
                <td><input  name="invId" value=""></td>
            </tr>
            <tr>
                <td>funcId</td>
                <td><input  name="funcId" value="1691" ></td>
            </tr>
            <tr>
                <td>invCode</td>
                <td><input  name="invCode" value="1692"></td>
            </tr>
            <tr>
                <td>invName</td>
                <td><input  name="invName" value="1693"></td>
            </tr>
            <tr>
                <td>invSpecies</td>
                <td><input  name="invSpecies" value="1694"></td>
            </tr>
            <tr>
                <td>invClassId</td>
                <td><input  name="invClassId" value="1695"></td>
            </tr>
            <tr>
                <td>gwDiff</td>
                <td><input  name="gwDiff" value="1696"></td>
            </tr>
            <tr>
                <td>thicknessInMM</td>
                <td><input  name="thicknessInMM" value="1697"></td>
            </tr>
            <tr>
                <td>widthInMM</td>
                <td><input  name="widthInMM" value="1698"></td>
            </tr>
            <tr>
                <td>lengthInMM</td>
                <td><input  name="lengthInMM" value="1699"></td>
            </tr>
            <tr>
                <td>remark</td>
                <td><input  name="remark" value="1670"></td>
            </tr>
            <tr>
                <td>createPerson</td>
                <td><input  name="createPerson" value="1671"></td>
            </tr>
          <tr>
              <td>createDate</td>
              <td><input type="date" name="createDate" value="<?php echo date("Y-m-d")?>"></td>
          </tr>

        </table>
<!--        <div><input type="datetime-local" name="createDate" value="--><?php //echo date("Y-m-d H:i:s")?><!--"></div>-->
<!--        <div><input type="date" name="createDate" value="--><?php //echo date("Y-m-d")?><!--"></div>-->
        <div><input type="submit" value="提交" name="submit"></div>
    </form>
    <form method="post" action="service/getFiles.php">
        <table border="1" >
            <tr>
                <td>invId</td>
                <td>funcId</td>
                <td>invCode</td>
                <td>invName</td>
                <td>invSpecies</td>
                <td>invClassId</td>
                <td>gwDiff</td>
                <td>thicknessInMM</td>
                <td>widthInMM</td>
                <td>lengthInMM</td>
                <td>remark</td>
                <td>createPerson</td>
                <td>createDate</td>
            </tr>
            <?php
                require_once("db/DbManage.php");
                $dbManage = new DbManage();

                $query = "SELECT*FROM inventory";
                $result = $dbManage->getFiles($query);

                while(!!$row = mysqli_fetch_array($result)){
                    echo "<tr><td>".$row["invId"]."</td><td>".
                        $row["funcId"]."</td><td>".
                        $row["invCode"]."</td><td>".
                        $row["invName"]."</td><td>".
                        $row["invSpecies"]."</td><td>".
                        $row["invClassId"]."</td><td>".
                        $row["gwDiff"]."</td><td>".
                        $row["thicknessInMM"]."</td><td>".
                        $row["widthInMM"]."</td><td>".
                        $row["lengthInMM"]."</td><td>".
                        $row["remark"]."</td><td>".
                        $row["createPerson"]."</td><td>".
                        $row["createDate"]."</td><td>";
                }
            ?>
        </table>

    </form>


    <form action="lookUp.php" method="post">
        <p><input type="submit" value="查看信息"></p>
    </form>
</body>
</html>