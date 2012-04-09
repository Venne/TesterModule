<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TesterModule;

use Venne;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Module extends \Venne\Module\BaseModule
{


	/** @var string */
	protected $description = "Make autotester for your project.";

	/** @var string */
	protected $version = "2.0";



	public function configure(\Nette\DI\Container $container)
	{
		parent::configure($container);

		$container->core->cmsManager->addContentType(Entities\TesterEntity::LINK, "tester page", array("url"), $container->tester->testerRepository, function() use($container)
		{
			return $container->tester->createTesterForm();
		});
		
		$container->core->cmsManager->addContentType(Entities\MainEntity::LINK, "list of testers", array("url"), $container->tester->mainRepository, function() use($container)
		{
			return $container->tester->createMainForm();
		});
	}

}
