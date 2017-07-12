<?php
$i = &$d['dResults']
?>
<div class="blog-post-area">
    <h2 class="title text-center">
        <?php echo Anchor(GetRewriteUrl($d['pUrl'], $i['Alias']), $i['Title']); ?>
    </h2>
    <div class="single-blog-post">
        <?php echo GetDecodeHTML($i['Contents']); ?>
    </div>
</div><!--/blog-post-area-->