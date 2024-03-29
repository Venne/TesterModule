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
 * @Entity(repositoryClass="\Venne\Doctrine\ORM\BaseRepository")
 * @Table(name="tester")
 *
 * @property $mainPage
 * @property $title
 * @property $keywords
 * @property $description
 * @property $text
 * @property $created
 * @property $updated
 * @property $website
 * @property $url
 */
class TesterEntity extends BaseEntity
{


	const LINK = "Tester:Default:default";
}
