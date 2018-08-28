<?php
$tmp_id=$_GET["client_id"];
$tmp_pay_type = $_GET["pay_type"];
$tmp_date_s = $_GET["date_s"];
$tmp_date_e = $_GET["date_e"];


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

$sql = "select DATE_FORMAT(Date_time, '%Y-%m-%d') as DAYTIME, count(1) as COUNT, sum(Total_money) as MONEY from  demoOrder where Date_time >= '"
	.$tmp_date_s." 00:00:00' and Date_time <= '".$tmp_date_e." 23:59:59'"
	.$tmp_id.$tmp_pay_type." GROUP BY DAY(Date_time) desc;";

$result = mysqli_query($con, $sql);
$order_data = array();
if (mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result)){
		$order_data[] = $row;
	}
	//echo json_encode(array('orderdata'=>$order_data));
	echo json_encode($order_data);
}
else
{
	echo "null";
}

mysqli_close($con);
?>
