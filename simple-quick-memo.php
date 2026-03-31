<?php
/*
Plugin Name: Simple Quick Memo
Description: ダッシュボードに自分専用のメモを残せます。
Version: 1.0
Tested up to: 6.9.4
Requires PHP: 8.3.23
Author: masato shibuya(Image-box Co., Ltd.)
*/

// フックのタイミングを 'wp_add_dashboard_widget' から 'wp_dashboard_setup' に変更
add_action('wp_dashboard_setup', 'my_custom_quick_memo_setup');

function my_custom_quick_memo_setup() {
    wp_add_dashboard_widget(
        'quick_memo_widget',
        'クイックメモ',
        'render_quick_memo_widget'
    );
}

function render_quick_memo_widget() {
    if (isset($_POST['my_memo_text'])) {
        update_option('quick_memo_data', sanitize_textarea_field($_POST['my_memo_text']));
        echo '<div style="color:green; margin-bottom:10px;">保存しました！</div>';
    }

    $memo = get_option('quick_memo_data', '');
    ?>
    <form method="post">
        <textarea name="my_memo_text" style="width:100%; height:120px; border:1px solid #ccc; padding:10px; box-sizing:border-box;"><?php echo esc_textarea($memo); ?></textarea>
        <?php submit_button('メモを保存'); ?>
    </form>
    <?php
}


require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';

$updateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/ms13th-cyber/wp-smart-checklist/',
    __FILE__,
    'wp-smart-checklist'
);

$updateChecker->setBranch('main');