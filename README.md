# perch-blog-posts-to-kirby
For converting Perch blog posts into Kirby text files

Firstly, `convert.php` will run the process and it goes in `perch/templates/blog/pages`. Modify the settings in this file to your own requirements and make sure that the `perch_blog_custom` function in the script is appropriate for your site. Then, you will need to create a page on your site that uses this as the master page. Then, open the page in your browser. The script will generate the blog posts in a folder called `/convertedposts` in the root of your site as long as the following template is in place.

Secondly, the process uses `_convert-posts.html` as the Perch template to create the Kirby text files in `/convertedposts`. In my case, I have `_convert-posts.html` in `perch/templates/blog/_hidden` but you can put it wherever you want. Just make sure that `_convert-posts.html` is in the correct location before you run `convert.php`.

With big thanks to Nils Mielke who wrote the initial script that I have adapted for my own use.
