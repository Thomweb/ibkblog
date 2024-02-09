<?php
namespace Ibk\Ibkblog\Controller;

use Doctrine\DBAL\Exception;
use Ibk\Ibkblog\PageTitle\PageTitleProvider;
use Ibk\Ibkblog\Services\MetatagServices;
use Ibk\Ibkblog\Domain\Model\Blog;
use Psr\Http\Message\ResponseInterface;
use Ibk\Ibkblog\Domain\Repository\BlogRepository;
use Ibk\Ibkblog\Seo\EventListener;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 - 2024 Thomas Berscheid <thom@thomweb.de>, IBK
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
 *  (c) 2020 - 2024 Thomas Berscheid <thom@thomweb.de>, IBK
 *
 ***/

/**
 * BlogController
 */
class BlogController extends ActionController
{

    /**
     * @param Ibk\Ibkblog\Domain\Repository\BlogRepository $blogRepository
     */
    private BlogRepository $blogRepository;

    public function __construct(
        BlogRepository $blogRepository,
        MetaTagManagerRegistry $metaTagManagerRegistry,
        PageRenderer $pageRenderer,
        MetatagServices $metatagServices,
        private readonly PageTitleProvider $pageTitleProvider
    )
    {
        $this->blogRepository = $blogRepository;
        $this->metaTagManagerRegistry = $metaTagManagerRegistry;
        $this->pageRenderer = $pageRenderer;
        $this->metatagServices = $metatagServices;
    }


    /**
     * action showByLink
     *
     * @param int $bloguid
     * @return ResponseInterface
     * @throws \Exception
     */
    public function showByLinkAction(int $bloguid = 0) :ResponseInterface
    {
        // Blogbeitrag zu diesem Link abfragen
        // Wenn kein Link gefunden (Blog Objekt leer) Fehlerbehandlung in Partial

        if (intval($bloguid) > 0) {
            $blog = $this->blogRepository->findByUid($bloguid);

            if ($blog) {
                // Fill <TITLE> Tag and META-Tags from Blog Posts
                $blogTitel = $blog->getTitel();

                $this->pageTitleProvider->setTitle($blogTitel);
                $this->metatagServices->setMetaTitle($blogTitel);
                $this->metatagServices->setMetaDescription($blog->getKurzfassung());
                $this->metatagServices->setMetaDate($blog->getDatum());
                $this->metatagServices->setMetaName($blog->getName());
                //$this->blogEventListener;

                // Add structured data to <HEAD> section
                $data = $this->metatagServices->setStructuredData($blog, $this->settings);

                $headerData = '<script type="application/ld+json">' . json_encode($data) . '</script>';
                $this->pageRenderer->addHeaderData($headerData);

                // Commit all data to view
                $this->view->assign('blog', $blog);
            }
        }

        // Die letzten 5 neuen Blogbeiträge finden
        $bloglist = $this->blogRepository->findBlogs(5, 0);

        $username = $this->blogRepository->getBlogUsername();
        $pageLoginUID = $this->settings['pageLoginUID'];
        $pageStartUID = $this->settings['pageStartUID'];

        $this->view->assign('bloglist', $bloglist);
        $this->view->assign('username', $username);
        $this->view->assign('pagestartuid', $pageStartUID);
        $this->view->assign('pageloginuid', $pageLoginUID);

        return $this->htmlResponse();
    }

    /**
     * action show
     * 
     * @param Blog $blog
     * @return ResponseInterface
     */
    public function showAction(Blog $blog): ResponseInterface
    {
        $username = $this->blogRepository->getBlogUsername();
        $pageLoginUID = $this->settings['pageLoginUID'];
        $pageStartUID = $this->settings['pageStartUID'];

        // Fill <TITLE> Tag from Blog Posts
        $this->metatagServices->setMetaTitle($blog->getTitel());
        $this->metatagServices->setMetaDescription($blog->getKurzfassung());
        $this->metatagServices->setMetaName($blog->getName());
        $this->pageTitleProvider->setTitle($blog->getTitel());

        // Commit all data to view
        $this->view->assign('blog', $blog);
        $this->view->assign('username', $username);
        $this->view->assign('pagestartuid', $pageStartUID);
        $this->view->assign('pageloginuid', $pageLoginUID);

        return $this->htmlResponse();
    }

    /**
     * action list
     *
     * @param int $_katid
     * @param int $_tagid
     * @param int $_offset
     * @return ResponseInterface
     * @throws Exception
     */
    public function listAction(int $_katid = 0, int $_tagid = 0, int $_offset = 0) :ResponseInterface
    {
        $offsetLink = 0;
        $blogKatLink = 0;
        $blogTagLink = 0;
        $blogKatName = "";
        $blogTagName = "";
        $countBlogPagesMax = 0;
        $countBlogPagesArray = [];

        // PID auslesen und Pages
        $pid = $this->settings['storagePid'];
        $pageLoginUID = $this->settings['pageLoginUID'];
        $pageStartUID = $this->settings['pageStartUID'];

        // Alle verfügbaren Tags auslesen
        $tagShowArray = $this->blogRepository->tagShow($pid);
        $tagCloudArray = $this->blogRepository->tagShow($pid);

        // Kategorien mit der Anzahl der Treffer auslesen
        $kat = $this->blogRepository->getBlogKategorienCount($pid);
        $username = $this->blogRepository->getBlogUsername();

        // Alle verfügbaren Kategorien auslesen
        $kategorieArray = $this->blogRepository->getBlogKategorien($pid);
        
        // Standardwerte falls nichts übergeben
        $limit = 10;

        // Erste Seite, wenn nicht anders übergeben
        $blogPage = 1;

        // Standard für Controller, kann auf mehrfache Weise überschrieben werden
        $controller = 'blog';

        if ($_katid>0) {
            $blogKatLink = $_katid;
            $controller = 'kat';
            $offsetLink = $_offset;
        } elseif ($_tagid > 0) {
            $blogTagLink = $_tagid;
            $controller = 'tag';
            $offsetLink = $_offset;
        } elseif ($_offset > 0) {
            $offsetLink = $_offset;

        } else {
            // Abfrage des Wertes für Offset und Controller

            if (array_key_exists('tx_ibkblog_blog', $this->request->getQueryParams())) {
                if ($vars = $this->request->getQueryParams()['tx_ibkblog_blog']) {
                    array_key_exists('katid', $vars) ? $blogKatLink = intval($vars['katid']) : 0;
                    array_key_exists('tagid', $vars) ? $blogTagLink = intval($vars['tagid']) : 0;
                    array_key_exists('offset', $vars) ? $offsetLink = intval($vars['offset']) : 0;
                }

                if ($blogKatLink > 0) {
                    $blogKatName = $this->blogRepository->getBlogKategorienName($blogKatLink);
                    $controller = 'kat';
                }
                if ($blogTagLink > 0) {
                    $blogTagName = $this->blogRepository->getBlogTagName($blogTagLink);
                    $controller = 'tag';
                }
            }
        }



        if ($offsetLink > 0) {
            $_offset = ($offsetLink - 1) * $limit;
            $blogPage = $offsetLink;
        }

        // Blogs abrufen
        // Anzahl der gesamten Blogs abfragen
        if ($controller == 'tag' || $blogTagLink > 0) {
            $blogs = $this->blogRepository->findByTag($blogTagLink, $limit, $_offset);
            $countBlogAll = intval($this->blogRepository->countTaggedBlogs($blogTagLink));
        } elseif ($controller == 'kat' || $blogKatLink > 0) {
            $blogs = $this->blogRepository->findByKategorie($blogKatLink, $limit, $_offset);
            $countBlogAll = intval($this->blogRepository->getBlogKategorienCounter($blogKatLink));
        } else {
            $blogs = $this->blogRepository->findBlogs($limit, $_offset);
            $countBlogAll = intval($this->blogRepository->countBlogs());
        }

        // Daten für Paging
        $countBlogPages = 0;
        if ($countBlogAll > $limit) {
            $countBlogPagesMax = ceil($countBlogAll / $limit);
            $p = 0;
            while ($p < $countBlogPagesMax) {
                $p++;
                $countBlogPagesArray[] = $p;
            }
        }

        // Ansichten für Frontend und META-Tags
        $textTemp = "";
        if ($countBlogPagesMax > 0) {
            $textTemp = "Seite " . $blogPage . " von " . $countBlogPagesMax . ": ";
        }
        if ($offsetLink == 0 && $controller == 'blog') {
            $textTemp = "TYPO3 und Wordpress Blog Agentur IBK Köln: ";
        }

        if ($controller == 'tag') {
            $title = $textTemp . "Treffer für Schlagwort " . $blogTagName;
            $description = $textTemp . "Treffer für das Schlagwort " . $blogTagName;
        } elseif ($controller == 'kat') {
            $title = $textTemp . "Treffer für Kategorie " . $blogKatName;
            $description = $textTemp . "Treffer für Kategorie " . $blogKatName;
        } else {
            $title = $textTemp . "Listenansicht";
            $description = $textTemp . "Listenansicht des Blog der Agentur IBK in Köln ✔ TYPO3 ✔ Wordpress ✔ SEO";
        }

        // Fill <TITLE> Tag from Blog Posts
        $this->pageTitleProvider->setTitle($title);
        $this->metatagServices->setMetaTitle($title);
        $this->metatagServices->setMetaDescription($description);

        // Alle Daten an die View übergeben
        $this->view->assign('title', $title);
        $this->view->assign('kat', $kat);
        $this->view->assign('controller', $controller);
        $this->view->assign('tagshowarray', $tagShowArray);
        $this->view->assign('tagcloudarray', $tagCloudArray);
        $this->view->assign('blogs', $blogs);
        $this->view->assign('username', $username);
        $this->view->assign('blogcounter', $countBlogPagesArray);
        $this->view->assign('blogoffset', $blogPage);
        $this->view->assign('blogtaglink', $blogTagLink);
        $this->view->assign('blogkatlink', $blogKatLink);
        $this->view->assign('blogtagname', $blogTagName);
        $this->view->assign('blogkatname', $blogKatName);
        $this->view->assign('pagestartuid', $pageStartUID);
        $this->view->assign('pageloginuid', $pageLoginUID);

        return $this->htmlResponse();
    }

    /**
     * action more
     * 
     * @param int $katid
     * @param int $tagid 
     * @param int $page
     * @return ResponseInterface
     */
    public function moreAction(int $katid=0, int $tagid=0, int $page=0) :ResponseInterface
    {
        if ($vars = GeneralUtility::_GET('tx_ibkblog_pluginone')) {
            $page = intval($vars['page']);
        }
        $limit = 10;
        if ($page > 0) {
            $offset = ($page - 1) * $limit;
            $pageneu = $page + 1;
        } else {
            $offset = 0;
            $pageneu = 1;
        }
        $blogs = $this->blogRepository->findBlogs($limit, $offset);
        $pageall = round($this->blogRepository->countBlogs() / $limit, 0);
        
        $this->view->assign('blogs', $blogs);
        $this->view->assign('pageneu', $pageneu);
        $this->view->assign('pageall', $pageall);

        return $this->htmlResponse();
    }


    /**
     * action page
     *
     * @return ResponseInterface
     */
    public function pageAction() :ResponseInterface
    {
        $pageLoginUID = $this->settings['pageLoginUID'];
        $pageStartUID = $this->settings['pageStartUID'];
        $limit = 5;
        $offset = 0;
        $blogs = $this->blogRepository->findBlogs($limit, $offset);
        $this->view->assign('blogs', $blogs);
        $this->view->assign('pagestartuid', $pageStartUID);
        $this->view->assign('pageloginuid', $pageLoginUID);

        return $this->htmlResponse();
    }

    /**
     * action pagelist
     *
     * @return ResponseInterface
     */
    public function pagelistAction() :ResponseInterface
    {
        $pageStartUID = $this->settings['pageStartUID'];
        $limit = 5;
        $offset = 0;
        $blogs = $this->blogRepository->findBlogs($limit, $offset);
        $this->view->assign('blogs', $blogs);
        $this->view->assign('pagestartuid', $pageStartUID);

        return $this->htmlResponse();
    }

    /**
     * action pagegrid
     *
     * @return ResponseInterface
     */
    public function pagegridAction() :ResponseInterface
    {
        $pageStartUID = $this->settings['pageStartUID'];
        $limit = 6;
        $offset = 0;
        $blogs = $this->blogRepository->findBlogs($limit, $offset);
        $this->view->assign('blogs', $blogs);
        $this->view->assign('pagestartuid', $pageStartUID);

        return $this->htmlResponse();
    }

    /**
     * action pageall
     *
     * @return ResponseInterface
     */
    public function pageallAction() :ResponseInterface
    {
        $pageStartUID = $this->settings['pageStartUID'];
        $limit = 100;
        $offset = 0;
        $blogs = $this->blogRepository->findBlogs($limit, $offset);
        $this->view->assign('blogs', $blogs);
        $this->view->assign('pagestartuid', $pageStartUID);

        return $this->htmlResponse();
    }

    /**
     * action new
     *
     * @param Blog $blog
     * @return ResponseInterface
     * @throws Exception
     */
    public function newAction(Blog $blog) :ResponseInterface
    {
        $pid = $this->settings['storagePid'];
        $kat = $this->blogRepository->getBlogKategorien($pid);
        $kategorieArray = $this->blogRepository->getBlogKategorien($pid);
        $tag = $this->blogRepository->getBlogTags($pid);
        $this->view->assign('blog', $blog);
        $this->view->assign('kat', $kat);
        $this->view->assign('kategorieArray', $kategorieArray[0]);
        $this->view->assign('tag', $tag);

        return $this->htmlResponse();
    }

    /**
     * action create
     *
     * @param Blog $blog
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function createAction(Blog $blog) :ResponseInterface
    {
        // Required fields überprüfen
        $blogError = 0;
        if (strlen(trim($blog->getTitel())) == 0) {
            $blogError++;
        }
        if (strlen(trim($blog->getKurzfassung())) == 0) {
            $blogError++;
        }
        if (strlen(trim($blog->getInhalt())) == 0) {
            $blogError++;
        }

        // Daten zum Speichern bereitstellen
        $datetime = date('Y-m-d H:i:s');
        $blogName = $this->blogRepository->getBlogUsername();
        $blogEmail = $this->blogRepository->getBlogEmail();
        $blog->setName($blogName);
        $blog->setEmail($blogEmail);
        $blog->setDatum($datetime);

        // Wenn alle Felder ausgefüllt sind Speichern
        if ($blogError == 0) {
            $this->blogRepository->add($blog);
            $this->view->assign('blog', $blog);
        } else {
            $this->forward('new', 'Blog', NULL, array('blog' => $blog));
        }

        return $this->htmlResponse();
    }


    

}
