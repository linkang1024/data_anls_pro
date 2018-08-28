<?php
	define("PATH", dirname(__FILE__));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>戈子数据分析</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--引入Bootstrap样式-->
	<?php include(PATH . '/header.inc.php');?>	
	<script type="text/javascript" language="javascript">
		function fun(username, userpw) {
			var str_url = "server/login.php?name=" + username + "&pw=" + userpw;
			$.ajax({
				url: str_url, 			//the page containing php script
				type: "GET",						//request type
				success:function(result){
					if(result != "null"){
						window.location.href='main.php?client_id='+ result;			
					}else{
						window.alert("账号密码错误，请检查！！！")
					}
				}
			});
		}
		/*function callback(data) {
			alert(data);
		}*/
	</script>
</head>

<body>
<div id="bkimage" style="position:absolute; left:0px; top:0px; width:100%; height:100%;
background-image:url('res/background.jpg');background-repeat:no-repeat; background-size:100% 100%;">
    <h4 style="margin:10px 10px 0px 10px;">戈子科技数据分析</h4>
    <p style="margin:10px 20px 0px 20px;">版权：五宫格科技有限公司</p>
    <div style="position: fixed;top: 0px;left: 0px;right: 0px;bottom: 0px; margin: auto; width:380px;height:400px;">
        <div align="center">
            <img src="res/wgg_logo.jpg" width=97px; height=97px; style="border-radius:50%"  />
        </div>
        <div class="panel-body" width=380px; height=300px; style="margin:10px 0px 0px 0px;order-radius:50%">
            <form  class="bs-example bs-example-form" role="form">
                <div class="input-group input-group-lg" >
                    <span class="input-group-addon">账号</span>
                    <input id= "user" name="user" type="text" class="form-control" value="yida" placeholder="请输入您的账号">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon">密码</span>
                    <input id="pw" name="pw" type="password" class="form-control" value="123456" placeholder="请输入您的密码">
                </div>
                <div class="bs-example">
                    <button id="user_login" type="button" class="btn btn-primary" data-loading-text="正在登录中。。。"
                            style="width: 100%;margin:10px 0px 0px 0px;"
							onclick="fun(document.getElementById('user').value, document.getElementById('pw').value)" value="btn1">登录
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
