<?php
namespace Ibk\Ibkblog\Domain\Repository;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Ibk\Ibkblog\Controller\BlogController;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2018 Thomas Berscheid <thom@thomweb.de>, IBK
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
 * The repository for Blogs
 */
class BlogRepository extends Repository
{

    // USERNAME aus Tabelle FE_USER abfragen
    public function getBlogUsername()
    {
        $username = '';
        if ($GLOBALS['TSFE']->fe_user->user) {
            $username = $GLOBALS['TSFE']->fe_user->user['username'];
        }
        return $username;
    }

    // E-MAIL Adresse aus Tabelle FE_USER abfragen
    public function getBlogEmail()
    {
        $email = '';
        if ($GLOBALS['TSFE']->fe_user->user) {
            $email = $GLOBALS['TSFE']->fe_user->user['email'];
        }
        return $email;
    }

    // Gesamtzahl der Beiträge (Blogs) ermitteln
    public function countBlogs()
    {

        // Query aufbauen
        $query = $this->createQuery();
        $result = $query->execute()->count();
        return $result;
    }

    // Listfunktion mit Limit, Offset und Order
    /**
     * @param $blogLimit
     * @param $blogOffset
     * @return array|object[]|QueryResultInterface
     */
    public function findBlogs($blogLimit, $blogOffset)
    {
        // Query aufbauen
        $query = $this->createQuery();
        $query->setOrderings(array('datum' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        $query->setLimit(intval($blogLimit));
        $query->setOffset(intval($blogOffset));
        return $query->execute();
    }

    /**
     * Verfügbare Tags mit Anzahl Beiträgen auslesen
     *
     * @param int $tagid Storage PID
     * @throws Exception
     */
    public function getBlogTagName($tagid)
    {
        $table = "tx_ibkblog_domain_model_tag";
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $tagNameArray = $queryBuilder->select('name')->from($table)->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($tagid, \PDO::PARAM_INT)))->execute()->fetchAll();
        return $tagNameArray[0]['name'];
    }

    /**
     * Verfügbare Kategorien auslesen
     *
     * @param int $pid Storage PID
     * @throws Exception
     */
    public function getBlogKategorien($pid)
    {
        $table = "tx_ibkblog_domain_model_kategorie";
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

        return  $queryBuilder->select('uid', 'name')->from($table)->where(
            $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT))
        )->executeQuery()->fetchAllAssociative();
    }

    /**
     * Verfügbare Kategorien mit Anzahl Beiträgen auslesen
     *
     * @param int $pid Storage PID
     * @throws Exception
     */
    public function getBlogKategorienCount($pid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_ibkblog_domain_model_kategorie');
        return $queryBuilder->select('tx_ibkblog_domain_model_kategorie.uid', 'tx_ibkblog_domain_model_kategorie.name')
            ->addSelectLiteral($queryBuilder->expr()->count('tx_ibkblog_domain_model_kategorie.name', 'counter'))
            ->from('tx_ibkblog_domain_model_kategorie')
            ->join(
        'tx_ibkblog_domain_model_kategorie', 
        'tx_ibkblog_domain_model_blog',
        'tx_ibkblog_domain_model_blog',
        $queryBuilder->expr()->eq('tx_ibkblog_domain_model_kategorie.uid', $queryBuilder->quoteIdentifier('tx_ibkblog_domain_model_blog.kategorie'))
        )->where($queryBuilder->expr()->eq('tx_ibkblog_domain_model_kategorie.pid', $queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT)))
            ->orderBy('tx_ibkblog_domain_model_kategorie.name', 'ASC')
            ->groupBy('tx_ibkblog_domain_model_kategorie.uid')
            ->executeQuery()->fetchAllAssociative();
    }

    /**
     * Name einer Kategorie auslesen
     *
     * @param int $katid UID for Kategorie
     * @throws Exception
     */
    public function getBlogKategorienName(int $katid)
    {
        $table = "tx_ibkblog_domain_model_kategorie";
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $katNameArray = $queryBuilder->select('name')->from($table)->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($katid, \PDO::PARAM_INT)))->execute()->fetchAll();
        $katName = $katNameArray[0]['name'];
        return $katName;
    }

    /**
     * Anzahl der Blogs zu einer Kategorie auslesen
     * 
     * @param int 	$katid		UID for Kategorie
     * @return int
     */
    public function getBlogKategorienCounter(int $katid): int
    {
        $query = $this->createQuery();
        $query->matching($query->equals('kategorie', $katid));
        return $query->execute()->count();
    }

    /**
     * Find all posts with a distinct Kategorie
     * 
     * @param int 	$katid		UID for Kategorie
     * @param int   $limit  	How many posts should be found.
     * @param int   $offset		Where the search should start
     * @return QueryResultInterface
     */
    public function findByKategorie($katid, $limit, $offset)
    {
        $query = $this->createQuery();
        $query->matching($query->equals('kategorie', $katid));
        $query->setLimit(intval($limit));
        $query->setOffset(intval($offset));
        $query->setOrderings(array('datum' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        return $query->execute();
    }

    /**
     * Find all posts with a distinct Tag
     * 
     * @param int 	$tagid		UID for Tag
     * @param int   $limit  	How many posts should be found.
     * @param int   $offset		Where the search should start
     * @return QueryResultInterface
     */
    public function findByTag($tagid, $limit, $offset): QueryResultInterface
    {
        $table = "tx_ibkblog_blog_tag_mm";

        // Get UIDs from tagged blogs
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $tagBlogArray = $queryBuilder->select('uid_local')->from($table)->where($queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($tagid, \PDO::PARAM_INT)))->orderBy('uid_local', 'ASC')->execute()->fetchAll();

        // Array mit UIDs der getagten Blogs aufbauen
        $t = 0;
        while ($t < count($tagBlogArray)) {
            $tagInArray[] = $tagBlogArray[$t]['uid_local'];
            $t++;
        }
        $query = $this->createQuery();
        $query->matching($query->in('uid', $tagInArray));
        $query->setLimit(intval($limit));
        $query->setOffset(intval($offset));
        $query->setOrderings(array('datum' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        return $query->execute();
    }

    /**
     * Find number of posts with a distinct Tag
     *
     * @param int $tagid UID for Tag
     * @throws Exception
     */
    public function countTaggedBlogs($tagid)
    {
        $table = "tx_ibkblog_blog_tag_mm";

        // Get UIDs from tagged blogs
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $query = $queryBuilder->select('uid_local')->from($table)->where($queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($tagid, \PDO::PARAM_INT)))->execute()->fetchAll();
        return count($query);
    }

    // Verfügbare Tags auslesen

    /**
     * @param $pid
     * @throws Exception
     */
    public function getBlogTags($pid)
    {
        $table = 'tx_ibkblog_domain_model_tag';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        return $queryBuilder->select('uid, name')->from($table)->where($queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT)))->execute()->fetchAll();
    }


    /**
     * @param $pid
     * @throws Exception
     */
    public function tagShow($pid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_ibkblog_domain_model_tag');
        return $queryBuilder->select('tx_ibkblog_domain_model_tag.uid', 'tx_ibkblog_domain_model_tag.name')->addSelectLiteral($queryBuilder->expr()->count('tx_ibkblog_domain_model_tag.name', 'counter'))->from('tx_ibkblog_domain_model_tag')->join(
        'tx_ibkblog_domain_model_tag', 
        'tx_ibkblog_blog_tag_mm', 
        'tx_ibkblog_blog_tag_mm', 
        $queryBuilder->expr()->eq('tx_ibkblog_domain_model_tag.uid', $queryBuilder->quoteIdentifier('tx_ibkblog_blog_tag_mm.uid_foreign'))
        )->where($queryBuilder->expr()->eq('tx_ibkblog_domain_model_tag.pid', $queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT)))->orderBy('tx_ibkblog_domain_model_tag.name', 'ASC')->groupBy('tx_ibkblog_domain_model_tag.uid')->execute()->fetchAll();
    }
}
