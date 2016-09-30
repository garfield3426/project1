<?php
	session_start();
	echo $_POST["code"]."<br>";
	echo $_SESSION["code"]."<br>";

	if(strtoupper($_POST["code"])==strtoupper($_SESSION["code"])){
		echo "ok";
	}else{
		echo "error";
	}
?>
<body>
	<form action="login.php" method="post">
		user:<input type="text" name="usenrame"><br>
		pass:<input type="passowrd" name="pass"><br>

		code: <input type="text" name="code" onkeyup="if(this.value!=this.value.toUpperCase()) this.value=this.value.toUpperCase()"> <img src="code.php" onclick="this.src='code.php?'+Math.random()"><br>

		<input type="submit" name="sub" value="login"><br>
	</form>
</body>
