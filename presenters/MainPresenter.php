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
use Nette\Utils\Strings;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class MainPresenter extends \CoreModule\Presenters\PagePresenter
{

	public function renderDefault()
	{
		$this->template->testers = $this->context->tester->testerRepository->findAll();
	}

}