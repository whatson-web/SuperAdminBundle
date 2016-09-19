<?php

namespace WH\SuperAdminBundle\Repository;

use WH\LibBundle\Repository\BaseRepository;

/**
 * Class MenuItemRepository
 *
 * @package WH\SuperAdminBundle\Repository
 */
class MenuItemRepository extends BaseRepository
{

	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getBaseQuery()
	{

		return $this
			->createQueryBuilder('menuItem')
			->addSelect('menu')
			->addSelect('parent')
            ->innerJoin('menuItem.menu', 'menu')
			->leftJoin('menuItem.parent', 'parent')
			->orderBy('menuItem.lft', 'ASC');
	}

}
