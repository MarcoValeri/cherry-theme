<?php get_header(); ?>
<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();
        $postTitle = get_the_title();
        ?>
        <article>
            <h1><?= $postTitle; ?></h1>
            <div>
            <?php the_content(); ?>
            </div>
        </article>
        <?php
    }
}
?>
<?php get_footer(); ?>