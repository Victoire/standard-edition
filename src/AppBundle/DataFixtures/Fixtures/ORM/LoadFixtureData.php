<?php

namespace AppBundle\DataFixtures\Fixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use AppBundle\DataFixtures\Seeds\ORM\LoadFixtureData as SeedsLoadFixturesData;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Load fixtures
 */
class LoadFixtureData extends SeedsLoadFixturesData implements ContainerAwareInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        // Load seeds and fixtures
        $this->files = $this->loadFiles();

        $manager = $this->container->get('doctrine.orm.entity_manager');

        Fixtures::load($this->files, $manager, array(
            'providers'    => [
                $this
            ],
            'locale'       => 'fr_FR',
            'persist_once' => true,
        ));
        $manager->flush();
    }

    protected function loadFiles()
    {
        $files = parent::loadFiles();
        //Add your own fixtures files here
        //$files['someBusinessFixture'] = $this->fileLocator->locate('@AppBundle/DataFixtures/Fixtures/ORM/someBusinessFixture.yml');

        return $files;
    }
}
