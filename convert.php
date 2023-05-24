<?php
if (!defined('PERCH_RUNWAY')) include($_SERVER['DOCUMENT_ROOT'].'/perch/runtime.php');

// change directory, folder is created in root
chdir($_SERVER['DOCUMENT_ROOT']);

$targetfoldername = 'convertedposts'; // Name of the folder you want to convert to. If not present, it will be created in the same folder this very file resides in. Do not use the same name as this file.
//from Perch
$postspage = '/news';
$postuid = 'postSlug'; // Field ID of the post slug.
$mainimageid = 'image'; // Field ID of the main post image.
$templateforoutput = '_hidden/_convert-posts'; // Template used to generate the text files for Kirby.
// for Kirby
$textfilebasename = 'post'; // Base name of the file containing the text contents of the post.
$kirbyimagetemplate = 'blocks/image'; // Template used for rendering the image in the page.

if(!is_dir($targetfoldername)) {
    if (!mkdir($targetfoldername, 0700)) {
        die('😕 Creating target folder <i>' . $targetfoldername . '</i> failed.<br>');
    } else {
        print '📁 Target folder <i>' . $targetfoldername . '</i> created<br>';
    }
}

// get the blog post data
// need to use 'count' because perch_blog_custom defaults to 10
$posts = perch_blog_custom(array(
    // 'page' => $postspage,
    'sort' => 'postDateTime',
    'sort-order' => 'DESC',
    'count' => 100,
    'skip-template' => true));

foreach ($posts as $post) {
    $postfolder = $post[$postuid];
    chdir($targetfoldername);
    if(!is_dir($postfolder)) {
        if (!mkdir($postfolder, 0766)) {
            die('<p>&nbsp;&nbsp;|– 😕 Creating post folder <i>' . $postfolder . '</i> failed.<br>');
        } else {
            print '<p>&nbsp;&nbsp;|– 📂 Post folder <i>' . $postfolder . '</i> created.<br>';
        }
    }
    chdir($postfolder);
    $randomid = substr(md5(rand()), 0, 8) . '-' . substr(md5(rand()), 0, 4) . '-' . substr(md5(rand()), 0, 4) . '-' . substr(md5(rand()), 0, 4) . '-' . substr(md5(rand()), 0, 12);
    $postcontent = perch_blog_custom(array(
        'page' => $postspage,
        'template' => $templateforoutput,
        'skip-template' => true,
        'filter' => $postuid,
        'value' => $post[$postuid],
        'return-html' => true,
        'data' => [
            'randomid' => $randomid
        ]
    ));
    if(!file_put_contents($textfilebasename . '.txt', $postcontent['html'])) {
        die('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|– 😕 Writing post text file to <i>' . $postfolder . '</i> failed.<br>');
    } else {
        print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|– 📄  Post text file created in <i>' . $postfolder . '</i>.<br>';
    }
    
    // Main image for each post
    $image = $post[$mainimageid] ? $post[$mainimageid] : false;
    $imagefilename = $image ? end(explode('/', $image)) : false;
    reset($image);
    if ($image && !copy($_SERVER['DOCUMENT_ROOT'] . $image, $imagefilename)) {
        $errors= error_get_last();
        echo "COPY ERROR: " . $errors['type'] . '<br>';
        echo $errors['message'] . '<br>';
        die('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|– 😕 Copying post image <i>' . $imagefilename . '</i> failed.<br>');
    } else if ($image) {
        print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|– 🎆 Post image <i>' . $imagefilename . '</i> copied.<br>';
    }
    if($image && !file_put_contents($imagefilename . '.txt', 'Template: ' . $kirbyimagetemplate)) {
        die('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|– 😕 Writing post image text file to <i>' . $postfolder . '</i> failed.<br>');
    } else if ($image) {
        print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|– 📄 Post image text file created in <i>' . $postfolder . '</i>.<br>';
    }
    print '</p>';
    chdir('../../');
    
}
// print '<pre>';
// print_r($posts);
// print '</pre>';