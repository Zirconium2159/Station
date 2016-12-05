<?php
include "php/database.php";

$conn = connectDB();

$result_tem = $conn->query('SELECT temperature FROM weatherdata ORDER BY id DESC LIMIT 0,1');
$result_hum = $conn->query('SELECT humidity FROM weatherdata ORDER BY id DESC LIMIT 0,1');
$result_ill = $conn->query('SELECT lightness FROM weatherdata ORDER BY id DESC LIMIT 0,1');


$result_tem_arr = $result_tem->fetch_assoc();
$result_hum_arr = $result_hum->fetch_assoc();
$result_ill_arr = $result_ill->fetch_assoc();

$tem = $result_tem_arr['temperature'];
$hum = $result_hum_arr['humidity'];
$ill = $result_ill_arr['lightness'];

$status = weather();
$word = words();
?>
<!--首页-->
<!DOCTYPE html>
<html>

<head>
    <title>寝室环境物联网检测系统</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="css/bootstrap/js/bootstrap.min.js"></script>
</head>

<body style="background-image: url('img/back.jpg')">
<div class="container">

    <div class="info">

        <svg class="circle" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50%" cy="50%" r="106" stroke="white" stroke-width="3" fill="none" />
        </svg>

        <div class="temp">
            <p id="data1"></p>
        </div>

        <div class="hum">
            <img class="icon" alt="26*26" src="img/hum.png" />
            <p id="data2"></p>
            <img class="icon" alt="26*26" src="img/light.png" />
            <p id="data3"></p>
        </div>

    </div>


    <a id="btn" href="history.php">环境变化记录</a>

    <p id="sentence"></p>
    <img id="logo" alt="62x30" src="img/logo.png" />

</div>
<script type="text/javascript">
    var temp,hum,light,word,img;
    temp='<?=$tem?>';
    hum='<?=$hum?>';
    light='<?=$ill?>';
    word='<?=$word?>';
    document.getElementById("data1").innerHTML=temp;
    document.getElementById("data2").innerHTML=hum+"%";
    document.getElementById("data3").innerHTML=light+"lx";
    document.getElementById("sentence").innerHTML=word;

</script>
</body>

</html>

