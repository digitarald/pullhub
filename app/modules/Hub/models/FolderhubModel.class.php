<?php

class Hub_FolderhubModel extends PullHubHubBaseModel
{

	protected $root = null;

	protected $alias = null;

	public function initialize($context, $parameters)
	{
		parent::initialize($context, $parameters);

		if (!isset($parameters['root'])) {
			throw new Exception('Parameter "root" is required');
		}

		$this->alias = $parameters['alias'];

		$this->root = $parameters['root'];
	}

	public function isRepo($repo)
	{
		$path = $this->root . '/' . $repo;

		if (!is_dir($path)) {
			return false;
		}

		if (!is_readable($path . '/manifest.yml')) {
			return false;
		}

		return true;
	}

	public function getRepos()
	{
		$results = array();

		$read = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->root));

    foreach ($read as $file) {

    	$name = $file->getFilename();

			if ($name == 'manifest.yml') {
				$result = array(
					'path' => $file->getPath(),
					'owner' => $this->alias,
					'name' => basename($file->getPath())
				);

				$result['manifest'] = sfYaml::load($file->__toString());

				$results[] = $result;
			}
		}

		return $results;
	}

	public function getRepo($name)
	{
		$found = false;

		foreach ($this->getRepos() as $repo) {
			if ($repo['name'] == $name) {
				$found = true;
				break;
			}
		}

		if (!$found) {
			return null;
		}

		$read = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($repo['path']), RecursiveIteratorIterator::SELF_FIRST);

		$tree = array();

		$last_depth = -1;

		foreach ($read as $file) {
			$depth = $read->getDepth();

			$filename = $file->getFilename();

			if ($depth - 1 > $last_depth) {
				continue;
			}

			$is_dir = $file->isDir();
			$size = $file->getSize();

			if (substr($filename, 0, 1) == '.' || (!$is_dir && !$size)) {
				continue;
			}

			$depth = $read->getDepth();

			$path = array();
			if ($depth) {
				$path = explode('/', str_replace('\\', '/', $read->getSubPath()) );
			}
			$path[] = $filename;

			$type = 'blob';
			if ($is_dir) {
				$type = 'tree';
			}

			$last_depth = $depth;

			$tree[join('/', $path)] = array(
				'name' => $filename,
				'path' => $path,
				'size' => $size,
				'depth' => $read->getDepth(),
				'type' => $type
			);
		}

		$repo['tree'] = $tree;

		return $repo;
	}

	public function expandManifest(&$repo)
	{
		foreach ($repo['tree'] as $path => &$file) {
			if ($file['type'] == 'tree' || $file['nature'] != 'source') {
				continue;
			}

			$file['data'] = file_get_contents($repo['path'] . '/' . $path);

			if (preg_match('/^\\/\*+=(.*?)\n\*+\//s', $file['data'], $m)) {
				$file['manifest'] = sfYaml::load(trim($m[1]));
			}
		}
	}

}

?>