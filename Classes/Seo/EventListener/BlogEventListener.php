<?php

declare(strict_types=1);

namespace Ibk\Ibkblog\Seo\EventListener;

use TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent;
use Ibk\Ibkblog\Domain\Model\Blog;
use Ibk\Ibkblog\Domain\Repository\BlogRepository;

final class BlogEventListener
{
    /**
     * @param Ibk\Ibkblog\Domain\Repository\BlogRepository $blogRepository
     */
    private BlogRepository $blogRepository;

    public function __construct(
        BlogRepository $blogRepository
    )
    {
        $this->blogRepository = $blogRepository;
    }

    public function setCanonicalLink(ModifyUrlForCanonicalTagEvent $event): void
    {
        // Get Server Params
        $blogHttp = 'http';
        $serverParams = $event->getRequest()->getServerParams();

        $blogHost = '';
        $blogSlug = '';

        if (array_key_exists('HTTP_HOST', $serverParams)) {
            $blogHost = $serverParams['HTTP_HOST'];
        }
        if (array_key_exists('REDIRECT_URL', $serverParams)) {
            $blogSlug = $serverParams['REDIRECT_URL'];
        }


        // Check for HTTPS Connection
        if (array_key_exists('REDIRECT_HTTPS', $serverParams)) {
            if ($serverParams['REDIRECT_HTTPS'] == 'on') {
                $blogHttp = 'https';
            }
        }

        // Get Params for Blog Posting if link is called from XML Sitemap
        if (array_key_exists('tx_ibkblog_blog', $event->getRequest()->getQueryParams())) {
            $arrayBlogEvent = $event->getRequest()->getQueryParams()['tx_ibkblog_blog'];

            // In case of function is called from XML Sitemap Canonical Link is rewritten
            if ((array_key_exists('blog', $arrayBlogEvent)) && (array_key_exists('action', $arrayBlogEvent))) {
                if ($arrayBlogEvent['action'] == 'show') {
                    $blogUid = $arrayBlogEvent['blog'];
                    $blog = $this->blogRepository->findByUid($blogUid);
                    $blogLink = $blog->getLink();

                    $newCanonical = $blogHttp . '://' . $blogHost . $blogSlug . '/' . $blogLink;

                    $event->setUrl((string)$newCanonical);
                }
            }
        }
    }
}