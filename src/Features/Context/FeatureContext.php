<?php

namespace Webit\Bundle\ShipmentBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Bundle\DoctrineBundle\ManagerConfigurator;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaValidator;
use Metadata\Driver\FileLocator;
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
     * @Then there should be valid mapping
     */
    public function thereShouldBeValidMapping()
    {
        /** @var EntityManager $doctrine */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $validator = new SchemaValidator($em);
        $errors = $validator->validateMapping();

        if ($errors) {
            $arMsg = array();
            foreach ($errors as $entity => $entityErrors) {
                $arMsg[] = sprintf("%s:\n", $entity) . implode("\n", $entityErrors);
            }

            throw new \LogicException("Invalid mappings: \n". implode("\n", $arMsg));
        }
    }

    /**
     * @Then there should be valid metadata directory for namespace :arg1
     */
    public function thereShouldBeValidMetadataDirectoryForNamespace($namespace)
    {
        /** @var FileLocator $locator */
        $locator = $this->getContainer()->get('jms_serializer.metadata.file_locator');
        $dirs = $locator->getDirs();
        die(var_dump($dirs));
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
