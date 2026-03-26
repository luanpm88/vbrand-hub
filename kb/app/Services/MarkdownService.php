<?php

namespace App\Services;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownService
{
    private MarkdownConverter $converter;

    public function __construct()
    {
        $config = [
            'heading_permalink' => [
                'html_class' => 'kb-heading-link',
                'id_prefix' => '',
                'insert' => 'after',
                'title' => '',
                'symbol' => '#',
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new HeadingPermalinkExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    public function toHtml(string $markdown): string
    {
        return $this->converter->convert($markdown)->getContent();
    }

    public function generateToc(string $html): array
    {
        $toc = [];
        preg_match_all('/<(h[23])[^>]*id="([^"]*)"[^>]*>(.*?)<\/h[23]>/s', $html, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $text = strip_tags($match[3]);
            // Remove the permalink symbol
            $text = trim(str_replace('#', '', $text));
            $toc[] = [
                'level' => $match[1] === 'h2' ? 2 : 3,
                'id' => $match[2],
                'text' => $text,
            ];
        }

        return $toc;
    }

    public function calculateReadingTime(string $markdown): int
    {
        $wordCount = str_word_count(strip_tags($markdown));
        return max(1, (int) ceil($wordCount / 200));
    }
}
