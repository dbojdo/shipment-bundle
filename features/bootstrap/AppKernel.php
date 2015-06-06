<?php
/**
 * File AppKernel.php
 * Created at: 2015-05-17 09-42
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Bundle\ShipmentBundle\Features\Bootstrap;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Webit\Bundle\ShipmentBundle\WebitShipmentBundle;
use Webit\Tests\Behaviour\Bundle\Kernel;

class AppKernel extends Kernel
{
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
            new FrameworkBundle(),
            new WebitShipmentBundle(),
            new JMSSerializerBundle(),
            new DoctrineBundle()
        );

        return $bundles;
    }
}
