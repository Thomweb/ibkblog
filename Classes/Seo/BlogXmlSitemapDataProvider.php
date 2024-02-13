<?php
declare(strict_types=1);

namespace Ibk\Ibkblog\Seo;

use \DateTime;
use Doctrine\DBAL\Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent;
use TYPO3\CMS\Seo\XmlSitemap\AbstractXmlSitemapDataProvider;
use TYPO3\CMS\Seo\XmlSitemap\Exception\MissingConfigurationException;
use Ibk\Ibkblog\Services\ServerServices;
//use Ibk\Ibkblog\Domain\Model\Blog;
use Ibk\Ibkblog\Domain\Repository\BlogRepository;


class BlogXmlSitemapDataProvider extends AbstractXmlSitemapDataProvider
{
    /**
     * @param ServerRequestInterface $request
     * @param string $key
     * @param array $config
     * @param ContentObjectRenderer|null $cObj
     * @throws MissingConfigurationException
     */
    public function __construct(
        ServerRequestInterface $request,
        string $key,
        array $config = [],
        ContentObjectRenderer $cObj = null
    )
    {
        parent::__construct($request, $key, $config, $cObj);
        $this->generateItems();
    }

    /**
     * Override Link Data generation for XML Sitemap
     *
     * @return void
     * @throws \Exception
     */
    public function generateItems(): void
    {
        $serverServices = GeneralUtility::makeInstance(ServerServices::class);
        $blogRepository = GeneralUtility::makeInstance(BlogRepository::class);
        $this->serverServices = $serverServices;

        $blogSlug = $this->config['url']['pageSlug'];

        $serverParams = $this->request->getServerParams();

        $blogPageLink = $serverServices->getBlogPageLink($serverParams, $blogSlug);

        $blogXmlList = $blogRepository->findXmlBlogs();

        foreach ($blogXmlList as $blogXmlListKey => $blogXmlListValue) {

            $datumTemp = new DateTime($blogXmlListValue['datum']);
            $datumFormatted = date_format($datumTemp, "c");

            $uri = (string)$blogXmlListValue['link'];

            $item = [
                'priority' => 0.5
            ];
            //lastMod
            $item['changefreq'] = 'weekly';
            $item['lastMod'] = $datumFormatted;
            $item['loc'] = $blogPageLink . $uri;
            $this->items[] = $item;
        }

    }
}