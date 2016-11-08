<?php

namespace WH\SuperAdminBundle\Controller\SuperAdmin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use WH\BackendBundle\Controller\Backend\BaseController;

/**
 * @Route("/sudo/menu/items")
 *
 * Class MenuItemController
 *
 * @package WH\SuperAdminBundle\Controller\SuperAdmin
 */
class MenuItemController extends BaseController
{

    public $bundlePrefix = 'WH';
    public $bundle = 'SuperAdminBundle';
	public $entity = 'MenuItem';
	public $type = 'SuperAdmin';

    /**
     * @Route("/index/{menuId}/{parentId}", name="sudo_wh_superadmin_menuitem_index")
     *
     * @param         $menuId
     * @param         $parentId
     * @param Request $request
     *
     * @return string
     */
    public function indexAction($menuId, $parentId = null, Request $request)
    {

        $arguments = array(
            'menu.id' => $menuId,
        );
        if (!$parentId) {
            $arguments['parent.id NULL'] = true;
        } else {
            $arguments['parent.id'] = $parentId;
        }

        $indexController = $this->get('bk.wh.back.index_controller');

        return $indexController->index($this->getEntityPathConfig(), $request, $arguments);
    }

    /**
     * @Route("/create/{menuId}", name="sudo_wh_superadmin_menuitem_create")
     *
     * @param         $menuId
     * @param Request $request
     *
     * @return mixed
     */
    public function createAction($menuId, Request $request)
    {

        $arguments = array(
            'menu.id' => $menuId,
        );

        $createController = $this->get('bk.wh.back.create_controller');

        return $createController->create($this->getEntityPathConfig(), $request, $arguments);
    }

    /**
     * @Route("/update/{id}", name="sudo_wh_superadmin_menuitem_update")
     *
     * @param         $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id, Request $request)
    {

        $updateController = $this->get('bk.wh.back.update_controller');

        return $updateController->update($this->getEntityPathConfig(), $id, $request);
    }

    /**
     * @Route("/delete/{id}", name="sudo_wh_superadmin_menuitem_delete")
     *
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {

        $deleteController = $this->get('bk.wh.back.delete_controller');

        return $deleteController->delete($this->getEntityPathConfig(), $id);
    }

    /**
     * @Route("/order/{menuId}", name="sudo_wh_superadmin_menuitem_order")
     *
     * @param         $menuId
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function orderAction($menuId, Request $request)
    {

        $arguments = array(
            'menu.id' => $menuId,
        );

        $orderController = $this->get('bk.wh.back.order_controller');

        return $orderController->order($this->getEntityPathConfig(), $request, $arguments);
    }

}
