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
class MainForm extends \Venne\Forms\PageForm
{


	protected function getParams()
	{
		return array(
			"module" => "Tester",
			"presenter" => "Main",
			"action" => "default",
			"url" => isset($this->entity->url) ? $this->entity->url : NULL
		);
	}

}
