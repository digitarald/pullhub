<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2005-2009 the Agavi Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * AgaviLoggingConfigHandler allows you to register loggers with the system.
 *
 * @package    agavi
 * @subpackage config
 *
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     Bob Zoller <bob@agavi.org>
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.10.0
 *
 * @version    $Id: AgaviLoggingConfigHandler.class.php 3586 2009-01-18 15:26:12Z david $
 */
class AgaviLoggingConfigHandler extends AgaviXmlConfigHandler
{
	const XML_NAMESPACE = 'http://agavi.org/agavi/config/parts/logging/1.0';
	
	/**
	 * Execute this configuration handler.
	 *
	 * @param      AgaviXmlConfigDomDocument The document to parse.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>AgaviUnreadableException</b> If a requested configuration
	 *                                             file does not exist or is not
	 *                                             readable.
	 * @throws     <b>AgaviParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Bob Zoller <bob@agavi.org>
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function execute(AgaviXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'logging');
		
		// init our data, includes, methods, appenders and appenders arrays
		$code      = array();
		$loggers   = array();
		$appenders = array();
		$layouts   = array();

		foreach($document->getConfigurationElements() as $cfg) {
			if($cfg->has('loggers')) {
				foreach($cfg->get('loggers') as $logger) {
					$name = $logger->getAttribute('name');
					if(!isset($loggers[$name])) {
						$loggers[$name] = array('class' => null, 'level' => null, 'appenders' => array(), 'params' => array());
					}
					$loggers[$name]['class'] = $logger->hasAttribute('class') ? $logger->getAttribute('class') : $loggers[$name]['class'];
					$loggers[$name]['level'] = $logger->hasAttribute('level') ? $logger->getAttribute('level') : $loggers[$name]['level'];
					if($logger->has('appenders')) {
						foreach($logger->get('appenders') as $appender) {
							$loggers[$name]['appenders'][] = $appender->getValue();
						}
					}
					$loggers[$name]['params'] = $logger->getAgaviParameters($loggers[$name]['params']);
				}
			}

			if($cfg->has('appenders')) {
				foreach($cfg->get('appenders') as $appender) {
					$name = $appender->getAttribute('name');
					if(!isset($appenders[$name])) {
						$appenders[$name] = array('class' => null, 'layout' => null, 'params' => array());
					}
					$appenders[$name]['class'] = $appender->hasAttribute('class') ? $appender->getAttribute('class') : $appenders[$name]['class'];
					$appenders[$name]['layout'] = $appender->hasAttribute('layout') ? $appender->getAttribute('layout') : $appenders[$name]['layout'];

					$appenders[$name]['params'] = $appender->getAgaviParameters($appenders[$name]['params']);
				}
			}

			if($cfg->has('layouts')) {
				foreach($cfg->get('layouts') as $layout) {
					$name = $layout->getAttribute('name');
					if(!isset($layouts[$name])) {
						$layouts[$name] = array('class' => null, 'params' => array());
					}

					$layouts[$name]['class'] = $layout->hasAttribute('class') ? $layout->getAttribute('class') : $layouts[$name]['class'];
					$layouts[$name]['params'] = $layout->getAgaviParameters($layouts[$name]['params']);
				}
			}

			if($cfg->has('loggers')) {
				$defaultLogger = $cfg->getChild('loggers')->getAttribute('default');
				if(!isset($loggers[$defaultLogger])) {
					throw new AgaviConfigurationException(sprintf('Logger "%s" is configured as default, but does not exist.', $defaultLogger));
				}
			}
		}

		if(count($loggers) > 0) {
			foreach($layouts as $name => $layout) {
				$code[] = sprintf('${%s} = new %s();', var_export('_layout_' . $name, true), $layout['class']);
				$code[] = sprintf('${%s}->initialize($this->context, %s);', var_export('_layout_' . $name, true), var_export($layout['params'], true));
			}

			foreach($appenders as $name => $appender) {
				$code[] = sprintf('${%s} = new %s();', var_export('_appender_' . $name, true), $appender['class']);
				$code[] = sprintf('${%s}->initialize($this->context, %s);', var_export('_appender_' . $name, true), var_export($appender['params'], true));
				$code[] = sprintf('${%s}->setLayout(${%s});', var_export('_appender_' . $name, true), var_export('_layout_' . $appender['layout'], true));
			}

			foreach($loggers as $name => $logger) {
				$code[] = sprintf('${%s} = new %s();', var_export('_logger_' . $name, true), $logger['class']);
				foreach($logger['appenders'] as $appender) {
					$code[] = sprintf('${%s}->setAppender(%s, ${%s});', var_export('_logger_' . $name, true), var_export($appender, true), var_export('_appender_' . $appender, true));
				}
				if($logger['level'] !== null) {
					$code[] = sprintf('${%s}->setLevel(%s);', var_export('_logger_' . $name, true), $logger['level']);
				}
				$code[] = sprintf('$this->setLogger(%s, ${%s});', var_export($name, true), var_export('_logger_' . $name, true));
			}
			$code[] = sprintf('$this->setDefaultLoggerName(%s);', var_export($defaultLogger, true));
		}

		return $this->generate($code, $document->documentURI);
	}
}

?>