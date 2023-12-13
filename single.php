<?php
/**
 * The template for displaying all single posts and attachments
 */

get_header();
?>
<div>
    <h1>single.php</h1>
    <?php
    while (have_posts()) {
        the_post();

        $postContent = get_the_content();
        ?>
        <article>
            <?= $postContent; ?>
        </article>
        <?php
    }
    ?>
</div>