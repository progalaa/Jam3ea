<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9 ]><html class="ie9" lang="en"><![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--><html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $_['_Lang']; ?>"><!--<![endif]-->
    <head>
        <base href="<?php echo SITE_URL; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $d['dTitle']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="<?php echo $d['dDescription']; ?>" />
        <meta name="keywords" content="<?php echo $d['dKeywords']; ?>" />
        <meta name="author" content="<?php echo $s['sSiteName']; ?>" />
        <meta property="og:title" content="<?php echo $d['dTitle']; ?>" />
        <meta property="og:url" content="<?php echo $d['pUrl']; ?>" />
        <meta property="og:image" content="<?php echo $d['pImage']; ?>" />
        <meta property="og:site_name" content="<?php echo $s['sSiteName']; ?>" />
        <meta property="og:description" content="<?php echo $d['dDescription']; ?>" />

        <link rel="canonical" href="<?php echo $d['pfUrl']; ?>" />
        <meta name="copyright" content="<?php echo $s['sSiteName']; ?>" />
        <meta name="Classification" content="<?php echo $s['sSiteName']; ?>" />
        <meta name="DC.language" scheme="UTF-8" content="<?php echo $_['_Lang']; ?>" />
        <meta name="dcterms.contributor" content="<?php echo $d['pfUrl']; ?>" />
        <meta name="dcterms.coverage" content="ecommerce" />
        <meta name="dcterms.creator" content="<?php echo $d['pfUrl']; ?>" />
        <meta name="dcterms.description" content="<?php echo $d['dDescription']; ?>" />
        <meta name="dcterms.format" content="text/html" />
        <meta name="dcterms.identifier" content="<?php echo $d['pfUrl']; ?>" />
        <meta name="dcterms.publisher" content="<?php echo $s['sSiteName']; ?>" />
        <meta name="dcterms.rights" content="<?php echo $s['sSiteName']; ?>" />
        <meta name="dcterms.source" content="<?php echo $s['sSiteName']; ?>" />
        <meta name="dcterms.subject" content="<?php echo $d['dTitle']; ?>" />
        <meta name="dcterms.title" content="<?php echo $d['dTitle']; ?>" />
        <link rel="alternate" type="application/rss+xml" href="<?php echo SITE_URL; ?>RSS" />
        <!--<link rel="icon" href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>img/favicon.ico" />-->

        <link rel="dns-prefetch" href="//connect.facebook.net" />
        <link rel="dns-prefetch" href="//s-static.ak.facebook.com" />
        <link rel="dns-prefetch" href="//ssl.google-analytics.com" />
        <link rel="dns-prefetch" href="//cm.g.doubleclick.net" />
        <link rel="dns-prefetch" href="//stats.g.doubleclick.net" />
        <link rel="dns-prefetch" href="//www.facebook.com" />

    </head>
    <body>
        <pre>
            <?php
            var_dump($d);
            ?>
        </pre>
    </body>
</html>