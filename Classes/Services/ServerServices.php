<?php
declare(strict_types=1);

namespace Ibk\Ibkblog\Services;

use Exception;
use DateTime;
use Ibk\Ibkblog\Domain\Repository\BlogRepository;
use Ibk\Ibkblog\Domain\Model\Blog;

class ServerServices {

    /**
     * Link Parameter für Canonical und XML Link
     *
     * @param array $_serverParams
     * @param string $_blogSlug
     * @return string
     */
    public function getBlogPageLink(
        array $_serverParams,
        string $_blogSlug = ''
    ): string {
        $blogHttp = 'http';
        $blogHost = '';
        $blogSlug = '';

        if (array_key_exists('HTTP_HOST', $_serverParams)) {
            $blogHost = $_serverParams['HTTP_HOST'];
        }

        if ($_blogSlug == '') {
            if (array_key_exists('REDIRECT_URL', $_serverParams)) {
                $blogSlug = $_serverParams['REDIRECT_URL'];
            }
        } else {
            $blogSlug = '/' . $_blogSlug;
        }

        // Check for HTTPS Connection
        if (array_key_exists('REDIRECT_HTTPS', $_serverParams)) {
            if ($_serverParams['REDIRECT_HTTPS'] == 'on') {
                $blogHttp = 'https';
            }
        } elseif (array_key_exists('SERVER_NAME', $_serverParams)) {
            if ($_serverParams['SERVER_NAME'] != 'localhost.agentur2020') {
                $blogHttp = 'https';
            }
        }

        return $blogHttp . '://' . $blogHost . $blogSlug . '/';
    }
}