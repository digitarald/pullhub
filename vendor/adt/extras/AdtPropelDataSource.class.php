<?php

class AdtPropelDataSource extends AdtDebugFilterDataSource
{

	/**
	 * Initialize this data source.
	 *
	 * @param      AgaviContext The current application context.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>AgaviInitializationException</b> If an error occurs while
	 *                                                 initializing.
	 *
	 */
	public function initialize(AgaviContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);
		$pdo = $this->context->getDatabaseConnection($this->getParameter('database_name'));

		if ($pdo instanceof DebugPDO) {
			$pdo->setLogger($this);
		}
		else {
			$this->log('NOTICE: The PDO connection might not support query logging. Refer to Propel documentation on how to enable full query logging.');
		}
		Propel::setLogger($this);
	}

	/**
	 * Return the name of this data source
	 *
	 * @return string
	 */
	public function getName()
	{
		$queries = 0;
		$pdo = $this->context->getDatabaseConnection($this->getParameter('database_name'));
		if ($pdo instanceof DebugPDO) {
			$queries = $pdo->getQueryCount();
		}
		return sprintf('Propel Query Log (%d queries)', $queries);
	}

	public function getDataType()
	{
		return AdtDebugFilterDataSource::TYPE_TABULAR;
	}

	public function getData()
	{
		$pdo = $this->context->getDatabaseConnection($this->getParameter('database_name'));
		if ($pdo instanceof DebugPDO) {
			$this->log('Number of queries executed: ' . $pdo->getQueryCount());
		}

		return array(
			'headers' => array('Microtime', 'Message'),
			'rows' => $this->getParameter('log', array())
		);
	}

	public function emergency($m)
	{
		$this->log($m, Propel::LOG_EMERG);
	}

	public function alert($m)
	{
		$this->log($m, Propel::LOG_ALERT);
	}

	public function crit($m)
	{
		$this->log($m, Propel::LOG_CRIT);
	}

	public function err($m)
	 {
		$this->log($m, Propel::LOG_ERR);
	}

	public function warning($m)
	 {
		$this->log($m, Propel::LOG_WARNING);
	}

	public function notice($m)
	 {
		$this->log($m, Propel::LOG_NOTICE);
	}

	public function info($m)
	 {
		$this->log($m, Propel::LOG_INFO);
	}

	public function debug($m)
	{
		$this->log($m, Propel::LOG_DEBUG);
	}

	public function log($m, $priority=null)
	{
		$this->appendParameter('log', array(microtime(true), $m));
	}

}

?>