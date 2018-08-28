<?php
$name=$_GET["name"];
$pw = $_GET["pw"];

$con = mysqli_connect('localhost', 'root', 'wggsql1024');
if(!$con){
	print_r("open mysql fail");
}

mysqli_select_db($con, "demoDB");
$sql = "select * from demoUser where USERNAME= '".$name."' and PASSWORD='".$pw."';";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result)){
		echo $row["CLIENT_ID"];
	}
}
else
{
	echo "null";
}

mysqli_close($con);

?>
