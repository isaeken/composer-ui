<?php


namespace IsaEken\ComposerUI;


use Composer\Composer;
use Composer\Config;
use Composer\Console\Application;
use Composer\Installer as ComposerInstaller;
use Composer\IO\IOInterface;
use Composer\Json\JsonValidationException;
use Composer\Util\HttpDownloader;

class Installer
{
    /** @var Installer $instance */
    private static Installer $instance;

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (! isset(static::$instance)) {
            static::$instance = new Installer;
        }

        return static::$instance;
    }

    /** @var IOInterface $io */
    public IOInterface $io;

    /** @var Application $application */
    public Application $application;

    /** @var Composer|null $composer */
    public Composer|null $composer;

    /** @var ComposerInstaller $installer */
    public ComposerInstaller $installer;

    /** @var Config $config */
    public Config $config;

    /**
     * @var array $options
     */
    public array $options = [
        'dry_run' => false,
        'verbose' => false,
        'prefer_source' => false,
        'prefer_dist' => true,
        'dev_mode' => false,
        'dump_autoloader' => false,
        'optimize_autoloader' => true,
        'class_map_authoritative' => false,
        'apcu_autoloader' => false,
        'ignore_platform_requirements' => false,
    ];

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }

        return $this->$name;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value): void
    {
        if (array_key_exists($name, $this->options)) {
            $this->options[$name] = $value;
        }

        $this->$name = $value;
    }

    /**
     * @return Installer
     */
    public function setIniForHttp(): self
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('max_input_time', 0);
        ini_set('memory_limit', -1);
        return $this;
    }

    /**
     * @param array $options
     * @return bool|string
     * @throws JsonValidationException
     */
    public function run(array $options = []): bool|string
    {
        $opts = $this->options;
        foreach ($options as $key => $value) {
            $opts[$key] = $value;
        }

        ob_start();

        $this->io = new IO;
        $this->application = new Application;
        $this->composer = $this->application->getComposer(true);

        if ((! $this->composer || ! $this->composer->getLocker()->isLocked()) && ! HttpDownloader::isCurlEnabled()) {
            $this->io->writeError('<warning>Composer is operating significantly slower than normal because you do not have the PHP curl extension enabled.</warning>');
        }

        $this->installer = ComposerInstaller::create($this->io, $this->composer);
        $this->config = $this->composer->getConfig();

        $this->installer
            ->setDryRun($opts['dry_run'])
            ->setVerbose($opts['verbose'])
            ->setPreferSource($opts['prefer_source'])
            ->setPreferDist($opts['prefer_dist'])
            ->setDevMode($opts['dev_mode'])
            ->setDumpAutoloader($opts['dump_autoloader'])
            ->setOptimizeAutoloader($opts['optimize_autoloader'])
            ->setClassMapAuthoritative($opts['class_map_authoritative'])
            ->setApcuAutoloader($opts['apcu_autoloader'])
            ->setIgnorePlatformRequirements($opts['ignore_platform_requirements'])
        ;

        $this->installer->run();
        return ob_get_clean();
    }
}
