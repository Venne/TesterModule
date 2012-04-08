<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TesterModule\Services\RepositoryDrivers;

use Venne;
use Nette\Object;
use TesterModule\Services\IRepositoryDriver;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class GitSubmoduleDriver extends GitDriver implements IRepositoryDriver
{



	public function getCloneCommand($repository, $revision, $info, $error)
	{
		return parent::getCloneCommand($repository, $revision, $info, $error) . " && git submodule update --init > " . ($info ? : "/dev/null") . " 2>" . ($error ? : "&1");
	}

}
