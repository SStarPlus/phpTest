<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <!-- <script src="bootstrap/js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
     <script type="text/javascript" src="js/jquery3.js"></script>
     -->
</head>
<body>
    <?php
    //include_once ("../service/StudentService.php");
    //session_start();
    //$stu_info = $_SESSION["stu_info"];//        echo $_SESSION['x_no'];
    ?>

    <form action="../controller/insertTestTableController.php" method="post">
        <div>
            <div>测试id:<input name="testId" maxlength="3" type="text"></div>
            <div>测试用户名：<input name="testName" maxlength="10" type="text"></div>
            <div><input type="submit" value="提交"></div>
        </div>
    </form>
</body>
</html>