<?php
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ($comment_count === 1) {
                printf(__('One comment on "%s"', 'vecutus'), get_the_title());
            } else {
                printf(_n('%1$s comment on "%2$s"', '%1$s comments on "%2$s"', $comment_count, 'vecutus'), number_format_i18n($comment_count), get_the_title());
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
            ));
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'vecutus'); ?></p>
    <?php endif; ?>

    <?php comment_form(); ?>
</div>