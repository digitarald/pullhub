<?php

class Hub_IndexErrorView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->container->createSystemActionForwardContainer('error_404', new AgaviException('Invalid search'));
	}
}

?>