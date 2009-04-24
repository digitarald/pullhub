<?php

class Default_IndexSuccessView extends PullHubDefaultBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->getResponse()->setRedirect($this->context->getRouting()->gen('hub.index'));
	}
}

?>