<?php

namespace WH\SuperAdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use WH\SuperAdminBundle\Entity\MenuItem;

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

        // TODO : Trouver une meilleure méthode pour les conditions
        // Penser au cas où il peut y avoir plusieurs arguments "communs"
        $conditions = array();
        if (!empty($options['urlData'])) {

            $keys = array_keys($options['urlData']);
            $conditions = array(
                $keys[0] => $options['urlData'][$keys[0]],
            );
        }
        $entities = $entityRepository->get(
            'all',
            array(
                'conditions' => $conditions,
            )
        );

        $indexController = $this->container->get('bk.wh.back.index_controller');

        $this->config = $indexController->getConfig(
            $options['entityPathConfig'],
            'index'
        );

        $menu = $factory->createItem(
            'root'
        );

        $menu->addChild(
            'root',
            array(
                'label' => 'Racine',
                'uri'   => $indexController->getActionUrl(
                    $options['entityPathConfig'],
                    'index',
                    (isset($options['urlData'])) ? $options['urlData'] : array()
                ),
            )
        );

        foreach ($entities as $entity) {

            if ($entity->getLvl() == 0) {

                $menu['root']->addChild(
                    $entity->getId(),
                    $this->getNodeTree($entity)
                );

                if (count($entity->getChildren()) > 0) {

                    $this->treeChildren($menu['root'], $entity->getId(), $entity->getChildren());
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
    private function treeChildren($node, $slug, $entities)
    {

        foreach ($entities as $entity) {

            $node[$slug]->addChild(
                $entity->getId(),
                $this->getNodeTree($entity)
            );

            if (count($entity->getChildren()) > 0) {

                $this->treeChildren($node[$slug], $entity->getId(), $entity->getChildren());
            }
        }

        return $node;
    }

    /**
     * @param $entity
     *
     * @return array
     */
    private function getNodeTree($entity)
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

    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function menu(FactoryInterface $factory, array $options = array())
    {

        $menuItemRepository = $this->container->get('doctrine')->getRepository('WHSuperAdminBundle:MenuItem');

        $menuItems = $menuItemRepository->get(
            'all',
            array(
                'conditions' => array(
                    'menu.slug' => $options['menuSlug'],
                ),
            )
        );

        $menuClass = 'nav navbar-nav';
        if (!empty($options['menuRight'])) {
            $menuClass .= ' navbar-right';
        }
        $menu = $factory->createItem(
            'root',
            array(
                'childrenAttributes' => array(
                    'class' => $menuClass,
                ),
            )
        );

        foreach ($menuItems as $menuItem) {

            if ($menuItem->getLvl() == 0) {

                $menu->addChild(
                    $menuItem->getId(),
                    $this->getNodeMenu($menuItem)
                );

                if (count($menuItem->getChildren()) > 0) {

                    $this->menuChildren($menu, $menuItem->getId(), $menuItem->getChildren());
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
    private function menuChildren($node, $slug, $entities)
    {

        foreach ($entities as $entity) {

            $node[$slug]->addChild(
                $entity->getId(),
                $this->getNodeMenu($entity)
            );

            if (count($entity->getChildren()) > 0) {

                $this->menuChildren($node[$slug], $entity->getId(), $entity->getChildren());
            }
        }

        return $node;
    }

    /**
     * @param MenuItem $menuItem
     *
     * @return array
     */
    private function getNodeMenu(MenuItem $menuItem)
    {

        $nodeInfos = array(
            'label' => $menuItem->getName(),
        );

        if ($menuItem->getRoute()) {

            $nodeInfos['route'] = $menuItem->getRoute();
        } else {

            $nodeInfos['uri'] = '#';
        }

        if (count($menuItem->getChildren()) > 0) {

            $nodeInfos['extras'] = array(
                'safe_label' => true,
            );
            $nodeInfos['label'] .= ' <span class="caret"></span>';

            $nodeInfos['childrenAttributes'] = array(
                'class' => 'dropdown-menu',
            );
            $nodeInfos['attributes'] = array(
                'class' => 'dropdown',
            );
            $nodeInfos['linkAttributes'] = array(
                'class'       => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
            );
        }

        return $nodeInfos;
    }

}