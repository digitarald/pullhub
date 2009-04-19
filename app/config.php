<?php

// +---------------------------------------------------------------------------+
// | Should we run the system in debug mode? When this is on, there may be     |
// | various side-effects. But for the time being it only deletes the cache    |
// | upon start-up.                                                            |
// |                                                                           |
// | This should stay on while you're developing your application, because     |
// | many errors can stem from the fact that you're using an old cache file.   |
// |                                                                           |
// | This constant will be auto-set by Agavi if you do not supply it.          |
// | The default value is: <false>                                             |
// +---------------------------------------------------------------------------+
// AgaviConfig::set('core.debug', true);

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the agavi package. This directory          |
// | contains all the Agavi packages.                                          |
// |                                                                           |
// | This constant will be auto-set by Agavi if you do not supply it.          |
// | The default value is the name of the directory "agavi.php" resides in.    |
// +---------------------------------------------------------------------------+
// AgaviConfig::set('core.agavi_dir', 'C:\xampp\htdocs\pullhub\vendor\agavi');

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to your web application directory. This       |
// | directory is the root of your web application, which includes the core    |
// | configuration files and related web application data.                     |
// | You shouldn't have to change this usually since it's auto-determined.     |
// | Agavi can't determine this automatically, so you always have to supply it.|
// +---------------------------------------------------------------------------+
AgaviConfig::set('core.app_dir', dirname(__FILE__));

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the directory where cache files will be    |
// | stored.                                                                   |
// |                                                                           |
// | NOTE: If you're going to use a public temp directory, make sure this is a |
// |       sub-directory of the temp directory. The cache system will attempt  |
// |       to clean up *ALL* data in this directory.                           |
// |                                                                           |
// | This constant will be auto-set by Agavi if you do not supply it.          |
// | The default value is: "<core.app_dir>/cache"                              |
// +---------------------------------------------------------------------------+
// AgaviConfig::set('core.cache_dir', AgaviConfig::get('core.app_dir') . '/cache');

// +---------------------------------------------------------------------------+
// | You may also modify the following other directives in this file:          |
// |  - core.config_dir   (defaults to "<core.app_dir>/config")                |
// |  - core.lib_dir      (defaults to "<core.app_dir>/lib")                   |
// |  - core.model_dir    (defaults to "<core.app_dir>/models")                |
// |  - core.module_dir   (defaults to "<core.app_dir>/modules")               |
// |  - core.template_dir (defaults to "<core.app_dir>/templates")             |
// +---------------------------------------------------------------------------+

// the folder with customizations
AgaviConfig::set('core.custom_dir', AgaviConfig::get('core.project_dir') . '/custom');
// holds the 3rd party libraries
AgaviConfig::set('core.vendor_dir', AgaviConfig::get('core.project_dir') . '/vendor');

// Report all errors during development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Custom error handler, to throw nice-looking error exceptions
function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
{
	$report = error_reporting();
	if ($report && $report & $errno) {
	  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
}
set_error_handler('errorHandler');

ini_set('session.use_trans_sid', '0');

set_include_path(AgaviConfig::get('core.vendor_dir') . PATH_SEPARATOR . get_include_path());

?>