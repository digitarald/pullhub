<?php

class Hub_Package_ViewErrorView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		return $this->container->createSystemActionForwardContainer('error_404', new AgaviException('Invalid repo'));
	}
}

?>