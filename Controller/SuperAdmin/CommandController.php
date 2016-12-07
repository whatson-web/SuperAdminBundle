<?php

namespace WH\SuperAdminBundle\Controller\SuperAdmin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/sudo/commands")
 *
 * Class CommandController
 *
 * @package WH\SuperAdminBundle\Controller\SuperAdmin
 */
class CommandController extends Controller
{

	/**
	 * @var array
	 */
	private $globalConfig = array(
		'menus' => array(
			'main'  => 'superAdminMenu',
			'right' => 'superAdminMenuRight',
		),
	);

	private $commands = array(
		'clearCache' => array(
			'title'      => 'Vider le cache',
			'arrayInput' => array(
				'command' => 'cache:clear',
			),
		),
		'dbUpdate'   => array(
			'title'      => 'Mettre à jour la base de données',
			'arrayInput' => array(
				'command' => 'doctrine:schema:update',
				'--force' => true,
			),
		),
	);

	/**
	 * @Route("/", name="sudo_wh_superadmin_command_list")
	 *
	 * @return Response
	 */
	public function listCommand()
	{
		return $this->render(
			'@WHSuperAdmin/SuperAdmin/Command/list.html.twig',
			array(
				'commands'     => $this->commands,
				'globalConfig' => $this->globalConfig,
			)
		);
	}

	/**
	 * @Route("/{commandSlug}", name="sudo_wh_superadmin_command_execute")
	 *
	 * @param $commandSlug
	 *
	 * @return Response
	 */
	public function executeCommand($commandSlug)
	{
		if (!isset($this->commands[$commandSlug])) {
			throw new NotFoundHttpException('Commmande introuvable');
		}
		$command = $this->commands[$commandSlug];

		$kernel = $this->get('kernel');
		$application = new Application($kernel);
		$application->setAutoExit(false);

		$input = new ArrayInput($command['arrayInput']);
		$output = new BufferedOutput();
		$application->run($input, $output);

		$result = $output->fetch();

		return $this->render(
			'WHBackendTemplateBundle:BackendTemplate/View:command.html.twig',
			array(
				'title'        => $command['title'],
				'content'      => $result,
				'globalConfig' => $this->globalConfig,
			)
		);
	}

}
