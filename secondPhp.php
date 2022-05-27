<?php
    $a = 3;
    $b = "aaaa";
    echo $a ."<br>";
    echo $b ."<br>";
    echo "<a href='firstphp.php'>跳转</a>";
    echo "当前文档路径" . __FILE__;
    echo "当前php版本：" .PHP_VERSION;


    function getLoginName(){
        $userName = "jq";
        echo $userName;
    }
    getLoginName();