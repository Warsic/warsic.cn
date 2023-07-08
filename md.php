<?php
function curl_raw($url, $content) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json",
              "User-Agent: " . $_SERVER['HTTP_USER_AGENT']));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $json_response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    return $json_response;
}

$markdown_filename = $_GET['f'];

$markdown_text = file_get_contents($markdown_filename);

$render_url = 'https://api.github.com/markdown';

$request_array['text'] = $markdown_text;
$request_array['mode'] = 'gfm';

$html_article_body = curl_raw($render_url, json_encode($request_array));

$header_text = file_get_contents('src/header.html');

echo '<!DOCTYPE html><html lang="zh-CN"><head><meta charset="utf-8">';
echo '<title>' . 'Warsic 音乐社团 - ' . $markdown_filename . '</title>';
echo '<link rel="stylesheet" type="text/css" href="styles/purple.css">';
echo '</head>';
echo '<body class="purple">' . $header_text . '<div class="content"><article class="markdown-body entry-content container-lg" itemprop="text">';
echo $html_article_body;
echo '</article></div>';
echo '<div class="container-fluid footer"><div class="row clearfix"><div class="col-md-12 column">';
echo '<div class="jumbotron"><div class="container"><center>';
echo '<p>Warsic 音乐社团 © 2023 All Rights Reserved.</p>';
echo 'Github: <a href="https://github.com/Warsic/warsic.cn">https://github.com/Warsic/warsic.cn</a> | Email: <a href="mailto://Warsic.WuChang@gmail.com">Warsic.WuChang@gmail.com</a><br>Bilibili: <a href="https://space.bilibili.com/2011729430">https://space.bilibili.com/2011729430</a> | 静态页面使用 <a href="https://github.com/FangCunWuChang/php-github-markdown">php-github-markdown</a> 构建';
echo '</center></div></div></div></div></div>';
echo '</body></html>';