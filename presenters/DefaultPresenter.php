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
class DefaultPresenter extends \CoreModule\Presenters\PagePresenter
{

	/** @persistent */
	public $tab = "results";
	
	/** @var Services\TesterService */
	protected $tester;
	

	public function startup()
	{
		parent::startup();

		$driverClass = "\TesterModule\Services\RepositoryDrivers\\" . ucfirst($this->page->driver) . "Driver";
		while (($pos = strpos($driverClass, "_")) !== false) {
			$end = substr($driverClass, $pos + 1);
			$driverClass = substr($driverClass, 0, $pos) . ucfirst($end);
		}

		$this->tester = new Services\Tester(new $driverClass, $this->page->name, $this->context->parameters["tempDir"] . "/repositories/" . Strings::webalize($this->page->name), $this->page->testDir, $this->context->parameters["tempDir"] . "/tests/" . Strings::webalize($this->page->name), $this->page->repository, $this->page->revision);


		$this->invalidateControl("tabs");
	}



	public function handleRun($redirect = true)
	{
		ignore_user_abort(true);
		session_write_close();
		
		$this->tester->runTest();
		$this->page->errors = $this->tester->getErrors();
		$this->page->assertions = $this->tester->getAssertions();
		$this->page->tests = $this->tester->getTests();
		$this->page->time = $this->tester->getTime();
		$this->page->updated = new \DateTime();
		$this->context->entityManager->flush();
		
		if($redirect){
			//if($this->isAjax()){
			//	$this->terminate();
			//}else{
				$this->redirect("this");
			//}
		}
	}



	public function handleCheckout()
	{
		ignore_user_abort(true);
		session_write_close();
		
		$this->tester->checkoutRepository();
		
		if($this->isAjax()){
			$this->terminate();
		}else{
			$this->redirect("this");
		}
	}



	public function handlePull()
	{
		ignore_user_abort(true);
		session_write_close();
		
		$this->tester->pullRepository();
		
		if($this->isAjax()){
			$this->terminate();
		}else{
			$this->redirect("this");
		}
	}



	public function handleUpdateAll($hash)
	{
		ignore_user_abort(true);
		session_write_close();
		
		if($this->page->hookLink != $hash){
			throw new \Nette\Application\BadRequestException;
		}
		
		$this->handleRun(false);
		
		if($this->isAjax()){
			$this->terminate();
		}else{
			$this->redirect("this");
		}
	}



	public function handleClean()
	{
		$this->tester->cleanRepository();
		
		$this->page->errors = 0;
		$this->page->assertions = 0;
		$this->page->tests = 0;
		$this->page->time = 0;
		$this->context->entityManager->flush();
		
		$this->redirect("this");
	}



	public function getInfoLog()
	{
		return $this->tester->getInfoFile() ? file_get_contents($this->tester->getInfoFile()) : "null";
	}



	public function getErrorLog()
	{
		return $this->tester->getErrorFile() ? file_get_contents($this->tester->getErrorFile()) : "null";
	}
	
	
	
	public function handleTab()
	{
		$this->template->showTab = true;
		$this->invalidateControl("tab");
		
		$this->validateControl("tabs");
	}



	public function renderDefault()
	{
		$this->template->isLocked = $this->tester->isLocked();
		$this->template->entity = $this->page;
		$this->template->tester = $this->tester;
	}

}