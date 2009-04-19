<?php

class Default_SecureSuccessView extends PullHubDefaultBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setAttribute('_title', 'Access Denied');
		
		$this->setupHtml($rd);
		
		$this->getResponse()->setHttpStatusCode('403');
	}
}

?>
