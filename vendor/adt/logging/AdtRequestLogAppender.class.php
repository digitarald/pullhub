<?php

/**
 * AdtRequestLogAppender is a simple logger appender
 * that stores the log lines in AgaviRequest
 * 
 * It was built to be used with AdtDebugFilter and its descendants 
 * that can render the log.
 *
 */
class AdtRequestLogAppender extends AgaviLoggerAppender
{

	public function write($message)
	{
		if(($layout = $this->getLayout()) === null) {
			throw new AgaviLoggingException('No Layout set');
		}
		$this->context->getRequest()->appendAttribute(
			'log',
			array(
				'microtime' => microtime(true), 
				'message' => $this->getLayout()->format($message)
			),
			AdtDebugFilter::NS_DATA
		);
	}
	
	public function shutdown() 
	{
		//
	}
}

?>