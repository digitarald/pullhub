<?php

class Hub_PackageModel extends PullHubHubBaseModel
{

	public function initialize($context, $parameters)
	{
		parent::initialize($context, $parameters);
	}

	public function getRepos($user = null)
	{
		foreach (AgaviConfig::get('hub.user_alias', array()) as $alias => $root) {
			if (!is_dir($root)) {
				continue;
			}

			if ($user !== null && $user != $alias) {
				continue;
			}

			$model = $this->context->getModel('Folderhub', 'Hub', array('root' => $root, 'alias' => $alias));

			$results = array_merge($results, $model->getRepos());
		}

		$model = $this->context->getModel('Github', 'Hub');

		if (!$user) {
			return array_merge($results, $model->getRepos());
		}

		return array_merge($results, $model->getRepos($user));
	}

	public function getRepo($user, $repo, $tree = null)
	{
		$alias = AgaviConfig::get('hub.user_alias', array());

		if (key_exists($user, $alias)) {
			$model = $this->context->getModel('Folderhub', 'Hub', array('root' => $alias[$user], 'alias' => $user));

			$result = $model->getRepo($repo);
		}

		if (!$result) {
			$model = $this->context->getModel('Github', 'Hub');

			$result = $model->getRepo($user, $repo, $tree);
		}

		if (!$result) {
			return null;
		}

		$this->expandManifest($result);

		$model->expandManifest($result);

		foreach ($result['tree'] as $path => &$file) {
			if (!isset($file['manifest'])) {
				continue;
			}

			if (isset($file['manifest']['require'])) {
				$file['manifest']['require_regex'] = $this->translateMatch($file['manifest']['require']);
			}

			if (isset($file['manifest']['provide'])) {
				$file['manifest']['provide_regex'] = $this->translateMatch($file['manifest']['provide']);
			}
		}

		return $result;
	}

	protected function expandManifest(&$repo)
	{
		if (!isset($repo['manifest'])) {
			$repo['manifest'] = array();
		}

		$manifest =& $repo['manifest'];

		if (!isset($manifest['description'])) {
			if (isset($repo['description'])) {
				$manifest['description'] = $repo['description'];
			} else {
				$manifest['description'] = $repo['name'];
			}
		}

		$nature = array();

		if (!isset($manifest['source'])) {
			if (isset($repo['tree']['Source'])) {
				$manifest['source'] = 'Source/*.js';
			} else {
				$manifest['source'] = '*.js';
			}
		}

		$nature['source'] = $this->translateMatch($manifest['source']);

		if (!isset($manifest['specs'])) {
			if (isset($repo['tree']['Specs'])) {
				$manifest['specs'] = 'Specs/*';
			} elseif (isset($repo['tree']['Specs.js'])) {
				$manifest['specs'] = 'Specs.js';
			}
		}

		if (isset($manifest['specs'])) {
			$nature['specs'] = $this->translateMatch($manifest['specs']);
		}

		if (!isset($manifest['demos'])) {
			if (isset($repo['tree']['Demos'])) {
				$manifest['demos'] = 'Demos/*';
			} elseif (isset($repo['tree']['Demos.html'])) {
				$manifest['demos'] = 'Demos.html';
			} elseif (isset($repo['tree']['Demos.js'])) {
				$manifest['demos'] = 'Demos.js';
			}
		}

		if (isset($manifest['demos'])) {
			$nature['demos'] = $this->translateMatch($manifest['demos']);
		}

		if (!isset($manifest['docs'])) {
			if (isset($repo['tree']['Docs'])) {
				$manifest['docs'] = 'Docs/*';
			} elseif (isset($repo['tree']['README'])) {
				$manifest['docs'] = 'README';
			} else {
				$manifest['docs'] = '*.md';
			}
		}

		$nature['docs'] = $this->translateMatch($manifest['docs']);

		if (!isset($manifest['assets'])) {
			if (isset($repo['tree']['Assets'])) {
				$manifest['assets'] = 'Assets/*';
			} else {
				$manifest['assets'] = '*.css, *.gif, *.png, *.jpg';
			}
		}

		if (!isset($manifest['compatibility'])) {
			if (isset($repo['tree']['Compatibility'])) {
				$manifest['compatibility'] = 'Compatibility/*.js';
			} elseif (isset($repo['tree']['Compatibility.js'])) {
				$manifest['compatibility'] = 'Compatibility.js';
			}
		}

		if (isset($manifest['compatibility'])) {
			$nature['compatibility'] = $this->translateMatch($manifest['compatibility']);
		}


		$nature['assets'] = $this->translateMatch($manifest['assets']);

		foreach ($repo['tree'] as $key => &$file) {
			$file['nature'] = null;

			foreach ($nature as $name => $preg) {

				if (!preg_match('/' . $preg . '/', $key)) {
					continue;
				}

				$file['nature'] = $name;
				break;
			}
		}
	}

	protected static function translateMatch($match)
	{
		if (!is_array($match)) {
			$match = preg_split('/\\s*,\\s*/', $match);
		}

		foreach ($match as &$path) {
			$path = explode('*', $path);

			foreach ($path as &$bit) {
				$bit = preg_quote($bit, '/');
			}

			$path = '(^|\\/)' . join('.*', $path) . '((\.[a-z0-9]{2,4})?$|\\/)';
		}
		unset($path);

		return join('|', $match);
	}

}

?>