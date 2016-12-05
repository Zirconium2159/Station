<?php
    include "php/database.php";
    $conn = connectDB();

    //取之前8小时数据
    $result_tem = $conn->query('SELECT temperature FROM weatherdata ORDER BY id DESC LIMIT 0,54');
    $json_tem = average($result_tem,8,'temperature');

    $result_hum = $conn->query('SELECT humidity FROM weatherdata ORDER BY id DESC LIMIT 0,54');
    $json_hum = average($result_hum,8,'humidity');

    $result_ill = $conn->query('SELECT lightness FROM weatherdata ORDER BY id DESC LIMIT 0,54');
    $json_ill = average($result_ill,8,'lightness');
    $status = weather();                        //更换背景图片
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>气象站</title>
    <script src="js/echarts.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="css/bootstrap/js/bootstrap.min.js"></script>
    <style type="text/css">
        /*nav bar*/
        .navbar-default{background-color: transparent;color: white; border-color: transparent;font-size:45px;height:100px;padding:25px 25px;margin:0 auto}  /*导航栏背景颜色和边框*/
        /*.navbar-default .navbar-brand{color: white;font-size:50px;}   !*标题字体颜色*!*/
        /*.navbar-default .navbar-brand:hover,           !*悬停*!*/
        /*.navbar-default .navbar-brand:focus{color: #761c19;}  !*点击*!*/
        .navbar-default .navbar-nav {background-color: transparent;color: white;}   /*默认链接部分正常字体&背景颜色*/
        .navbar-default .navbar-nav > li > a{ color: white;border-left:solid;}                      /*其他链接部分正常字体&背景颜色*/
        .navbar-default .navbar-nav > li > a:hover,                                 /*悬停*/
        .navbar-default .navbar-nav > li > a:focus{ color: #761c19;}                /*其他链接点击后字体颜色*/
        .navbar-default .navbar-nav >.active> a { background-color: transparent; color: white;}     /*默认部分设置*/
        .navbar-default .navbar-nav >.active> a:hover,
        .navbar-default .navbar-nav >.active> a:focus{ background-color: transparent; color: #761c19;}
        /*row*/
        .row .col-md-2{font-size: 35px;color: white;text-align: center;}
        .row .col-sm-2{font-size: 35px;color: white;text-align: center;}
	.row {margin-right:0px;}
    </style>
</head>
<body style="background-image: url('img/<?=$status?>.jpg')">
<nav class="navbar navbar-default" role="navigation">
    <!--        <div class="navbar-header">-->
    <!--            <a class="navbar-brand" href="#">WiFi气象站</a>-->
    <!--        </div>-->
    <div>
        <ul class="nav navbar-nav">
            <li><a href="history.php">历史1小时</a></li>
            <li class="active"><a href="#">历史8小时</a></li>
            <li><a href="history_24.php">历史24小时</a> </li>
        </ul>
    </div>

</nav>

<div class="row">
    <div class="col-md-2 col-sm-2">温度</div>
    <div class="col-md-10 col-sm-10" id="chart_tem" style="width: 100%;height:450px;margin: 0 auto">
    </div>
    <div class="clearfix visible-sm"></div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2" >湿度</div>
    <div class="col-md-10 col-sm-10" id="chart_hum" style="width: 100%;height:450px;margin: 0 auto">
    </div>
<div class="clearfix visible-sm"></div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2">光照</div>
    <div class="col-md-10 col-sm-10" id="chart_light" style="width: 100%;height:450px;margin: 0 auto">
    </div>
<div class="clearfix visible-sm"></div>
</div>

<script type="text/javascript">
    var myChart_tem = echarts.init(document.getElementById('chart_tem'));
    var myChart_hum = echarts.init(document.getElementById('chart_hum'));
    var myChart_light = echarts.init(document.getElementById('chart_light'));

    //温度图
    option_tem = {
        title: {
            text: '',
            textStyle:{        //标题文字格式
                fontSize: 38,
                fontWeight: 'bolder',
                color: 'white'
            }
        },
        grid:{
            show:true,
            y:'3%',
            //y2:'0.2%',
            borderWidth:0
            //backgroundColor:'rgba(255,255,255,0.15)'
        },
        tooltip: {                    //在移动过程中显示数据
            trigger: 'item'
        },
        legend: {               //图例
            show:false,
            data:['气温']
        },
        dataZoom:{
            type: 'inside',
            show: false,
            start:25,
            end: 100,
            zoomLock:true
        },
        toolbox: {
            show: false
        },
        xAxis:  {   //横坐标  时间,读取系统时间
            show:false,
            type: 'category',
            axisLabel:{      //坐标轴
                textStyle:{
                    color:'white'
                }
            },
            axisLine:{
                lineStyle:{
                    color:'white'
                }
            },
            boundaryGap: false,
            splitLine: {
                show: false
            },
            data:['8小时前','7小时前','6小时前','5小时前','4小时前','3小时前','2小时前','1小时前','现在']
        },

        yAxis: {
            show:false,
            type: 'value',
            boundaryGap:true,
            scale: true,
            boundaryGap:[0.01,0.01],
            axisLabel: {
                formatter: '{value} °C'     //纵坐标
            },
            splitLine: {
                show: false
            }

        },
        series: [
            {
                name:'温度',
                type:'line',
                label:{
                    normal:{
                        textStyle:{
                            fontSize:28
                        }
                    }
                },
                formatter:'{line}°C',
                itemStyle : {
                    normal: {
                        label : {
                            show: true,     //每个点显示数据
                            position: 'top'
                        },
                        color : '#faf7f7'    //修改颜色
                    }
                },
                data:<?=$json_tem?>
            }
        ]
    };

    //湿度图
    option_hum = {
        title: {
            text: '',
            textStyle:{        //标题文字格式
                fontSize: 38,
                fontWeight: 'bolder',
                color: 'white'
            }
        },
        tooltip: {                    //在移动过程中显示数据
            trigger: 'item'
        },
        grid:{
            show :true,
            y:'3%',
            //y2:'0.2%',
            borderWidth:0
            //backgroundColor:'rgba(255,255,255,0.15)'
        },
        legend: {               //图例
            data:['湿度'],
            show:false
        },
        dataZoom:{
            type: 'inside',
            show: false,
            start: 25,
            end: 100,
            zoomLock:true
        },
        toolbox: {
            show: false,      //功能区
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['line', 'bar']},
                restore: {},          //还原为折线图
                saveAsImage: {}      //保存为图片
            }
        },
        xAxis:  {   //横坐标  时间,读取系统时间
            show:false,
            type: 'category',
            axisLabel:{      //坐标轴
                textStyle:{
                    color:'white'
                }
            },
            axisLine:{
                lineStyle:{
                    color:'white'
                }
            },
            boundaryGap: false,
            splitLine: {
                show: false
            },
            data:['8小时前','7小时前','6小时前','5小时前','4小时前','3小时前','2小时前','1小时前','现在']
//                (function (){
//                var now = new Date();    //读取当前时间
//                var res = [];
//                var len = 14;   //要显示的值数
//                while (len--) {
//                    res.unshift(now.toLocaleTimeString().replace(/^\D*/,''));  //正则表达式,生成时间
//                    //unshift() 方法可向数组的开头添加一个或更多元素，并返回新的长度。toLocaleTimeString可根据本地时间把 Date 对象的时间部分转换为字符串，并返回结果
//                    now = new Date(now - 3600*1000);    //多少秒一次间隔
//                }
//                return res;
//            })()
        },
        yAxis: {
            show:false,
            type: 'value',
            boundaryGap:true,
            scale: true,
            boundaryGap:[0.01,0.01],
            axisLabel: {
                formatter: '{value} %'     //纵坐标
            },
            splitLine: {
                show: false
            }

        },
        series: [
            {
                name:'湿度',
                type:'line',
                label:{
                    normal:{
                        textStyle:{
                            fontSize:28
                        }
                    }
                },
                //formatter:'{line}°C',
                itemStyle : {
                    normal: {
                        label : {
                            show: true,     //每个点显示数据
                            position: 'top'
                        },
                        color : '#faf7f7'    //修改颜色
                    }
                },
                data:<?=$json_hum?>
            }
        ]
    };

    //光照图
    option_light = {
        title: {
            text: '',
            textStyle:{        //标题文字格式
                fontSize: 38,
                fontWeight: 'bolder',
                color: 'white'
            }
        },
        tooltip: {                    //在移动过程中显示数据
            trigger: 'item'
        },
        grid:{
            show :true,
            y:'3%',
            borderWidth:0
            //backgroundColor:'rgba(255,255,255,0.15)'
        },
        legend: {                     //图例
            show:false,
            data:['亮度']
        },
        dataZoom:{
            type: 'inside',
            show: true,
            start: 25,
            end: 100,
            zoomLock:true
        },
        toolbox: {
            show: false,      //功能区
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['line', 'bar']},
                restore: {},          //还原为折线图
                saveAsImage: {}      //保存为图片
            }
        },
        xAxis:  {   //横坐标  时间,读取系统时间
            show:true,
            type: 'category',
            axisLabel:{      //坐标轴
                textStyle:{
                    color:'white',
                    fontSize:24
                }
            },
            axisLine:{
                lineStyle:{
                    color:'white'
                }
            },
            boundaryGap: false,
            splitLine: {
                show: false
            },
            data:['8小时前','7小时前','6小时前','5小时前','4小时前','3小时前','2小时前','1小时前','现在']
//                (function (){
//                var now = new Date();    //读取当前时间
//                var res = [];
//                var len = 14;   //要显示的值数
//                while (len--) {
//                    res.unshift(now.toLocaleTimeString().replace(/^\D*/,''));  //正则表达式,生成时间
//                    //unshift() 方法可向数组的开头添加一个或更多元素，并返回新的长度。toLocaleTimeString可根据本地时间把 Date 对象的时间部分转换为字符串，并返回结果
//                    now = new Date(now - 3600*1000);    //多少秒一次间隔
//                }
//                return res;
//            })()
        },
        yAxis: {
            show:false,
            type: 'value',
            boundaryGap:true,
            scale: true,
            boundaryGap:[0.01,0.01],
            axisLabel: {
                formatter: '{value} °C'     //纵坐标
            },
            splitLine: {
                show: false
            }

        },
        series: [
            {
                name:'光照',
                type:'line',
                label:{
                    normal:{
                        textStyle:{
                            fontSize:28
                        }
                    }
                },
                data:<?=$json_ill?>,

                itemStyle : {
                    normal: {
                        label : {
                            show: true,     //每个点显示数据
                            position: 'top'
                        },
                        color : '#faf7f7'    //修改颜色
                    }
                }
            }
        ]
    };

    myChart_tem.setOption(option_tem);   //绘图
    myChart_hum.setOption(option_hum);
    myChart_light.setOption(option_light);

    myChart_tem.group = 'group1';    //联动
    myChart_hum.group = 'group1';
    myChart_light.group = 'group1';
    echarts.connect('group1');

    window.onresize = function () {
        myChart_hum.resize();
        myChart_tem.resize();
        myChart_light.resize();
    }


</script>


</body>
</html>
