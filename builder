#!/usr/bin/php

<?php

class ProjectBuilder
{
	private $scenario = [];
	private $args = [];
	private $result = [];

	public function __construct($args)
	{
		$this->args = $args;
	}

	public function applyToAllRepo($cmd, $params = '')
	{
		$path = 'vendor/follower/';
		exec('cd ' . $path . ' && ls', $projects);

		array_unshift($projects, '');

		foreach ($projects as $project) {
			if ($cmd != 'add') { //silent add
				echo PHP_EOL;
				echo '+------------------------------' . PHP_EOL;
				echo '| ' . ($project ? 'follower/' . $project : 'main'). PHP_EOL;
				echo '+------------------------------' . PHP_EOL;
				echo $this->runCmd($path . $project, $cmd, $params);
			}
		}
	}

	public function execute()
	{
		switch ($this->args[1]) {
			case 'status' :
				$this->applyToAllRepo($this->args[1]);
				break;

			case 'reset' :
				$this->applyToAllRepo($this->args[1], $this->args[2]);
				break;

			case 'push' :
			case 'pull' :
				$this->applyToAllRepo($this->args[1], $this->args[2] . ' ' . $this->args[3]);
				break;

			case 'commit' :
				if (isset($this->args[2])) {
					$this->applyToAllRepo('add', '.');
					$this->applyToAllRepo('commit -am \'' . $this->args[2] . '\'');
				} else {
					$this->result[] = 'Enter commit reason';
				}
				break;

			case 'help' :
			default :
				$this->result = [
					'Project Builder v 1.0 Help:',
					' * commit \'commit reason\' - Updates untracked files in repository and commits changes to current branch',
					' * status - \'git status\' output',
					' * push - \'git push\' command',
					' * pull - \'git pull\' command',
				];
				break;
		}

		$this->resultOutput();
	}

	private function runCmd($path, $command, $params = '')
	{
		if ($path) {
			$cd = 'cd ' . $path . ' && ';
		}
		exec($cd . 'git ' . $command . ' ' . $params, $result);
		return implode("\n", $result) . PHP_EOL;
	}

	private function resultOutput()
	{
		array_walk($this->result, function ($line) {
			echo $line . PHP_EOL;
		});
	}
}

$builder = new ProjectBuilder($argv);
$builder->execute();
