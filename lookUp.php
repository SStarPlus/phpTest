<head>
    <meta charset="UTF-8">
    <style rel="stylesheet">
        table{border-left: 1px solid black; border-top: 1px solid black; border-collapse: separate;border-spacing: 0px;}
        td{border-right: 1px solid black; border-bottom: 1px solid black}
    </style>
</head>
<!--    </form>-->
    <form method="post" action="service/getFiles.php">
        <table>
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
                        $row["createDate"]."</td></tr>";
                }
            ?>
        </table>

    </form>

