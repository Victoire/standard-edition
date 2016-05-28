<?php

namespace AppBundle\DataFixtures\Seeds\ORM;

use AppVentus\OssusBundle\Provider\OssusProvider;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Load fixtures.
 */
class LoadFixtureData extends AbstractFixture implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    protected $container;
    protected $fileLocator;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->fileLocator = $this->container->get('file_locator');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        // Load fixtures files
        $files = $this->loadFiles();
        Fixtures::load($files, $manager, array(
            'providers'    => array(
                $this
            ),
            'locale'       => 'fr_FR',
            'persist_once' => false,
        ));

        $manager->flush();
    }

    protected function loadFiles()
    {
        $files['user'] = $this->fileLocator->locate('@AppBundle/DataFixtures/Seeds/ORM/User/user.yml');
        $files['folder'] = $this->fileLocator->locate('@AppBundle/DataFixtures/Seeds/ORM/Media/folder.yml');
        $files['template'] = $this->fileLocator->locate('@AppBundle/DataFixtures/Seeds/ORM/View/template.yml');
        $files['page'] = $this->fileLocator->locate('@AppBundle/DataFixtures/Seeds/ORM/View/page.yml');
        $files['errorPage'] = $this->fileLocator->locate('@AppBundle/DataFixtures/Seeds/ORM/View/errorPage.yml');

        return $files;
    }

    /**
     * Remove all files from given folder.
     *
     * @param string $folder Path of the folder to clear.
     *
     * @return void
     */
    public function clearFolder($folder)
    {
        if (is_dir($folder)) {
            // Open folder
        $openFolder = opendir($folder);

        // While folder is not empty
        while ($file = readdir($openFolder)) {
            if ($file != '.' && $file != '..') {
                // Remove file
            $recursiveDelete = function ($str) use (&$recursiveDelete) {
            if (is_file($str)) {
                return @unlink($str);
            } elseif (is_dir($str)) {
                $scan = glob(rtrim($str, '/').'/*');
                foreach ($scan as $path) {
                    $recursiveDelete($path);
                }

                return @rmdir($str);
            }
            };
                $recursiveDelete($folder.$file);
            }
        }

        // Close empty folder
        closedir($openFolder);
        }
    }
}
