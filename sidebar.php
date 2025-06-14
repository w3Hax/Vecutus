<aside id="secondary" class="widget-area">
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php else : ?>
        <div class="widget">
            <h3 class="widget-title">Sample Widget</h3>
            <p>This is a sample widget. You can add widgets from the WordPress admin.</p>
        </div>
    <?php endif; ?>
</aside>
