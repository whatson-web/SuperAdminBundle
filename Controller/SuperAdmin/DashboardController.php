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
		return $this->render(
			'@WHSuperAdmin/Backend/Dashboard/dashboard.html.twig',
			array(
				'globalConfig' => array(
					'menus' => array(
						'main'  => 'backendMenu',
						'right' => 'backendMenuRight',
					),
				),
			)
		);
	}

	/**
	 * @Route("/admin/", name="sudo_wh_dashboard")
	 *
	 * @return Response
	 */
	public function dashboardAction()
	{
		return $this->render(
			'@WHSuperAdmin/SuperAdmin/Dashboard/dashboard.html.twig',
			array(
				'globalConfig' => array(
					'menus' => array(
						'main'  => 'superAdminMenu',
						'right' => 'superAdminMenuRight',
					),
				),
			)
		);
	}

}
