<html>
<head>
	<title>Rythm PHP Framework</title>
	<style type="text/css">
	body#error500 {
		font-size: 12px;
		background-color: #efefef;
		margin: 0;
	}
	
	#error500 #wrapper {
		background-color: #fff;
		margin: 20px auto;
		width: 800px;
		border: 1px solid #acacac;
	}
	#error500 #header {
		margin: 0 20px;
		border-bottom: 2px solid #9e9e9e;
	}
	#error500 #header h1 {
		color: #5e5e33;
	}
	#error500 #content {
		padding: 20px
	}
	#error500 #msg {
		border: 1px solid #cfcfd0;
		border-width: 1px 0;
		background-color: #efefef;
		padding: 10px;
		font-size: 14px;
	}
	
	</style>
</head>
<body id="error500">
	<div id="wrapper">
		<div id="header">
			<h1>Rythm PHP Framework</h1>
		</div>
		<div id="content"><?php echo $rt_layout_content; ?></content>
	</div>
</body>
</html>