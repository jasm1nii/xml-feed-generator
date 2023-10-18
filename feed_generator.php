<?php
    // work in progress!!
    
    // GENERAL SETTINGS -------------------------------------------------------------------

    // the timezone referenced by the system for automatic timestamping.
    // suported timezones: https://www.php.net/manual/en/timezones.php
    $timezone = 'Asia/Jakarta';

    // FEED METADATA //////////////////////////////////////////////////////////////////////
    // certain characters must be escaped as HTML entities - note that XML only accepts five of them.
    // reference: https://en.wikipedia.org/wiki/List_of_XML_and_HTML_character_entity_references
    $feed_title = 'jasmine&apos;s b(rain)log | jasm1nii.xyz';
    $feed_subtitle = 'blog articles by jasmine';
    $blog_url = 'https://jasm1nii.xyz/blog/articles';
    $feed_url = 'https://jasm1nii.xyz/blog/articles/articles.xml';
    $author_name = 'jasmine';
    $author_email = 'contact@jasm1nii.xyz';
    $author_homepage = 'https://jasm1nii.xyz/';
    $feed_icon = 'https://jasm1nii.xyz/assets/media/itchio-textless-white.svg';
    $feed_logo = 'https://jasm1nii.xyz/assets/media/main/07042023-me_compressed.webp';

    // PATH TO FETCH PAGES FROM ///////////////////////////////////////////////////////////
    // __DIR__ is the directory where *this script* is located.
    // in my case, i first need to go up two directories to get to the site root.
    $site_root = dirname(__DIR__, 2);
    // once i'm there, i specify the parent directory where i keep all of my blog pages.
    $blog_root = $site_root.'/blog/articles';
    // then i specify a pattern that matches the path of each individual page.
    // my setup is /YYYY/MM/DD/entry.html
    $blog_entries = $blog_root.'/*/*/*/*.html';

    // ------------------------------------------------------------------------------------

    // create beginning of feed template.
    // reference for required elements: https://validator.w3.org/feed/docs/atom.html
    ob_start();
    date_default_timezone_set($timezone);

    echo    '<?xml version="1.0" encoding="utf-8"?>'
            .'<feed xmlns="http://www.w3.org/2005/Atom">'
            // optionally specify feed generator for debugging purposes.
            .'<generator uri="https://github.com/jasm1nii/xml-feed-generator" version="1.1">PHP feed generator by jasm1nii.xyz | last modified by the system at ' . strtoupper(date("h:i:sa")) . ' (GMT' . date('P') . ')</generator>'
            .'<title>' . $feed_title . '</title>'
            .'<subtitle>' . $feed_subtitle . '</subtitle>'
            .'<id>' . $blog_url . '</id>'
            .'<link rel="self" href="'. $feed_url .'" type="application/atom+xml"/>'
            .'<link rel="alternate" href="' . $blog_url .'" type="text/html"/>';

    // force libxml to parse all HTML elements, including HTML 5. by default, the extension can only read valid HTML 4.
    libxml_use_internal_errors(true);
    
    // match feed update time with the newest entry.
    $article_list = glob($blog_entries);
    $first_article = array_pop($article_list);
    $first_article_content = file_get_contents($first_article);
    $first_article_dom = new DOMDocument;
    $first_article_dom->loadHTML($first_article_content);
    $feed_updated = $first_article_dom->getElementsByTagName('time');
    $f = 0;
    foreach ($feed_updated as $feed_updated_text) {
        $feed_datetime = $feed_updated_text->getAttribute('datetime');
        if (strlen($feed_datetime) == 10) {
            echo    '<updated>' . $feed_datetime . 'T00:00:00' . date('P') .'</updated>';
        }
        elseif (strlen($feed_datetime) == 25 || strlen($feed_datetime) == 20) {
            echo    '<updated>' . $feed_datetime .'</updated>';
        }
        if(++$f > 0) break;
    }
    // if no RFC 3339 timestamp is found, use the file creation date.
    if (empty($feed_updated)) {
        $first_article_created = filectime($first_article);
        echo    '<updated>' . date(DATE_ATOM, $first_article_created) . '</updated>';
    }

    // rest of the template.
    echo    '<author>'
            .'<name>' . $author_name . '</name>'
            .'<email>' . $author_email . '</email>'
            .'<uri>' . $author_homepage . '</uri>'
            .'</author>'
            .'<icon>' . $feed_icon . '</icon>'
            .'<logo>' . $feed_logo . '</logo>';

    // output entries.
    $i = 0;
    foreach (array_reverse(glob($blog_entries)) as $article) {
        $article_content = file_get_contents($article);
        $article_dom = new DOMDocument;
        $article_dom->loadHTML($article_content);

        echo    '<entry>';

        // title
        $title = $article_dom->getElementsByTagName('h2');
        foreach ($title as $title_text) {
            echo    '<title>'.$title_text->nodeValue.'</title>';
        }

        // id
        echo    '<id>https://jasm1nii.xyz/blog/articles/' . ltrim($article, $blog_root) . '</id>';

        // alternate link
        echo    '<link rel="alternate" type="text/html" href="https://jasm1nii.xyz/blog/articles/' . ltrim($article, $blog_root) . '"/>';

        $updated = $article_dom->getElementsByTagName('time');
        $a = 0;
        foreach ($updated as $updated_text) {
            $timestamp = $updated_text->getAttribute('datetime');
            if (strlen($timestamp) == 10) {
                echo    '<updated>' . $timestamp . 'T00:00:00' . date('P'). '</updated>';
            }
            elseif (strlen($timestamp) == 25 || strlen($timestamp) == 20) {
                echo    '<updated>' . $timestamp .'</updated>';
            }
            if(++$a > 0) break;
        }
        // if no RFC 3339 timestamp is found, use the file creation date.
        if (empty($updated)) {
            $article_created = filectime($article);
            echo    '<updated>' . date(DATE_ATOM, $article_created) . '</updated>';
        }

        // summary
        $x = new DOMXPath($article_dom);
        $summary_class = 'p-summary';
        $summary = $x->query("//*[@class='" . $summary_class . "']");
        if ($summary->length > 0) {
            echo    '<summary type="html">';
            echo    $summary->item(0)->nodeValue;
            echo    '</summary>';
        }

        // content
        $content_class = 'e-content';
        $content = $x->query("//*[@class='" . $content_class . "']");
        if ($content->length > 0) {
            // strip line breaks and output a maximum of 500 characters.
            echo    '<content type="html">' . preg_replace('/\s\s+/', ' ',(substr($content->item(0)->nodeValue,0,500))) . '... (&lt;a href="https://jasm1nii.xyz/blog/articles/' . ltrim($article, $blog_root) . '"&gt;read more&lt;/a&gt;)' . '</content>';
        } else {
            // fallback for older markup
            $content_class = 'entry';
            $content = $x->query("//*[@class='" . $content_class . "']");
            if ($content->length >= 0) {
                echo    '<content type="html">' . 'whoops - this page contains markup that can&apos;t be parsed for feed-reader friendliness. read more on the website!' . '</content>';
            }
        }

        echo    '</entry>';

        // add no more than 10 entries.
        if(++$i > 9) break;
    }
    echo '</feed>';

    $xml_str = ob_get_contents();
    ob_end_clean();
    file_put_contents($blog_root.'/articles.xml', $xml_str);
?>