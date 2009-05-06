<?php

class Hub_IndexSuccessView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$contentLayer = $this->getLayer('content');

		foreach ($this->getAttribute('repos') as $key => $repo) {
			$slot = $this->createSlotContainer('Hub', 'Package.View', array('user' => $repo['owner'], 'repo' => $repo['name']));
			$contentLayer->setSlot('repo-' . $key, $slot);
		}

		$this->setAttribute('_title', 'Index');
	}
}

?>