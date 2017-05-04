<?php

namespace WH\SuperAdminBundle\Controller\SuperAdmin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DashboardController
 *
 * @package WH\SuperAdminBundle\Controller\SuperAdmin
 */
class DashboardController extends Controller
{

    /**
     * @Route("/admin/", name="bk_wh_dashboard")
     *
     * @return Response
     */
    public function backDashboardAction()
    {
        $backendTranslator = $this->container->get('bk.wh.back.translator');
        $backendTranslator->setDomain('WHSuperAdminBundle_Backend_Dashboard');

        $title = $backendTranslator->trans('Dashboard');

        return $this->render(
            '@WHSuperAdmin/Backend/Dashboard/dashboard.html.twig',
            [
                'title'        => $title,
                'globalConfig' => [
                    'menus' => [
                        'main'  => 'backendMenu',
                        'right' => 'backendMenuRight',
                    ],
                ],
            ]
        );
    }

    /**
     * @Route("/sudo/", name="sudo_wh_dashboard")
     *
     * @return Response
     */
    public function dashboardAction()
    {
        $backendTranslator = $this->container->get('bk.wh.back.translator');
        $backendTranslator->setDomain('WHSuperAdminBundle_SuperAdmin_Dashboard');

        $title = $backendTranslator->trans('Dashboard');

        return $this->render(
            '@WHSuperAdmin/SuperAdmin/Dashboard/dashboard.html.twig',
            [
                'title'        => $title,
                'globalConfig' => [
                    'menus' => [
                        'main'  => 'superAdminMenu',
                        'right' => 'superAdminMenuRight',
                    ],
                ],
            ]
        );
    }

}
