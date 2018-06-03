<!DOCTYPE html>
<html>
<head>
	<title>Upload Status | Redirection</title>
</head>
<script type="text/javascript">
	var time = 0;
	var threshold = <?php
		if($upload_error == true)
			echo "10";
		else
			echo "5";
		?>;
	setInterval(function(){
		console.log(time+" seconds");
		time++;
		document.getElementById("num").innerHTML = threshold - time;
		if(time == threshold)
			window.location = "/";
	},1000);
</script>
<body>
	<h1><?php
	if($upload_error == true)
		echo "Upload Failed!";
	else
		echo "Upload Success!";
	?></h1>
	<h2><?php
			echo $upload_message;
	 ?></h2>
	<h3>Redirecting to home page in <span id="num">0</span> seconds.</h3>
</body>
<script type="text/javascript">
	document.getElementById("num").innerHTML = threshold;
</script>
</html>