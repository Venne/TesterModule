<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TesterModule\Services;

use Venne;
use Nette\Object;
use Nette\Utils\Finder;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Tester extends Object
{


	const TYPE_DIR = "dir";
	const TYPE_GIT = "git";

	/** @var string */
	protected $name;

	/** @var IRepositoryDriver */
	protected $driver;

	/** @var IPostHook[] */
	protected $postHooks = array();

	/** @var string */
	protected $remoteRepository;
	
	/** @var string */
	protected $revision;

	/** @var string */
	protected $repository;

	/** @var string */
	protected $testDir;

	/** @var string */
	protected $resultDir;

	/** @var string */
	protected $_xml;

	/** @var string */
	protected $_results;

	/** @var string */
	protected $_time;

	/** @var string */
	protected $_tests;

	/** @var string */
	protected $_errors;

	/** @var string */
	protected $_assertions;



	public function __construct(IRepositoryDriver $driver, $name, $repository, $testDir, $resultDir, $remoteRepository , $revision)
	{
		$this->driver = $driver;
		$this->name = $name;
		$this->repository = $repository;
		$this->testDir = $testDir;
		$this->resultDir = $resultDir;
		$this->remoteRepository = $remoteRepository;
		$this->revision = $revision;

		if (!file_exists($this->getResultDir())) {
			@mkdir($this->getResultDir(), 0777, true);
		}

		if (!file_exists($this->repository)) {
			@mkdir($this->repository, 0777, true);
		}
	}



	public function addPostHook(IPostHook $hook)
	{
		$this->postHooks[] = $hook;
	}



	public function checkoutRepository()
	{
		$this->cleanRepository();
		$this->runCommand("cd {$this->repository} ; {$this->driver->getCloneCommand($this->remoteRepository, $this->revision, $this->getInfoFile(), $this->getErrorFile())}");
	}



	public function pullRepository()
	{
		$this->runCommand("cd {$this->repository} ; {$this->driver->getPullCommand($this->remoteRepository, $this->revision, $this->getInfoFile(), $this->getErrorFile())}");
	}



	public function runTest()
	{
		$resultDir = $this->getResultDir();
		$this->runCommand("cd {$this->getTestDir()} ; phpunit --log-junit {$this->getResultFile()}");

		$this->_xml = NULL;
		foreach ($this->postHooks as $hook) {
			$hook->process($this->getErrors());
		}
	}



	public function isLocked()
	{
		return file_exists($this->getResultDir() . "/lock");
	}



	public function isCloned()
	{
		$ret = false;
		$directory = dir($this->repository);

		while ((FALSE !== ($item = $directory->read()))) {
			if ($item != '.' && $item != '..') {
				$ret = true;
				break;
			}
		}

		$directory->close();
		return $ret;
	}



	public function getXmlResult()
	{
		if (file_exists($this->getResultDir() . "/result.xml")) {
			if (!$this->_xml) {
				$this->_xml = file_get_contents($this->getResultFile());
			}
			return $this->_xml;
		}
		return NULL;
	}



	public function getResults()
	{
		if (!$this->_results) {
			$this->processXml();
		}
		return $this->_results;
	}



	public function getTime()
	{
		if (!$this->_time) {
			$this->processXml();
		}
		return $this->_time;
	}



	public function getTests()
	{
		if (!$this->_tests) {
			$this->processXml();
		}
		return $this->_tests;
	}



	public function getAssertions()
	{
		if (!$this->_assertions) {
			$this->processXml();
		}
		return $this->_assertions;
	}



	public function getErrors()
	{
		if (!$this->_errors) {
			$this->processXml();
		}
		return $this->_errors;
	}



	protected function processXml()
	{
		$result = $this->getXmlResult();
		if (!$result) {
			return NULL;
		}

		$simple = new \SimpleXMLElement($result);

		$test_results = array();
		$assertions = 0;
		$time = 0.0;
		$tests = 0;
		$errors = 0;
		foreach ($simple->{'testsuite'}->testsuite as $testsuite) {
			foreach ($testsuite->testcase as $testcase) {
				$result = array();
				$tests += 1;
				// Don't froget to cast SimpleXMLElement to string!
				$result['name'] = (string) $testcase['name'];
				$result['suite'] = (string) $testcase['class'];
				$assertions += $result['assertions'] = (string) $testcase['assertions'];
				$time += $result['time'] = (string) $testcase['time'];
				if (isset($testcase->{'failure'})) {
					$errors++;
					$result['result'] = 'Fail';
					$result['message'] = (string) $testcase->{'failure'};
				} else {
					$result['result'] = 'Pass';
					$result['message'] = '';
				}
				$test_results[] = $result;
			}
		}
		$this->_results = $test_results;
		$this->_assertions = $assertions;
		$this->_time = $time;
		$this->_tests = $tests;
		$this->_errors = $errors;
	}



	public function getResultFile()
	{
		return $this->getResultDir() . "/result.xml";
	}



	public function getErrorFile()
	{
		return $this->getResultDir() . "/error.txt";
	}



	public function getInfoFile()
	{
		return $this->getResultDir() . "/info.txt";
	}



	protected function getTestDir()
	{
		return $this->repository . $this->testDir;
	}



	protected function getResultDir()
	{
		return $this->resultDir;
	}



	protected function runCommand($command)
	{
		$cmd = "touch {$this->resultDir}/lock ; {$command} ; rm {$this->resultDir}/lock";
		//die($cmd);
		system($cmd);
	}



	public function cleanRepository()
	{
		@unlink($this->getResultFile());

		$dirContent = Finder::find('*')->from($this->repository)->childFirst();
		foreach ($dirContent as $file) {
			if ($file->isDir())
				@rmdir($file->getPathname());
			else
				@unlink($file->getPathname());
		}
	}

}
