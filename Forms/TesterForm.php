<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TesterModule\Forms;

use Venne\Forms\Mapping\EntityFormMapper;
use Doctrine\ORM\EntityManager;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class TesterForm extends \Venne\Forms\PageForm
{



	public function startup()
	{
		parent::startup();

		$this->addGroup("Tester options");
		$this->addText("name", "Name");
		$this->addText("repository", "Repository");
		$this->addText("revision", "Revision")->setDefaultValue("master");
		$this->addText("testDir", "Test directoy");
		$this->addSelect("driver", "Driver", \TesterModule\Entities\BaseEntity::getDrivers());
	}



	protected function getParams()
	{
		return array(
			"module" => "Tester",
			"presenter" => "Default",
			"action" => "default",
			"url" => isset($this->entity->url) ? $this->entity->url : NULL
		);
	}

}
