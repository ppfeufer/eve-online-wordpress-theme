<?php
defined('ABSPATH') or die();
?>

<article id="post-<?php the_ID(); ?>" <?php \post_class('clearfix'); ?>>
    <?php
    if(\has_post_thumbnail()) {
        ?>
        <a href="<?php \the_permalink(); ?>" title="<?php \the_title_attribute('echo=0'); ?>">
            <figure class="post-loop-thumbnail">
                <?php
                if(\function_exists('\fly_get_attachment_image')) {
                    echo \fly_get_attachment_image(\get_post_thumbnail_id(), 'post-loop-thumbnail');
                } else {
                    \the_post_thumbnail('post-loop-thumbnail');
                } // END if(\function_exists('\fly_get_attachment_image'))
                ?>
            </figure>
        </a>
        <?php
    } // END if(has_post_thumbnail())
    ?>

    <header class="entry-header">
        <h2 class="entry-title">
            <a href="<?php \the_permalink(); ?>" title="<?php \printf(\esc_attr__('Permalink to %s', 'eve-online'), \the_title_attribute('echo=0')); ?>" rel="bookmark">
                <?php \the_title(); ?>
            </a>
        </h2>
        <aside class="entry-details">
            <p class="meta">
                <?php
                echo \WordPress\Themes\EveOnline\Helper\PostHelper::getPostMetaInformation();
                \WordPress\Themes\EveOnline\Helper\PostHelper::getPostCategoryAndTags();
                \edit_post_link(__('Edit', 'eve-online'));
                ?>
            </p>
        </aside><!--end .entry-details -->
    </header><!--end .entry-header -->

    <section class="post-content clearfix">
        <?php
        if(\is_search()) { // Only display excerpts without thumbnails for search.
            ?>
            <div class="entry-summary clearfix">
                <?php \the_excerpt(); ?>
            </div><!-- end .entry-summary -->
            <?php
        } else {
            ?>
            <div class="entry-content clearfix">
                <?php
                echo \wpautop(\do_shortcode(WordPress\Themes\EveOnline\Helper\StringHelper::cutString(\get_the_content(), '140')));
                \printf('<a href="%1$s"><span class="read-more">' . \__('Read more', 'eve-online') . '</span></a>', \get_the_permalink());
                ?>
            </div><!-- end .entry-content -->
            <?php
        } // END if(is_search())
        ?>
    </section>
</article><!-- /.post-->
