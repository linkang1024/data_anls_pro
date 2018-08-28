<?php
$tmp_id=$_GET["client_id"];
$tmp_pay_type = $_GET["pay_type"];
$tmp_date = $_GET["date"];


if(strlen($tmp_id) != 9){
	$tmp_id = "and ClientID like '%".$tmp_id."%'";
}
else
{
	$tmp_id = " and ClientID = '".$tmp_id."'";
}

if($tmp_pay_type == "ALL")
{
	$tmp_pay_type = "";
}
else
{
	$tmp_pay_type = " and Pay_type = '".$tmp_pay_type."'";
}

$con = mysqli_connect('localhost', 'root', 'wggsql1024');
if(!$con){
	print_r("open mysql fail");
}

mysqli_select_db($con, "demoDB");
$sql = "select a.ID as ID, a.Order_number as ORDER_ID, a.ClientID as CLIENT_ID, DATE_FORMAT(a.Date_time, '%Y-%m-%d %H:%i:%s') as DAYTIME, a.Total_money as TOTAL, "
		."a.Pay_type as PAY_TYPE, b.Name as PRICE, b.number as NUMS "
		."from demoOrder a left join demoOrderitem b on a.ID = b.OrderID where "
		."a.Date_time like '".$tmp_date."%'".$tmp_id.$tmp_pay_type." order by a.Date_time";

$sql_page = "select count(1) as pagesize from (".$sql.") as tmp";
$page_ret = mysqli_query($con, $sql_page);
if(mysqli_num_rows($page_ret) > 0)
{
	while($row = mysqli_fetch_assoc($page_ret)){
		$tmp_page = $row["pagesize"];
	}
}

if($tmp_page % 40 != 0){
	$page_size = floor($tmp_page / 40) + 1;
}
else{
	$page_size = $tmp_page / 40;
}

$sql_query = "select * from (".$sql.") as tmp limit 0, 40";
$result = mysqli_query($con, $sql_query);
$array_data = array();
if(mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result)){
		$array_data[] = $row;	
	}
	echo json_encode(array('page-size'=>$page_size, 'data'=>$array_data));
}
else
{
	echo "null";
}
/*

$order_data = array();
if (mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result)){
		$order_data[] = $row;
	}
	echo json_encode($order_data);
}
else
{
	echo "null";
}

mysqli_close($con);*/
?>
