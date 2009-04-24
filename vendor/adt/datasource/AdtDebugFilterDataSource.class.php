<?php

/**
 *
 * @author     Veikko Mäkinen <veikko@veikko.fi>
 */
abstract class AdtDebugFilterDataSource extends AgaviParameterHolder
{
	/**
	 * Data source returns table data as an array.
	 * Array has to inner arrays - 'headers' and 'rows'.
	 *
	 */
	const TYPE_TABULAR = 1;

	/**
	 * Data source returns key=>value array.
	 *
	 */
	const TYPE_KEYVALUE = 2;

	/**
	 * Data source returns logged data as a simple array.
	 *
	 */
	const TYPE_LINEAR = 3;

	/**
	 * @var        AgaviContext An AgaviContext instance.
	 */
	protected $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     AgaviContext The current Context instance.
	 */
	public final function getContext()
	{
		return $this->context;
	}

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
		$this->context = $context;

		$this->setParameters($parameters);
	}

	/**
	 * Returns the name of this data source
	 *
	 * @return     string
	 */
	public function getName()
	{
		return get_class($this);
	}

	/**
	 * Returns the data from this data source
	 *
	 * Data is returned as an array but the structure of the
	 * array depends on data source's type. See getDataType.
	 *
	 * @return     array
	 */
	abstract public function getData();

	/**
	 * Returns data source data type. See class constants for details.
	 *
	 * Possible result values:
	 * - AdtDebugFilterDataSource::TYPE_TABULAR
	 * - AdtDebugFilterDataSource::TYPE_KEYVALUE
	 * - AdtDebugFilterDataSource::TYPE_LINEAR
	 *
	 * @return     int
	 */
	abstract public function getDataType();


	/**
	 * @param      AgaviExecutionContainer $container
	 */
	public function beforeExecuteOnce(AgaviExecutionContainer $container)
	{

	}

	/**
	 * @param      AgaviExecutionContainer $container
	 */
	public function afterExecuteOnce(AgaviExecutionContainer $container)
	{

	}

	/**
	 * @param      AgaviExecutionContainer $container
	 */
	public function beforeExecute(AgaviExecutionContainer $container)
	{

	}

	/**
	 * @param      AgaviExecutionContainer $container
	 */
	public function afterExecute(AgaviExecutionContainer $container)
	{

	}
}

?>