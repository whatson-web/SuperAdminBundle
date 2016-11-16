<?php

namespace WH\SuperAdminBundle\Controller\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WH\SuperAdminBundle\Entity\Menu;
use WH\SuperAdminBundle\Entity\MenuItem;

class LoadMenu implements FixtureInterface
{

	public function load(ObjectManager $manager)
	{
		// Les noms d'utilisateurs à créer
		$listMenus = array(
			array(
				'name'      => 'Super Admin Menu',
				'menuItems' => array(
					array(
						'name'  => 'Menus',
						'route' => 'sudo_wh_superadmin_menu_index',
					),
					array(
						'name'     => 'SEO',
						'children' => array(
							array(
								'name'  => 'Configurations',
								'route' => 'sudo_wh_seo_config_preview',
							),
							array(
								'name'  => 'Urls',
								'route' => 'sudo_wh_seo_url_index',
							),
							array(
								'name'  => 'Redirections',
								'route' => 'sudo_wh_seo_redirection_index',
							),
						),
					),
				),
			),
			array(
				'name'      => 'Super Admin Menu Right',
				'menuItems' => array(
					array(
						'name'  => 'Déconnexion',
						'route' => 'sudo_wh_user_security_logout',
					),
				),
			),
			array(
				'name'      => 'Backend Menu',
				'menuItems' => array(
					array(
						'name'  => 'Accueil',
						'route' => '',
					),
					array(
						'name'  => 'Pages',
						'route' => '',
					),
					array(
						'name'  => 'Actualités',
						'route' => 'bk_wh_blog_post_index',
					),
					array(
						'name'  => 'Utilisateurs',
						'route' => 'bk_wh_user_user_index',
					),
					array(
						'name'  => 'Paramètres',
						'route' => '',
					),
				),
			),
			array(
				'name'      => 'Backend Menu Right',
				'menuItems' => array(
					array(
						'name'  => 'Déconnexion',
						'route' => 'bk_wh_user_security_logout',
					),
				),
			),
		);

		foreach ($listMenus as $listMenu) {
			$menu = new Menu();

			$menu->setName($listMenu['name']);

			$manager->persist($menu);

			if (!isset($listMenu['menuItems'])) {
				continue;
			}
			foreach ($listMenu['menuItems'] as $listMenuItem) {
				$menuItem = new MenuItem();

				$menuItem->setName($listMenuItem['name']);
				if (!empty($listMenuItem['route'])) {
					$menuItem->setRoute($listMenuItem['route']);
				}
				$menuItem->setMenu($menu);

				$manager->persist($menuItem);

				if (!isset($listMenuItem['children'])) {
					continue;
				}

				foreach ($listMenuItem['children'] as $childrenListMenuItem) {
					$childrenMenuItem = new MenuItem();

					$childrenMenuItem->setName($childrenListMenuItem['name']);
					if (!empty($childrenListMenuItem['route'])) {
						$childrenMenuItem->setRoute($childrenListMenuItem['route']);
					}
					$childrenMenuItem->setMenu($menu);
					$childrenMenuItem->setParent($menuItem);

					$manager->persist($childrenMenuItem);
				}
			}
		}

		$manager->flush();
	}
}