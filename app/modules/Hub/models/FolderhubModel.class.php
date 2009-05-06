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

	public function getRepos()
	{
		$results = array();

		$read = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->root));

    foreach ($read as $file) {

    	$name = $file->getFilename();

			if ($name == 'manifest.yml') {
				if (file_exists($file->getPath() . '/.lock')) {
					continue;
				}

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
		foreach ($this->getRepos() as $repo) {
			if ($repo['name'] == $name) {
				$this->getTree($repo);
				return $repo;
			}
		}

		return null;
	}

	public function getTree(&$repo)
	{
		$read = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($repo['path']), RecursiveIteratorIterator::SELF_FIRST);

		$tree = array();

		$last_depth = -1;

		foreach ($read as $file) {
			$depth = $read->getDepth();

			if ($depth - 1 > $last_depth) {
				continue;
			}

			$filename = $file->getFilename();

			$is_dir = $file->isDir();
			$size = $file->getSize();

			if (substr($filename, 0, 1) == '.' || (!$is_dir && !$size)) {
				continue;
			}

			$depth = $read->getDepth();
			$path = array();

			if ($depth <= 1) {
				if ($filename == 'manifest.yml' && !isset($repo['manifest'])) {
					$repo['manifest'] = sfYaml::load($file->__toString());
				}

				if ($filename == 'scripts.json' && !isset($repo['scripts'])) {
					$repo['scripts'] = json_decode(file_get_contents($file->__toString()), true);
				}
			}

			if ($depth) {
				$path = explode('/', str_replace('\\', '/', $read->getSubPath()) );
			}
			$path[] = $filename;

			$type = 'blob';
			if ($is_dir) {
				$type = 'tree';
			}

			$last_depth = $depth;

			$tree[$repo['name'] . ':' . join('/', $path)] = array(
				'name' => $filename,
				'path' => join('/', $path),
				'paths' => $path,
				'size' => $size,
				'depth' => $read->getDepth(),
				'type' => $type
			);
		}

		$repo['tree'] = $tree;
	}

	public function expandManifest(&$repo)
	{
		foreach ($repo['tree'] as &$file) {
			if ($file['type'] == 'tree' || $file['nature'] != 'source') {
				continue;
			}

			$file['data'] = file_get_contents($repo['path'] . '/' . $file['path']);

			if (preg_match('/^\\/\*+=(.*?)\n\*+\//s', $file['data'], $m)) {
				$file['manifest'] = sfYaml::load(trim($m[1]));
			}
		}
	}

}

?>