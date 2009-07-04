<!-- Debug Tool Bar Middleware -->

<style>
	#rt_toolbar {
		padding: 10px;
		border: 1px solid #ddd;
		position: absolute;
		top: 0;
		right: 200px;
		background-color: #fff;
	}
	#rt_logs {
	}
	
</style>


<div id="rt_toolbar">
	<div id="rt_toolbar_banner">
		<a id="rt_toolbar_switcher" href="#" >Show</a>
	</div>
	
	<div id="rt_logs" style="display:none">
		<pre>
		<?php foreach ($logs as $log) {
			echo $log['message'] . "<br />";
		}
		
		var_dump($rt_flash);
		var_dump($rt_request);
		?>
		</pre>
	</div>
</div>
