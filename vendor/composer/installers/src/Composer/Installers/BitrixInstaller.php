<?php
namespace Composer\Installers;

use Composer\Util\Filesystem;

/**
 * Installer for Bitrix Framework
 *
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 * @author Denis Kulichkin <onexhovia@gmail.com>
 */
class BitrixInstaller extends BaseInstaller
{
    protected $locations = array(
        'module'    => 'bitrix/modules/{$name}/',
        'component' => 'bitrix/components/{$name}/',
        'theme'     => 'bitrix/templates/{$name}/',
    );

    /**
     * @var array Storage for informations about duplicates at all the time of installation packages
     */
    private static $checkedDuplicates = array();

    /**
     * {@inheritdoc}
     */
    protected function templatePath($path, array $vars = array())
    {
        $templatePath = parent::templatePath($path, $vars);
        $this->checkDuplicates($templatePath, $vars);

        return $templatePath;
    }

    /**
     * Duplicates search packages
     *
     * @param string $templatePath
     * @param array $vars
     */
    protected function checkDuplicates($templatePath, array $vars = array())
    {
        /**
         * Incorrect paths for backward compatibility
         */
        $oldLocations = array(
            'module'    => 'local/modules/{$name}/',
            'component' => 'local/components/{$name}/',
            'theme'     => 'local/templates/{$name}/'
        );

        $packageType = substr($vars['type'], strlen('bitrix') + 1);
        $oldLocation = str_replace('{$name}', $vars['name'], $oldLocations[$packageType]);

        if (in_array($oldLocation, static::$checkedDuplicates)) {
            return;
        }

        if ($oldLocation !== $templatePath && file_exists($oldLocation) && $this->io && $this->io->isInteractive()) {

            $this->io->writeError('    <error>Duplication of packages:</error>');
            $this->io->writeError('    <info>Package ' . $oldLocation . ' will be called instead package ' . $templatePath . '</info>');

            while (true) {
                switch ($this->io->ask('    <info>Delete ' . $oldLocation . ' [y,n,?]?</info> ', '?')) {
                    case 'y':
                        $fs = new Filesystem();
                        $fs->removeDirectory($oldLocation);
                        break 2;

                    case 'n':
                        break 2;

                    case '?':
                    default:
                        $this->io->writeError(array(
                            '    y - delete package ' . $oldLocation . ' and to continue with the installation',
                            '    n - don\'t delete and to continue with the installation',
                        ));
                        $this->io->writeError('    ? - print help');
                        break;
                }
            }
        }

        static::$checkedDuplicates[] = $oldLocation;
    }
}
