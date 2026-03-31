<?php
/*
Plugin Name: Simple Quick Memo
Description: ダッシュボードに自分専用のメモを残せます。
Version: 1.2
Tested up to: 6.9.4
Requires PHP: 8.3.23
Author: masato shibuya(Image-box Co., Ltd.)
*/

// 直接アクセスを禁止
if (!defined('ABSPATH')) exit;

/**
 * 1. ダッシュボードウィジェットの登録
 */
add_action('wp_dashboard_setup', 'sqm_add_dashboard_widget');

function sqm_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'quick_memo_widget',
        'クイックメモ',
        'sqm_render_memo_widget'
    );
}

/**
 * 2. ウィジェットの表示と保存処理
 */
function sqm_render_memo_widget() {
    // 保存処理（POSTされた場合）
    if (isset($_POST['sqm_memo_text']) && check_admin_referer('sqm_save_memo')) {
        update_option('sqm_memo_data', sanitize_textarea_field($_POST['sqm_memo_text']));
        echo '<div style="color:#856404; background-color:#fff3cd; border:1px solid #ffeeba; padding:8px; margin-bottom:10px; border-radius:4px;">メモを更新しました！</div>';
    }

    $memo = get_option('sqm_memo_data', '');
    ?>

    <style>
        /* 付箋風のデザイン */
        .sqm-container {
            position: relative;
            background: #fff9c4; /* 付箋の黄色 */
            padding: 15px;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid #fbc02d; /* アクセント線 */
            border-radius: 0 0 5px 5px;
        }
        .sqm-textarea {
            width: 100%;
            height: 160px;
            background: transparent;
            border: none;
            resize: vertical;
            font-family: "Helvetica Neue", Arial, "Hiragino Kaku Gothic ProN", "Hiragino Sans", sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            outline: none;
            margin: 0;
            padding: 0;
        }
        .sqm-textarea:focus {
            box-shadow: none;
            outline: none;
        }
        .sqm-footer {
            margin-top: 12px;
            text-align: right;
        }
        /* ボタンの色を付箋に合わせる */
        .sqm-save-btn {
            background: #fbc02d !important;
            border-color: #f9a825 !important;
            color: #333 !important;
            text-shadow: none !important;
            font-weight: bold !important;
            box-shadow: 0 1px 0 #f9a825 !important;
        }
        .sqm-save-btn:hover {
            background: #f9a825 !important;
            border-color: #f57f17 !important;
        }
    </style>

    <div class="sqm-container">
        <form method="post" action="">
            <?php wp_nonce_field('sqm_save_memo'); // セキュリティ用のトークン ?>
            <textarea
                name="sqm_memo_text"
                class="sqm-textarea"
                placeholder="ここにメモを書いてください..."
            ><?php echo esc_textarea($memo); ?></textarea>

            <div class="sqm-footer">
                <input type="submit" class="button button-primary sqm-save-btn" value="メモを保存">
            </div>
        </form>
    </div>

    <?php
}


require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';

$updateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/ms13th-cyber/wp-smart-checklist/',
    __FILE__,
    'wp-smart-checklist'
);

$updateChecker->setBranch('main');