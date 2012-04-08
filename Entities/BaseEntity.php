<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TesterModule\Entities;

use Venne;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class BaseEntity extends \CoreModule\Entities\BasePageEntity
{


	const DRIVER_GIT = "git";
	const DRIVER_GIT_COMPOSER = "git_composer";
	const DRIVER_GIT_SUBMODULE = "git_submodule";

	protected static $drivers = array(
		self::DRIVER_GIT => "git",
		self::DRIVER_GIT_COMPOSER => "git & composer",
		self::DRIVER_GIT_SUBMODULE => "git & submodules",
	);

	/** @Column(type="string") */
	protected $driver;

	/** @Column(type="string") */
	protected $name;

	/** @Column(type="string") */
	protected $repository;

	/** @Column(type="string") */
	protected $revision;

	/** @Column(type="string") */
	protected $testDir;

	/** @Column(type="string") */
	protected $hash;

	/** @Column(type="string") */
	protected $hookLink;

	/** @Column(type="datetime") */
	protected $created;

	/** @Column(type="datetime") */
	protected $updated;

	/** @Column(type="integer") */
	protected $errors;

	/** @Column(type="integer") */
	protected $tests;

	/** @Column(type="integer") */
	protected $assertions;

	/** @Column(type="integer") */
	protected $time;



	function __construct()
	{
		$this->hookLink = \Nette\Utils\Strings::random(20);
		$this->hash = "";

		$this->created = new \Nette\DateTime;
		$this->updated = new \Nette\DateTime;
		$this->errors = 0;
	}



	public static function getDrivers()
	{
		return self::$drivers;
	}



	public function getName()
	{
		return $this->name;
	}



	public function setName($name)
	{
		$this->name = $name;
	}



	public function getRepository()
	{
		return $this->repository;
	}



	public function setRepository($repository)
	{
		$this->repository = $repository;
	}



	public function getRevision()
	{
		return $this->revision;
	}



	public function setRevision($revision)
	{
		$this->revision = $revision;
	}



	public function getTestDir()
	{
		return $this->testDir;
	}



	public function setTestDir($testDir)
	{
		$this->testDir = $testDir;
	}



	public function getHash()
	{
		return $this->hash;
	}



	public function setHash($hash)
	{
		$this->hash = $hash;
	}



	public function getHookLink()
	{
		return $this->hookLink;
	}



	public function getDriver()
	{
		return $this->driver;
	}



	public function setDriver($driver)
	{
		$this->driver = $driver;
	}



	public function getCreated()
	{
		return $this->created;
	}



	public function setCreated($created)
	{
		$this->created = $created;
	}



	public function getUpdated()
	{
		return $this->updated;
	}



	public function setUpdated($updated)
	{
		$this->updated = $updated;
	}



	public function getErrors()
	{
		return $this->errors;
	}



	public function setErrors($errors)
	{
		$this->errors = (int)$errors;
	}



	public function getTests()
	{
		return $this->tests;
	}



	public function setTests($tests)
	{
		$this->tests = (int)$tests;
	}



	public function getAssertions()
	{
		return $this->assertions;
	}



	public function setAssertions($assertions)
	{
		$this->assertions = (int)$assertions;
	}



	public function getTime()
	{
		return $this->time;
	}



	public function setTime($time)
	{
		$this->time = $time;
	}

}
