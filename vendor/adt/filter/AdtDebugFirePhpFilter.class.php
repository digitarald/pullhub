<?php

/**
 * AdtDebugFirePhpFilter renders AdtDebugFilter's log using
 * FirePHP
 *
 * @author     Veikko Mäkinen <veikko@veikko.fi>
 * @author     Harald Kirschner <mail@digitarald.de>
 * @copyright  Authors
 * @version    $Id$
 */
class AdtDebugFirePhpFilter extends AdtDebugFilter
{

	/**
	 * Combine and normalize two arrays to be used as table output
	 * for FirePHP
	 *
	 * @param      array $headers Simple array with table header values
	 * @param      array $data Table rows. 
	 * @param      boolean $dataAsKeyValuePairs Whether or not to treat the data array as 
	 *             key-value pairs or normal rows (in which case only array values are used)
	 * 
	 */
	protected function firePhpTable(array $headers, array $data, $dataAsKeyValuePairs=false)
	{
		$result = array($headers);
		foreach($data as $key => $value) {
			if ($dataAsKeyValuePairs) {
				$result[] = array($key, $value);
			}
			else {
				$result[] = array_values($value);
			}
		}

		return $result;
	}
	
	public function render(AgaviExecutionContainer $container)
	{
		$firephp = AdtFirePhp::getInstance(true);
		$firephp->setContext($this->context);
		$firephp->setOptions(array(
			'includeLineNumbers' => false,
			'maxObjectDepth' => 1
		));
		
		if (!$this->isAllowedOutputType($container)) {
			return;
		}

		$template = $this->rq->getAttributeNamespace(AdtDebugFilter::NS_DATA);

		$table = array(array('Name', 'Regexp', 'Matches'));
		foreach($template['routes'] as $routeName => $routeInfo) {
			$table[] = array($routeName, $routeInfo['opt']['reverseStr'], $routeInfo['matches']);
		}
		$firephp->table('Matched Routes', $table);

		$firephp->group('Request Data');
		
		foreach($template['request_data'] as $title => $data) {
			$title = ucwords(str_replace('_', ' ', $title));
			$firephp->table(
				sprintf($title.' (%s)', count($data)),
				$this->firePhpTable(array('Name', 'Value'), $data, true)
			);
		}
		
		$firephp->groupEnd(); //req data

		if (isset($template['actions'])) {
			$firephp->group('Actions');
	
			foreach($template['actions'] as $action) {
				$firephp->group($action['module'] .'.'.$action['name']);
				if ($action['validation']['has_errors']) {
					$firephp->error('Has Validation Errors');
				} else {
					$firephp->log('No Validation Errors');
				}
	
				$map = $action['validation']['incidents'];
				if (count($map)) {
					$table = array(array('Name', 'Severity', 'Fields'));
					foreach($action['validation']['incidents'] as $incident) { /* @var $incident AgaviValidationIncident */
						$table[] = array(
							$incident->getValidator() ? $incident->getValidator()->getName() : '(no validator)',
							$action['validation']['severities'][$incident->getSeverity()],
							implode(', ', $incident->getFields())
						);
					}
					$firephp->table(sprintf('Validation Incidents (%s)', count($map)), $table);
				} else {
					$firephp->log('No Validation Incidents');
				}
	
				$firephp->group('Request Data (from execution container)');
	
				foreach($action['request_data'] as $title => $data) {
					$title = ucwords(str_replace('_', ' ', $title));
					$firephp->table(
						sprintf($title.' (%s)', count($data)),
						$this->firePhpTable(array('Name', 'Value'), $data, true)
					);
				}
				$firephp->groupEnd(); //req data
				$firephp->groupEnd(); //action
			} //action
			$firephp->groupEnd(); //actions
		} // actions

		if (!empty($template['log'])) {
			$firephp->table(
				sprintf('Debug Log (%s)', count($template['log'])), 
				$this->firePhpTable(array('Microtime', 'Message'), $template['log'])
			);
		}

		foreach($this->rq->getAttribute('datasources', AdtDebugFilter::NS, array()) as $datasource) { /* @var $datasource AdtDebugFilterDataSource */
			switch ($datasource->getDataType()) {
				case AdtDebugFilterDataSource::TYPE_KEYVALUE:
				break;
				case AdtDebugFilterDataSource::TYPE_LINEAR:
				break;
				case AdtDebugFilterDataSource::TYPE_TABULAR:
					$data = $datasource->getData();
					$table = array($data['headers']);
					foreach($data['rows'] as $row)
						$table[] = $row;
					$firephp->table($datasource->getName(), $table);
				break;
			}
		}

	}

}
?>