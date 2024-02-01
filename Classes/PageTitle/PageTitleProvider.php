<?php

declare(strict_types=1);

namespace Ibk\Ibkblog\PageTitle;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

final class PageTitleProvider extends AbstractPageTitleProvider
{
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}