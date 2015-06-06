<?php

namespace Webit\Bundle\ShipmentBundle\Features\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Tools\SchemaValidator;
use Webit\Tests\Behaviour\Bundle\BundleConfigurationContext;

/**
 * Defines application features from the specific context.
 */
class ConfigurationContext extends BundleConfigurationContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

        parent::__construct(new AppKernel());
    }

    /**
     * @Then there should be valid ORM mapping
     */
    public function thereShouldBeValidOrmMapping()
    {
        /** @var EntityManager $doctrine */
        $em = $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
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
}
