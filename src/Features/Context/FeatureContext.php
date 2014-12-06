<?php

namespace Webit\Bundle\ShipmentBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use PHPUnit_Framework_Assert as Assert;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class FeatureContext
 * @package Webit\Bundle\GlsBundle\Features\Context
 */
class FeatureContext implements Context, SnippetAcceptingContext, KernelAwareContext
{
    /**
     * @var AppKernel
     */
    private $kernel;

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private function getContainer()
    {
        $this->kernel->boot();
        return $this->kernel->getContainer();
    }

    /**
     * @When application config contains:
     * @param PyStringNode $string
     * @throws PendingException
     */
    public function applicationConfigContains(PyStringNode $string)
    {
        $this->kernel = clone($this->kernel);
        $this->kernel->mergeConfig($string->getRaw());
    }

    /**
     * @When application is up
     */
    public function applicationIsUp()
    {
        $this->kernel->boot();
    }

    /**
     * @Then There should be following services in container:
     * @param PyStringNode $string
     */
    public function thereShouldBeFollowingServicesInContainer(PyStringNode $string)
    {
        foreach ($string->getStrings() as $line) {
            $arServices = explode(',', $line);
            foreach ($arServices as $serviceName) {
                $serviceName = trim($serviceName);
                if (empty($serviceName)) {continue;}
                Assert::assertTrue(
                    $this->getContainer()->has($serviceName),
                    sprintf('Required service "%s" has not been registered in Container', $serviceName)
                );

                try {
                    $this->getContainer()->get($serviceName);
                } catch (\Exception $e) {
                    throw new \Exception(sprintf('Error during getting "%s" from container: %s %s', $serviceName, $e->getMessage(), $e->getTraceAsString()), null, $e);
                }
            }
        }
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
