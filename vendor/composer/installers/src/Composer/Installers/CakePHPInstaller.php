<?php
namespace Composer\Installers;

use Composer\DependencyResolver\Pool;
use Composer\Package\PackageInterface;

class CakePHPInstaller extends BaseInstaller
{
    protected $locations = array(
        'plugin' => 'Plugin/{$name}/',
    );

    /**
     * Format package name to CamelCase
     */
    public function inflectPackageVars($vars)
    {
        if ($this->matchesCakeVersion('>=', '3.0.0')) {
            return $vars;
        }

        $nameParts = explode('/', $vars['name']);
        foreach ($nameParts as &$value) {
            $value = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $value));
            $value = str_replace(array('-', '_'), ' ', $value);
            $value = str_replace(' ', '', ucwords($value));
        }
        $vars['name'] = implode('/', $nameParts);

        return $vars;
    }

    /**
     * Change the default plugin location when cakephp >= 3.0
     */
    public function getLocations()
    {
        if ($this->matchesCakeVersion('>=', '3.0.0')) {
            $this->locations['plugin'] =  $this->composer->getConfig()->get('vendor-dir') . '/{$vendor}/{$name}/';
        }
        return $this->locations;
    }

    /**
     * Check if CakePHP version matches against a version
     *
     * @param string $matcher
     * @param string $version
     * @return bool
     */
    protected function matchesCakeVersion($matcher, $version)
    {
        if (class_exists('Composer\Semver\Constraint\MultiConstraint')) {
            $multiClass = 'Composer\Semver\Constraint\MultiConstraint';
            $constraintClass = 'Composer\Semver\Constraint\Constraint';
        } else {
            $multiClass = 'Composer\Package\LinkConstraint\MultiConstraint';
            $constraintClass = 'Composer\Package\LinkConstraint\VersionConstraint';
        }

        $repositoryManager = $this->composer->getRepositoryManager();
        if ($repositoryManager) {
            $repos = $repositoryManager->getLocalRepository();
            if (!$repos) {
                return false;
            }
            $cake3 = new $multiClass(array(
                new $constraintClass($matcher, $version),
                new $constraintClass('!=', '9999999-dev'),
            ));
            $pool = new Pool('dev');
            $pool->addRepository($repos);
            $packages = $pool->whatProvides('cakephp/cakephp');
            foreach ($packages as $package) {
                $installed = new $constraintClass('=', $package->getVersion());
                if ($cake3->matches($installed)) {
                    return true;
                    break;
                }
            }
        }
        return false;
    }
}
