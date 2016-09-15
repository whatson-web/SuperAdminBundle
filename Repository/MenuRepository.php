<?php

namespace WH\SuperAdminBundle\Repository;

use WH\LibBundle\Repository\BaseRepository;

/**
 * Class MenuRepository
 *
 * @package WH\SuperAdminBundle\Repository
 */
class MenuRepository extends BaseRepository
{

	/**
	 * @return string
	 */
	public function getEntityNameQueryBuilder()
	{

		return 'menu';
	}
}
