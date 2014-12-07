<?php
/**
 * File AppKernel.php
 * Created at: 2014-12-06 19-16
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

class AppKernel  extends Kernel
{

    /**
     * @var string
     */
    private $hash;

    private $configMerge = array();

    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);
        $this->hash = $this->generateHash();
    }

    /**
     * @return string
     */
    private function generateHash()
    {
        return md5(microtime().mt_rand(0, 1000000));
    }

    public function __clone()
    {
        parent::__clone();
        $this->hash = $this->generateHash();
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances.
     *
     * @api
     */
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Webit\Bundle\ShipmentBundle\WebitShipmentBundle()
        );

        return $bundles;
    }

    /**
     * Loads the container configuration.
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     *
     * @api
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $config = file_get_contents(__DIR__.'/config/config.yml');
        if ($this->configMerge) {
            $config = Yaml::parse($config);
            foreach ($this->configMerge as $configToMerge) {
                $config = array_replace($config, Yaml::parse($configToMerge));
            }
            $config = Yaml::dump($config);
        }

        file_put_contents($this->getCacheDir() .'/config.yml', $config);

        $loader->load($this->getCacheDir() .'/config.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        $dir = sys_get_temp_dir() . '/' . $this->hash . '/cache';
        @mkdir($dir, 755, true);

        return $dir;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getLogDir()
    {
        return $this->getCacheDir().'/logs';
    }

    public function mergeConfig($configYml)
    {
        $this->configMerge[] = $configYml;
    }

    /**
     * Gets the container class.
     *
     * @return string The container class
     */
    protected function getContainerClass()
    {
        return $this->name.ucfirst($this->environment).($this->debug ? 'Debug' : '').$this->hash.'ProjectContainer';
    }
}