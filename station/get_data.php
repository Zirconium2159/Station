<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<!--以下为接收数据代码-->
<?php
$temperature = $_GET["tem"];
$humidity = $_GET["hum"];
$lightness = $_GET["light"];
    $conn = @mysql_connect('127.0.0.1', 'root', '233233');   //链接服务器
    if (!$conn) {
        die("failed");
    }
    @mysql_query("SET NAMES UTF8");
    @mysql_select_db('wifistation', $conn) or die("cannot find");             //选择数据库
if(empty($temperature)||empty($humidity)||empty($lightness)){                  //如果数据为空则重复插入上一条数据
    $result = @mysql_query('SELECT * FROM weatherdata ORDER BY did DESC LIMIT 0,1',$conn); //SELECT为SQL语句，result为查询结果返回
    $result_arr = @mysql_fetch_assoc($result);                                 //转化成为数组
    $temperature_new = $result_arr['temperature'];
    $humidity_new = $result_arr['humidity'];
    $lightness_new = $result_arr['lightness'];
    $insert ="insert into  weatherdata (temperature,humidity,lightness) VALUES ('$temperature_new','$humidity_new','$lightness_new')";   //SQL插入语句
}
else{                                                                         //如果接收到数据则插入数据表中
    $insert = "insert into weatherdata (temperature,humidity,lightness) VALUES ('$temperature','$humidity','$lightness')";
}
@mysql_query($insert,$conn);                                                   //执行插入语句
?>
</body>
</html>
