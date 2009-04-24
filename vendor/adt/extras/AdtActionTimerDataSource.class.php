<?php

/**
 * AdtActionTimerDataSource keeps track of action execution times.
 * Because actions are executed recursively execution times logged by
 * this data source are cumulative i.e. the top most action's execution
 * time includes all inner execution times.
 *
 * @author     Veikko Mäkinen <veikko@veikko.fi>
 */
class AdtActionTimerDataSource extends AdtDebugFilterDataSource
{


	protected $recursionLevel = 0;

	protected $counters = array();

	protected $log = array();

	/**
	 * Return the name of this data source
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'Action Timer';
	}

	public function getDataType()
	{
		return AdtDebugFilterDataSource::TYPE_TABULAR;
	}

	/**
	 * Returns the collected data
	 *
	 * @return array
	 */
	public function getData()
	{
		return array(
			'headers' => array('Action', 'Execution Time (Cumulative)'),
			'rows' => $this->log
		);
	}

	public function beforeExecute(AgaviExecutionContainer $container)
	{
		$this->recursionLevel++;
		$this->counters[$container->getActionName().$container->getMicrotime()] = microtime(true);
	}

	public function afterExecute(AgaviExecutionContainer $container)
	{
		$start = $this->counters[$container->getActionName().$container->getMicrotime()];
		$duration = microtime(true) - $start;
		array_unshift($this->log, array(str_repeat(' - ', $this->recursionLevel-1).$container->getModuleName().'.'.$container->getActionName(), $duration));
		$this->recursionLevel--;
	}

}

?>