<?php

namespace OroCRM\Bundle\ReportBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\EntityConfigBundle\Entity\OptionSet;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;

class LoadLeadSourceData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var array
     */
    protected $data = [
        'Website' => false,
        'Advertising' => false,
        'Blogging' => false,
        'Media' => false,
        'Outbound' => false,
        'Partner' => false
    ];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var ConfigManager $configManager */
        $configManager = $this->container->get('oro_entity_config.config_manager');

        $configFieldModel = $configManager->getConfigFieldModel(
            'OroCRM\Bundle\SalesBundle\Entity\Lead',
            'extend_source'
        );

        $priority = 1;
        foreach ($this->data as $optionSetLabel => $isDefault) {
            $priority++;
            $optionSet = new OptionSet();
            $optionSet
                ->setLabel($optionSetLabel)
                ->setIsDefault($isDefault)
                ->setPriority($priority)
                ->setField($configFieldModel);

            $manager->persist($optionSet);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 290;
    }
}
