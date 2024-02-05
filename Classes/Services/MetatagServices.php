<?php
declare(strict_types=1);

namespace Ibk\Ibkblog\Services;

use Exception;
use DateTime;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use Ibk\Ibkblog\PageTitle\PageTitleProvider;
use Ibk\Ibkblog\Domain\Repository\BlogRepository;
use Ibk\Ibkblog\Domain\Model\Blog;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Object\ObjectManager\ObjectManager;

class MetatagServices {

    public function __construct(
        BlogRepository $blogRepository,
        MetaTagManagerRegistry $metaTagManagerRegistry,
        PageRenderer $pageRenderer,
        private readonly PageTitleProvider $pageTitleProvider
    )
    {
        $this->blogRepository = $blogRepository;
        $this->metaTagManagerRegistry = $metaTagManagerRegistry;
        $this->pageRenderer = $pageRenderer;
    }

    /*
    article:author - profile array - Writers of the article.
    article:section - string - A high-level section name. E.g. Technology
    */

    /**
     * setMetaDescription: Beschreibung in META-Tag <description> und diverse Open Graph / Twitter Cards schreiben
     *
     * @param string $_description
     * @return void
     */
    public function setMetaDescription(string $_description): void
    {
        if (strlen($_description) < 90) {
            $description = $_description . ' ✔ TYPO3 Wordpress SEO ✔ Blog Agentur IBK Köln';
        } elseif (strlen($_description) < 110) {
            $description = $_description . ' ✔ TYPO3 Wordpress SEO ✔ Agentur IBK';
        } elseif (strlen($_description) < 130) {
            $description = $_description . ' ✔ Blog Agentur IBK';
        } else {
            $description = $_description;
        }

        // Fill SEO Metatags from Blog Post
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('description');
        $metaTagManager->removeProperty('description');
        $metaTagManager->addProperty('description', $description);

        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('title');
        $metaTagManager->removeProperty('title');
        $metaTagManager->addProperty('title', $description);

        // Open Graph Description
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('og:description');
        $metaTagManager->removeProperty('og:description');
        $metaTagManager->addProperty('og:description', $description);

        // Open Graph ALT-Tag for Image
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('og:image:alt');
        $metaTagManager->removeProperty('og:image:alt');
        $metaTagManager->addProperty('og:image:alt', $description);

        // Twitter Cards Description
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('twitter:description');
        $metaTagManager->removeProperty('twitter:description');
        $metaTagManager->addProperty('twitter:description', $description);

        // Twitter Cards ALT-Tag for Image
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('twitter:image:alt');
        $metaTagManager->removeProperty('twitter:image:alt');
        $metaTagManager->addProperty('twitter:image:alt', $description);

        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('og:article:tag');
        $metaTagManager->removeProperty('og:article:tag');
        $metaTagManager->addProperty('og:article:tag', $description);
    }

    /**
     * setMetaName: Namen des Autors in META-Tag und Open Graph Attribute schreiben
     *
     * @param string $name
     * @return void
     */

    public function setMetaName(string $name): void
    {
        // Set META-Tag Author
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('author');
        $metaTagManager->addProperty('author', $name);

        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('og:article:author');
        $metaTagManager->addProperty('og:article:author', $name);
    }

    /**
     * setMetaTitle: META Description und andere Tags füllen
     *
     * @param string $_datum
     * @return void
     * @throws Exception
     */
    public function setMetaDate(string $_datum): void
    {
        // Calculate Timestamp and ISO-8601 format
        $datumPublishedTemp = new \DateTime($_datum);
        $datumPublishedArticle = $datumPublishedTemp->format('c');

        $datumExpirationTemp = new \DateTime('2099-12-31 11:59:59');
        $datumExpirationArticle = $datumExpirationTemp->format('c');

        // Set META Tags for Open Graph Article Timestamps
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('og:article:published_time');
        $metaTagManager->addProperty('og:article:published_time', $datumPublishedArticle);

        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('og:article:modified_time');
        $metaTagManager->addProperty('og:article:modified_time', $datumPublishedArticle);

        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('og:article:expiration_time');
        $metaTagManager->addProperty('og:article:expiration_time', $datumExpirationArticle);
    }

    /**
     * setMetaTitle: META Description und andere Tags füllen
     *
     * @param string $_title
     * @return void
     */
    public function setMetaTitle(string $_title): void
    {
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('og:title');
        $metaTagManager->removeProperty('og:title');
        $metaTagManager->removeProperty('canonical');
        $metaTagManager->addProperty('og:title', $_title . ' ✔ Blog Agentur IBK');

        // Fill Twitter Cards Metatags from Blog Post
        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('twitter:title');
        $metaTagManager->removeProperty('twitter:title');
        $metaTagManager->addProperty('twitter:title', $_title . ' ✔ Blog Agentur IBK');
    }

    /**
     * setStructuredData: Get Data from Blog Object into array
     *
     * Die Funktion schreibt Daten aus Blog Objekt in ein Array
     * Vorlage bei schema.org für Article und Author
     *
     * @param Blog $_blog
     * @param  array $_settings
     * @return array
     * @throws Exception
     */
    public function setStructuredData(Blog $_blog, array $_settings): array {

        // Get Link to Webseite Domain Root on each Server
        $server = $_SERVER['HTTP_HOST'];

        // Get Settings with configuration data
        $pageTBLink = $_settings['pageTBLink'];
        $imageBlogLink = $_settings['imageBlogLink'];

        // Get Data from Blog Posting
        $blogName = $_blog->getName();
        $blogEmail = $_blog->getEmail();
        $blogTitel = $_blog->getTitel();
        $blogKurzfassung = $_blog->getKurzfassung();
        $blogInhalt = strip_tags($_blog->getInhalt());
        $blogWordCount = str_word_count($blogInhalt);

        $blogTags = $_blog->getTags();

        // get Datum and format according to ISO 8601 Standard
        $blogDatumTemp = new DateTime($_blog->getDatum());
        $blogDatum = date_format($blogDatumTemp, "c");

        $blogLink = $_blog->getLink();

        $blogPostLink = '//' . $server . '/blog/' . $blogLink;

        // Fill Data Array for JSON response
        $dataArray = [];

        $dataArray['@context'] = 'https://schema.org/';
        $dataArray['@type'] = 'BlogPosting';
        $dataArray['@id'] = $blogPostLink;
        $dataArray['url'] = $blogPostLink;
        $dataArray['mainEntityOfPage'] = $blogTitel;
        $dataArray['headline'] = $blogTitel;
        $dataArray['description'] = $blogKurzfassung;
        $dataArray['articleBody'] = $blogInhalt;
        $dataArray['datePublished'] = $blogDatum;
        $dataArray['dateModified'] = $blogDatum;
        $dataArray['wordCount'] = $blogWordCount;

        if (count($blogTags) > 0) {
            foreach ($blogTags as $blogTagsKey => $blogTagsValue) {
                $dataArray['keywords'][] = $blogTagsValue->getName();
            }
        }

        $dataArray['author'] = [
            '@type' => 'Person',
            '@id' => $pageTBLink,
            'url' => $pageTBLink,
            'name' => $blogName
        ];

        $dataArray['image'] = [
            '@type' => 'ImageObject',
            '@id' => $imageBlogLink,
            'url' => $imageBlogLink,
            'height' => '362',
            'width' => '800'
        ];

        return $dataArray;
    }

    /**
     * setLink: Absoluten Link der Seite in Open Graph Attribute URL schreiben
     *
     * derzeit nicht verwendet
     *
     * @param string $url
     * @return void
     */
    public function setLink(string $url): void
    {
        if (strpos($url, "no_cache") > 0) {
            $url = substr($url, 0, (strpos($url, "no_cache")-1));
        }
        if (strpos($url, "cHash=") > 0) {
            $url = substr($url, 0, (strpos($url, "cHash=")-1));
        }

        #$metaTagManager = GeneralUtility::makeInstance(MetaTagManagerRegistry::class)->getManagerForProperty('og:url');
        #$metaTagManager->removeProperty('og:url');
        #$metaTagManager->addProperty('og:url', $url);
    }
}