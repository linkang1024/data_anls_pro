<?php
define("PATH", dirname(__FILE__));
$client_id = $_GET["client_id"];
$conn = mysqli_connect('localhost', 'root', 'wggsql1024');
if (!$conn) {
    echo "open databse fail";
    print_r("open database fail");
}
mysqli_select_db($conn, "demoDB");
$sql = "select device_id from demoArea where parent_id='" . $client_id . "';";
$result = mysqli_query($conn, $sql);
$blgs = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $blgs[] = $row["device_id"];
    }
}
$json_data = json_encode($blgs);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>戈子数据分析</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--引入Bootstrap样式-->
    <?php include(PATH . '/header.inc.php'); ?>
    <script type="text/javascript" language="javascript">
        function click_turnover_info() {
            $("#turnover_info")[0].style.display = "block";
            $("#order_info")[0].style.display = "none";
        }

        function click_order_info() {
            $("#turnover_info")[0].style.display = "none";
            $("#order_info")[0].style.display = "block";
        }

        function query_trunover() {
            var tmp_clientid = document.getElementById("sel_trunover_id").value;
            var tmp_pay_type = document.getElementById("trunover_pay_type").value;
            var tmp_date_s = document.getElementById("date_s").value;
            var tmp_date_e = document.getElementById("date_e").value;
            var str_url = "server/trunover.php?client_id=" + tmp_clientid + "&pay_type=" + tmp_pay_type + "&date_s=" + tmp_date_s + "&date_e=" + tmp_date_e;
            $.ajax({
                url: str_url,
                type: "GET",
                success: function (result) {
                    if (result == "null") {
                        alert("该时间段， 数据为空!!!");
                        return;
                    }
                    var json_data = eval(result);
                    var html = "";
                    $("#tbl_trunover").html(html);
                    html = html + '<tr>';
                    html = html + '<th>日期</th> <th>交易量</th> <th>营业额 </th>';
                    html = html + '</tr>';
                    for (var i = 0; i < json_data.length; i++) {
                        html = html + '<tr>';
                        html = html + '<td >' + json_data[i].DAYTIME + '</td>';
                        html = html + '<td >' + json_data[i].COUNT + '</td>';
                        html = html + '<td >' + json_data[i].MONEY + '</td>';
                        html = html + '</tr>';
                    }
                    $("#tbl_trunover").html(html);
                }
            });
        }


        function query_order() {
            var tmp_clientid = document.getElementById("sel_order_id").value;
            var tmp_pay_type = document.getElementById("trunover_pay_type").value;
            var tmp_date = document.getElementById("date_order").value;
            var str_url = "server/orderinfo.php?client_id=" + tmp_clientid + "&pay_type=" + tmp_pay_type + "&date=" + tmp_date;
            $.ajax({
                url: str_url,
                type: "GET",
                success: function (result) {
                    if (result == "null") {
                        alert("该时间段， 数据为空!!!");
                        return;
                    }
                    var tmp_data = JSON.parse(result);
                    var json_data = tmp_data.data;
                    var html = "";
                    $("#tbl_order").html(html);
                    html = html + '<tr>';
                    html = html + '<th>序号</th> <th>订单号</th> <th>终端号</th> <th>日期</th> <th>总价</th> <th>支付方式</th> <th>单价</th> <th>数量</th>';
                    html = html + '</tr>';
                    for (var i = 0; i < json_data.length; i++) {
                        html = html + '<tr>';
                        html = html + '<td >' + json_data[i].ID + '</td>';
                        html = html + '<td >' + json_data[i].ORDER_ID + '</td>';
                        html = html + '<td >' + json_data[i].CLIENT_ID + '</td>';
                        html = html + '<td >' + json_data[i].DAYTIME + '</td>';
                        html = html + '<td >' + json_data[i].TOTAL + '</td>';
                        html = html + '<td >' + json_data[i].PAY_TYPE + '</td>';
                        var tmp_price = parseFloat(json_data[i].PRICE);
                        html = html + '<td >' + tmp_price.toFixed(2) + '</td>';
                        html = html + '<td >' + json_data[i].NUMS + '</td>';
                        html = html + '</tr>';
                    }

                    $("#tbl_order").html(html);
                }
            });

            function callback(data) {
                //alert(data);
            }
        }
    </script>
</head>

<style>
    th {
        height: 45px;
        font-size: 23px;
        text-align: center
    }

    td {
        font-size: 20px;
        text-align: center;
        height: 30px
    }
</style>
<body>
<div id="bkimage" style="position:absolute; left:0px; top:0px; width:100%; height:100%;
background-image:url('res/timg.jpg');background-repeat:no-repeat; background-size:100% 100%; overflow:auto; ">
    <div class="panel-body" style="widht:auto height:200">
        <h1 style="color:#FFA500">戈子数据分析</h1>
    </div>
    <!--导航栏-->
    <div>
        <div style="position:absolute; top:120px; left:20px; right:0px; bottom:0px; width:200px; height:auto; background:#DCDCDC; box-shadow:0 0 2px 2px #f8f8f8">
            <ul class="nav nav-stacked">
                <li class="active">
                    <button onclick="click_turnover_info()"
                            style="width:200px; padding:10px; font-size:25px; color:#FFA500">营业额
                    </button>
                </li>
                <li>
                    <button onclick="click_order_info()"
                            style="width:200px; padding:10px; font-size:25px; color:#FFA500">订单明细
                    </button>
                </li>
            </ul>
        </div>
        <!--数据显示区-->
        <div name="dataanls" id="show_data" style="position:absolute; top:120px; left:240px; right:0px; bottom:0px; width:auto; height:auto;
		background:#DCDCDC; box-shadow:0 0 2px 2px #f8f8f8; overflow:auto">
            <!--turnover_info-->
            <div id="turnover_info" style="padding: 20px 40px; display:none; ">
                <span class="label label-info" style="font-size:25px; vertical-align: middle;t">终端号：</span>
                <div class="btn-group">
                    <select id="sel_trunover_id" class="combobox"
                            style="height:45px; width:140px;vertical-align: middle; font-size:20px">
                        <option value='<?php echo $client_id; ?>'>所有</option>
                        <script>
                            var data = eval(<?php echo json_encode($json_data);?>);
                            var combo = document.getElementById("sel_trunover_id");
                            for (var i = 0; i < data.length; i++) {
                                var tmpOP = document.createElement("option");
                                tmpOP.value = data[i];
                                tmpOP.text = data[i];
                                combo.appendChild(tmpOP);
                            }
                        </script>
                    </select>
                </div>
                <label style="width:15px; vertical-align: middle;"></label>
                <span class="label label-info" style="font-size:25px; vertical-align: middle;">支付类型：</span>
                <div class="btn-group">
                    <select id="trunover_pay_type" class="combobox"
                            style="height:45px; width:100px;vertical-align: middle; font-size:20px">
                        <option value="ALL">所有</option>
                        <option value="K">卡机</option>
                        <option value="W">微信</option>
                        <option value="Z">支付宝</option>
                        <option value="S">收钱吧</option>
                    </select>
                </div>
                <label style="width:15px; vertical-align: middle;"></label>
                <span class="label label-info" style="font-size:25px; vertical-align: middle;">起始：</span>
                <input id="date_s" type="date" id="datetimepicker" data-date-format="yyyy-mm-dd"
                       style="height:45px; width:200px;vertical-align: middle; font-size:23px">
                <script>
                    var date_s = new Date();
                    var weekday = date_s.getDay() || 7;
                    date_s.setDate(date_s.getDate() - weekday + 1);
                    document.getElementById("date_s").valueAsDate = date_s;
                </script>
                <label style="width:15px; vertical-align: middle;"></label>
                <span class="label label-info" style="font-size:25px; vertical-align: middle;">结束：</span>
                <input id="date_e" type="date" id="datetimepicker" data-date-format="yyyy-mm-dd"
                       style="height:45px; width:200px;vertical-align: middle; font-size:23px">
                <script>
                    var date_e = new Date();
                    date_e.setDate(date_e.getDate());
                    document.getElementById("date_e").valueAsDate = date_e;
                </script>
                <button onclick="query_trunover()"
                        style="vertical-align: middle; font-size:18px; height:45px; width:80px">查询
                </button>
                <h2>营业额明细：</h2>
                <div style="overflow-x: auto; overflow-y: auto;">
                    <table id="tbl_trunover" border="2" style="width:100%; height:100%">
                    </table>
                </div>
            </div>

            <!--order_info-->
            <div id="order_info" style="padding: 20px 40px; display:none; ">
                <span class="label label-info" style="font-size:25px; vertical-align: middle;t">终端号：</span>
                <div class="btn-group">
                    <select id="sel_order_id" class="combobox"
                            style="height:45px; width:200px;vertical-align: middle; font-size:20px">
                        <option value='<?php echo $client_id; ?>'>所有</option>
                        <script>
                            var data = eval(<?php echo json_encode($json_data);?>);
                            var combo = document.getElementById("sel_order_id");
                            for (var i = 0; i < data.length; i++) {
                                var tmpOP = document.createElement("option");
                                tmpOP.value = data[i];
                                tmpOP.text = data[i];
                                combo.appendChild(tmpOP);
                            }
                        </script>
                    </select>
                </div>
                <label style="width:15px; vertical-align: middle;"></label>
                <span class="label label-info" style="font-size:25px; vertical-align: middle;">支付类型：</span>
                <div class="btn-group">
                    <select id="order_pay_type" class="combobox"
                            style="height:45px; width:100px;vertical-align: middle; font-size:20px">
                        <option value="ALL">所有</option>
                        <option value="K">卡机</option>
                        <option value="W">微信</option>
                        <option value="Z">支付宝</option>
                        <option value="S">收钱吧</option>
                    </select>
                </div>
                <label style="width:15px; vertical-align: middle;"></label>
                <span class="label label-info" style="font-size:25px; vertical-align: middle;">日期：</span>
                <input id="date_order" type="date" id="datetimepicker" data-date-format="yyyy-mm-dd"
                       style="height:45px; width:200px;vertical-align: middle; font-size:23px">
                <script>
                    /*date_e.setDate(date_e.getDate());
                    document.getElementById("date_order").valueAsDate = date_e;*/
                    document.getElementById("date_order").value = "2018-07-19";

                </script>
                <button onclick="query_order()" style="vertical-align: middle; font-size:18px; height:45px; width:80px">
                    查询
                </button>
                <h2>订单明细：</h2>
                <div style="overflow:auto; width:100%; height:100%">
                    <table id="tbl_order" border="2" style="width:100%; height:100%; ">
                    </table>
                </div>
                <button style="vertical-align: middle; font-size:18px; height:45px; width:80px;">首页</button>
                <button style="vertical-align: middle; font-size:18px; height:45px; width:80px; display:inline;">上一页
                </button>
                <input class="form-control" type="text"
                       style="vertical-align: middle; font-size:18px; height:45px; width:80px; display:inline;">
                <button style="vertical-align: middle; font-size:18px; height:45px; width:80px; display:inline;">下一页
                </button>
                <button style="vertical-align: middle; font-size:18px; height:45px; width:80px; display:inline;">尾页
                </button>
                <label style="vertical-align: middle; font-size:30px; height:45px; width:auto; display:inline; padding: 50px">
                    当页面1 共200页</label>
            </div>
        </div>
    </div>
</body>
</html>
