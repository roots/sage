<?php
namespace App\SageWoo;

use Roots\Sage\Installer\ComposerScript;
use Symfony\Component\Process\Process;

class SageInstallerMod extends ComposerScript{
	public static function postCreateProjectMod($event = null)
	{
		$sage = dirname(__DIR__, 2).'/vendor/roots/sage-installer/bin/sage';
		(new static($event))
			->validate()
			->run(new Process(sprintf('php %s %s', $sage, 'meta')))
			->run(new Process(sprintf('php %s %s', $sage, 'config')));
	}
}