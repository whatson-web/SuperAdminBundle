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

    private $indexController;

    private $config = [];
    private $entityPathConfig = [];
    private $urlData = [];

    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function tree(FactoryInterface $factory, array $options = [])
    {
        $this->indexController = $this->container->get('bk.wh.back.index_controller');

        $this->urlData = (isset($options['urlData'])) ? $options['urlData'] : [];

        $this->entityPathConfig = $options['entityPathConfig'];

        $this->config = $this->indexController->getConfig(
            $this->entityPathConfig,
            'index'
        );

        $entityRepository = $this->container->get('doctrine')->getRepository(
            $this->indexController->getRepositoryName($this->entityPathConfig)
        );

        // TODO : Trouver une meilleure méthode pour les conditions
        // Penser au cas où il peut y avoir plusieurs arguments "communs"
        $conditions = [];
        if (!empty($this->urlData)) {
            $keys = array_keys($this->urlData);
            $conditions = [
                $keys[0] => $this->urlData[$keys[0]],
            ];
        }
        unset($conditions['parent']);

        $entities = $entityRepository->get(
            'all',
            [
                'conditions' => $conditions,
            ]
        );

        $menu = $factory->createItem('root');

        $data = array_merge(
            $this->urlData,
            [
                'parent.id' => null,
            ]
        );

        $rootLabel = 'Root';
        if (isset($options['rootLabel'])) {
            $rootLabel = $options['rootLabel'];
        }

        $menu->addChild(
            'root',
            [
                'label' => $rootLabel,
                'uri'   => $this->indexController->getActionUrl(
                    $this->entityPathConfig,
                    'index',
                    $data
                ),
            ]
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
        $data = array_merge(
            $this->urlData,
            [
                'parent' => [
                    'id' => $entity->getId(),
                ],
            ]
        );

        return [
            'uri'   => $this->indexController->getActionUrl(
                $this->entityPathConfig,
                'index',
                $data
            ),
            'label' => $entity->getName(),
        ];
    }

}