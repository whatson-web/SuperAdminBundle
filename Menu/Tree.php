<?php

namespace WH\SuperAdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Tree
 *
 * @package WH\SuperAdminBundle\Menu
 */
class Tree implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    private $config = array();

    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function tree(FactoryInterface $factory, array $options = array())
    {

        $baseController = $this->container->get('bk.wh.back.base_controller');

        $entityRepository = $this->container->get('doctrine')->getRepository(
            $baseController->getRepositoryName($options['entityPathConfig'])
        );

        $indexController = $this->container->get('bk.wh.back.index_controller');

        $this->config = $indexController->getConfig(
            $options['entityPathConfig'],
            'index'
        );

        $menu = $factory->createItem(
            'root'
        );

        $entities = $entityRepository->get(
            'all'
        );

        $menu->addChild(
            'root',
            array(
                'label' => 'Racine',
                'uri'   => $indexController->getActionUrl(
                    $options['entityPathConfig'],
                    'index',
                    ($options['urlData']) ? $options['urlData'] : array()
                ),
            )
        );

        foreach ($entities as $entity) {

            if ($entity->getLvl() == 0) {

                $menu['root']->addChild(
                    $entity->getId(),
                    $this->getNode($entity)
                );

                if (count($entity->getChildren()) > 0) {

                    $this->childrenTree($menu['root'], $entity->getId(), $entity->getChildren());
                }
            }
        }

        return $menu;
    }

    /**
     * @param $node
     * @param $slug
     * @param $entities
     *
     * @return mixed
     */
    private function childrenTree($node, $slug, $entities)
    {

        foreach ($entities as $entity) {

            $node[$slug]->addChild(
                $entity->getId(),
                $this->getNode($entity)
            );

            if (count($entity->getChildren()) > 0) {

                $this->childrenTree($node[$slug], $entity->getId(), $entity->getChildren());
            }
        }

        return $node;
    }

    /**
     * @param $entity
     *
     * @return array
     */
    private function getNode($entity)
    {

        return array(
            'route'           => 'sudo_wh_superadmin_menuitem_index',
            'routeParameters' => array(
                'menuId'   => $entity->getMenu()->getId(),
                'parentId' => $entity->getId(),
            ),
            'label'           => $entity->getName(),
        );
    }

}