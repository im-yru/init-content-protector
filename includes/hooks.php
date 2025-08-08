<?php
// Hook với priority cao để chạy sau khi shortcode và caption đã được xử lý
add_filter( 'the_content', 'init_plugin_suite_content_protector_filter_post_content', 99, 1 );

function init_plugin_suite_content_protector_filter_post_content( $content ) {
    // Chỉ chạy trên frontend và single post
    if ( is_admin() || ! is_singular() ) {
        return $content;
    }
    
    // Lấy post hiện tại
    global $post;
    if ( ! $post ) {
        return $content;
    }
    
    // Lấy cấu hình plugin
    $option = get_option( INIT_PLUGIN_SUITE_CONTENT_PROTECTOR_OPTION, [] );
    $allowed_post_types = $option['post_types'] ?? [];
    
    // Kiểm tra post type có được bảo vệ không
    if ( ! in_array( $post->post_type, $allowed_post_types, true ) ) {
        return $content;
    }
    
    // Xử lý inject noise nếu được bật
    if ( ! empty( $option['inject_noise'] ) && $option['inject_noise'] === '1' ) {
        $content = init_plugin_suite_content_protector_inject_noise( $content );
    }

    $content = init_plugin_suite_content_protector_replace_keywords( $content, $post->ID );
    
    // Xử lý encrypt mode
    if ( ! empty( $option['content_mode'] ) && $option['content_mode'] == 'encrypt' ) {
        // Không cần wpautop vì content đã được xử lý đầy đủ
        $encrypted = wp_json_encode( init_plugin_suite_content_protector_encrypt( $content ) );
        
        if ( $encrypted ) {
            add_action( 'wp_enqueue_scripts', function() use ( $encrypted ) {
                wp_add_inline_script(
                    'init-content-protector-script',
                    'var InitContentEncryptedPayload = ' . $encrypted . ';',
                    'before'
                );
            }, 110 );

            $protected_content  = '<div class="imc-skeleton-line"></div>';
            $protected_content .= '<div class="imc-skeleton-line short"></div>';
            $protected_content .= '<div class="imc-skeleton-line"></div>';
            $protected_content .= '<div class="imc-skeleton-line"></div>';
            $protected_content .= '<div class="imc-skeleton-line short"></div>';
            return $protected_content;
        } else {
            return '<div class="uk-alert-danger">Encryption failed.</div>';
        }
    } else {
        // Content đã được xử lý đầy đủ, không cần wpautop
        return $content;
    }
}

// WordPress content processing priorities:
// Priority 8: wpautop
// Priority 9: do_blocks (Gutenberg blocks)
// Priority 10: Capital_P_dangit
// Priority 11: do_shortcode
// Priority 12: img_caption_shortcode
// Priority 99: Chạy sau tất cả để đảm bảo content đã được xử lý đầy đủ
?>