<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php if(isset($_title)) echo htmlspecialchars($_title) . ' - '; echo AgaviConfig::get('core.app_name'); ?></title>

	<link rel="stylesheet" href="/assets/css/blueprint/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="/assets/css/blueprint/print.css" type="text/css" media="print" />
	<!--[if IE]>
	  <link rel="stylesheet" href="/assets/css/blueprint/ie.css" type="text/css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" href="/assets/css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="/assets/css/blueprint/plugins/tabs/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="/assets/css/blueprint/plugins/silksprite/sprite.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="/assets/css/blueprint/plugins/liquid/liquid.css" type="text/css" media="screen, projection" />

	<link rel="stylesheet" href="/assets/css/bundle.css" type="text/css" media="screen" />

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.2.1/mootools-yui-compressed.js"></script>
	<script type="text/javascript" src="/assets/js/bundle.js"></script>
</head>
<body>
	<div id="header">
		<div class="container">
			<h1 class="push-0"><a href="<?= $ro->gen('index') ?>"><?= AgaviConfig::get('core.site_name') ?></a></h1>
			<h2><a href="<?= $ro->gen(null) ?>"><?= htmlspecialchars($_title) ?></a></h2>
		</div>
	</div>

	<div id="content">
		<div class="container">
			<?php echo $inner; ?>
		</div>
	</div>

	<div id="footer">
		<div class="container">
			<?= AgaviConfig::get('core.site_name') ?> is <a href="http://digitarald.com">digitarald.com</a> © 2009
		</div>
	</div>
	<script type="text/javascript">pullhub.ready();</script>
</body>
</html>
