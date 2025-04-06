<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use League\HTMLToMarkdown\HtmlConverter;
use League\HTMLToMarkdown\Converter\TableConverter;


class HtmlToMarkdownService
{
    public function __construct()
    {
    }

    /**
     * Convert HTML to Markdown.
     *
     * @param string $html
     * @param bool $purifyFirst (optional) Whether to purify the HTML first using HTML Purifier https://github.com/ezyang/htmlpurifier
     * @param string|null $baseUrl (optional) Base URL for relative links
     * @return string
     */
    public function convert(string $html, bool $purifyFirst = true, string $baseUrl = null): string
    {
        // If you want to purify the HTML first, you can use a library like HTML Purifier
        if ($purifyFirst) {
            $htmlBefore = $html;
            $html = $this->purifyHtml($html, $baseUrl);
            Log::debug(sprintf('HTML Purified - Length (Before: %s, After: %s)', number_format(strlen($htmlBefore)), number_format(strlen($html))), [
                'original' => $htmlBefore,
                'purified' => $html,
                'baseUrl' => $baseUrl,
            ]);
        }

        // Use a library like `league/html-to-markdown` to convert HTML to Markdown

        $converter = new HtmlConverter([
            'strip_tags' => true,
            'preserve_comments' => false,
            'strip_placeholder_links' => true,
            'italic_style' => '*',
            'bold_style' => '**',

            'hard_break' => true, // By default, br tags are converted to two spaces followed by a newline character as per traditional Markdown. Set hard_break to true to omit the two spaces, as per GitHub Flavored Markdown (GFM).

            'use_autolinks' => false,  // By default, a tags are converted to the easiest possible link syntax, i.e. if no text or title is available, then the <url> syntax will be used rather than the full [url](url) syntax. Set use_autolinks to false to change this behavior to always use the full link syntax.

            'header_style' => 'atx', // Setext (underlined) headers are the default for H1 and H2. If you prefer the ATX style for H1 and H2 (# Header 1 and ## Header 2), set header_style to 'atx' in the options array when you instantiate the object:
        ]);
        $converter->getEnvironment()->addConverter(new TableConverter());
        $markdown = $converter->convert($html);

        // final cleaning of the markdown

        // replace 3+ \n with 2 \n while its matching in a loop
        while (preg_match('/(\n){3,99}/', $markdown)) {
            $markdown = preg_replace('/(\n){3,99}/', "\n\n", $markdown);
        }

        // remove empty lines
        $markdown = preg_replace('/^\s*$/m', '', $markdown);

        Log::debug(sprintf('HTML to Markdown - Length (<html> Before: %s, <markdown> After: %s)', number_format(strlen($html)), number_format(strlen($markdown))), [
            'original' => $html,
            'markdown' => $markdown,
            'baseUrl' => $baseUrl,
        ]);
        return $markdown;
    }

    /**
     * Purify HTML using HTML Purifier.
     *
     * @param string $html
     * @param string|null $baseUrl (optional) Base URL for relative links
     * @return string
     */
    private function purifyHtml(string $html, string $baseUrl = null): string
    {
        // Use HTML Purifier to clean the HTML
        $config = \HTMLPurifier_Config::createDefault();

        // build the config based on demo tool post call:
        $parameters = [
            'HTML.Allowed' => 'a[href],p,div,h1,h2,h3,h4,h5,strong,br,b,em,span,code,pre,blockquote,ul,ol,li',
//            'HTML.AllowedAttributes' => 'a[href],img[src]',
//            'HTML.AllowedClasses' => '',
//            'HTML.ForbiddenElements' => '',
//            'HTML.ForbiddenAttributes' => '',
            'HTML.Trusted' => false,

            'HTML.Doctype' => null,
            'HTML.SafeObject' => false,
            'HTML.TidyLevel' => 'heavy',
            'URI.DisableExternalResources' => true,
            'URI.Munge' => null,
            'AutoFormat.RemoveEmpty.Predicate' => 'colgroup:,th:,td:,iframe:src',
            'AutoFormat.RemoveEmpty' => true,
            'AutoFormat.RemoveSpansWithoutAttributes' => true,
            'CSS.AllowedProperties' => null,
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.DisplayLinkURI' => false,
            'AutoFormat.Linkify' => false,
            'URI.MakeAbsolute' => true,
        ];
        if (!empty($baseUrl)) {
            $parameters['URI.Base'] = $baseUrl;
        }

        $config->loadArray($parameters);
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($html);
    }
}
