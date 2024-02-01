<?php
namespace Ibk\Ibkblog\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use \TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Thomas Berscheid <thom@thomweb.de>, IBK
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/***
 *
 * This file is part of the "Blog" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Thomas Berscheid <thom@thomweb.de>, IBK
 *
 ***/
/**
 * Blog
 */
class Blog extends AbstractEntity
{

    protected $name = '';

    protected $email = '';

    protected $titel = '';

    protected $kurzfassung = '';

    protected $inhalt = '';

    protected $datum = NULL;

    protected $link = '';
    
    protected $kategorie = NULL;

    /**
     * Tag cloud with multiple tags
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ibk\Ibkblog\Domain\Model\Tag>
     */
    protected $tags = NULL;

    /**
     * __construct
     */
    public function __construct()
    {

        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     * 
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->tags = new ObjectStorage();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTitel(): string
    {
        return $this->titel;
    }

    public function setTitel(string $titel): void
    {
        $this->titel = $titel;
    }

    public function getKurzfassung(): string
    {
        return $this->kurzfassung;
    }

    public function setKurzfassung(string $kurzfassung): void
    {
        $this->kurzfassung = $kurzfassung;
    }

    public function getInhalt(): string
    {
        return $this->inhalt;
    }

    public function setInhalt(string $inhalt): void
    {
        $this->inhalt = $inhalt;
    }

    public function getDatum(): string
    {
        return $this->datum;
    }

    public function setDatum(string $datum): void
    {
        $this->datum = $datum;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * Returns the kategorie
     * 
     * @return \Ibk\Ibkblog\Domain\Model\Kategorie $kategorie
     */
    public function getKategorie()
    {
        return $this->kategorie;
    }

    /**
     * Sets the kategorie
     * 
     * @param \Ibk\Ibkblog\Domain\Model\Kategorie $kategorie
     * @return void
     */
    public function setKategorie(\Ibk\Ibkblog\Domain\Model\Kategorie $kategorie)
    {
        $this->kategorie = $kategorie;
    }

    /**
     * Adds a Tag
     * 
     * @param \Ibk\Ibkblog\Domain\Model\Tag $tag
     * @return void
     */
    public function addTag(\Ibk\Ibkblog\Domain\Model\Tag $tag)
    {
        $this->tags->attach($tag);
    }

    /**
     * Removes a Tag
     * 
     * @param \Ibk\Ibkblog\Domain\Model\Tag $tagToRemove The Tag to be removed
     * @return void
     */
    public function removeTag(\Ibk\Ibkblog\Domain\Model\Tag $tagToRemove)
    {
        $this->tags->detach($tagToRemove);
    }

    /**
     * Returns the tags
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ibk\Ibkblog\Domain\Model\Tag> $tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Sets the tags
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ibk\Ibkblog\Domain\Model\Tag> $tags
     * @return void
     */
    public function setTags(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags)
    {
        $this->tags = $tags;
    }
}
