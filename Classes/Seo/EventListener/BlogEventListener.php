<?php

declare(strict_types=1);

namespace Ibk\Ibkblog\Seo\EventListener;

use Ibk\Ibkblog\Services\ServerServices;
use TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent;
use Ibk\Ibkblog\Domain\Repository\BlogRepository;

final class BlogEventListener
{
    /**
     * @param BlogRepository $blogRepository
     * @param ServerServices $serverServices
     */
    private BlogRepository $blogRepository;
    private ServerServices $serverServices;

    public function __construct(
        BlogRepository $blogRepository,
        ServerServices $serverServices
    )
    {
        $this->blogRepository = $blogRepository;
        $this->serverServices = $serverServices;
    }

    public function setCanonicalLink(ModifyUrlForCanonicalTagEvent $event): void
    {
        $serverParams = $event->getRequest()->getServerParams();

        $blogPageLink = $this->serverServices->getBlogPageLink($serverParams);

        // Get Params for Blog Posting if link is called from XML Sitemap
        if (array_key_exists('tx_ibkblog_blog', $event->getRequest()->getQueryParams())) {
            $arrayBlogEvent = $event->getRequest()->getQueryParams()['tx_ibkblog_blog'];

            // In case of function is called from XML Sitemap Canonical Link is rewritten
            if ((array_key_exists('blog', $arrayBlogEvent)) && (array_key_exists('action', $arrayBlogEvent))) {
                if ($arrayBlogEvent['action'] == 'show') {
                    $blogUid = $arrayBlogEvent['blog'];
                    $blog = $this->blogRepository->findByUid($blogUid);
                    $blogLink = $blog->getLink();

                    $newCanonical = $blogPageLink . $blogLink;

                    $event->setUrl((string)$newCanonical);
                }
            }
        }
    }
}