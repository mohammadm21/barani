<?php
    header::totxt();
?>
User-agent: *
Disallow: /post
Disallow: /url
Disallow: /tag
Disallow: /?true
Disallow: /instagram
Disallow: /chat
Disallow: /page
Disallow: /users
Disallow: /blog
Disallow: /cat
Disallow: /?a=
Disallow: ajax/register.confrim
Disallow: /profile
Disallow: /player
Sitemap: http://<?php echo(safe::string($_SERVER["HTTP_HOST"])); ?>/sitemap.xml