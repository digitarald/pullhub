<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php if(isset($_title)) echo htmlspecialchars($_title) . ' - '; echo AgaviConfig::get('core.app_name'); ?></title>
	
	<link rel="stylesheet" href="/assets/css/blueprint/lib/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="/assets/css/blueprint/lib/print.css" type="text/css" media="print"> 
	<!--[if IE]>
	  <link rel="stylesheet" href="/assets/css/blueprint/lib/ie.css" type="text/css" media="screen, projection">
	<![endif]-->
	<link media="screen" rel="stylesheet" type="text/css" src="/assets/css/bundle.css" />
	
	<script type="text/javascript" src="/assets/js/bundle.js"></script>
</head>
<body>
	<div id="header">
		<div class="container">
			<h1><?php if(isset($_title)) echo htmlspecialchars($_title) . ' - '; echo AgaviConfig::get('core.app_name'); ?></h1>
		</div>	
	</div>
	
	<div id="content">
		<div class="container">
			<?php echo $inner; ?>
		</div>
	</div>
	
	<div id="footer">
		<div class="container">
			PullHub is <a href="http://digitarald.com">digitarald.com</a> © 2009
		</div>	
	</div>
</body>
</html>
