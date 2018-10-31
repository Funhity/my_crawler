<?php
$android_url = 'Location: https://www.pgyer.com/ezjL';
$ios_url = 'Location: https://itunes.apple.com/cn/app/wonder.wiki/id1131097236?l=en&mt=8';
if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
    header($android_url);
    exit(0);
} else if (strpos($_SERVER['HTTP_USER_AGENT'], 'android')) {
    header($android_url);
    exit(0);
} else {
    header($ios_url);
    exit(0);
}
?>