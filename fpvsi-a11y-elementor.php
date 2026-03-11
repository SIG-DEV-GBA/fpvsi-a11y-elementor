<?php
/**
 * Plugin Name: FPVSI Accessibility Widget for Elementor
 * Plugin URI:  https://github.com/SIG-DEV-GBA/fpvsi-a11y-elementor
 * Description: Widget de accesibilidad para Elementor. Arrastra, configura y se aplica a todo el sitio.
 * Version:     1.1.0
 * Author:      SIG-DEV-GBA
 * Author URI:  https://github.com/SIG-DEV-GBA
 * License:     MIT
 * Text Domain: fpvsi-a11y-elementor
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'FPVSI_A11Y_EL_VERSION', '1.1.0' );
define( 'FPVSI_A11Y_EL_DIR', plugin_dir_path( __FILE__ ) );
define( 'FPVSI_A11Y_EL_URL', plugin_dir_url( __FILE__ ) );

// Auto-update desde GitHub
require_once FPVSI_A11Y_EL_DIR . 'vendor/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$fpvsi_a11y_el_updater = PucFactory::buildUpdateChecker(
    'https://github.com/SIG-DEV-GBA/fpvsi-a11y-elementor/',
    __FILE__,
    'fpvsi-a11y-elementor'
);
$fpvsi_a11y_el_updater->setBranch( 'main' );
$fpvsi_a11y_el_updater->getVcsApi()->enableReleaseAssets();

/**
 * Verificar que Elementor está activo
 */
add_action( 'admin_notices', function () {
    if ( did_action( 'elementor/loaded' ) ) {
        return;
    }
    echo '<div class="notice notice-error"><p><strong>FPVSI Accessibility Widget for Elementor</strong> requiere <a href="https://wordpress.org/plugins/elementor/" target="_blank">Elementor</a> instalado y activo.</p></div>';
});

/**
 * Enqueue CSS y JS en el frontend
 */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'fpvsi-a11y-el',
        FPVSI_A11Y_EL_URL . 'assets/css/widget.css',
        [],
        FPVSI_A11Y_EL_VERSION
    );

    wp_enqueue_script(
        'fpvsi-a11y-el',
        FPVSI_A11Y_EL_URL . 'assets/js/widget.js',
        [],
        FPVSI_A11Y_EL_VERSION,
        true
    );
});

/**
 * Init global en wp_footer usando la config guardada por Elementor
 */
add_action( 'wp_footer', function () {
    $config = get_option( 'fpvsi_a11y_elementor_config', null );
    if ( empty( $config ) || ! is_array( $config ) ) {
        return;
    }
    // Asegurar flagsUrl correcto
    $config['flagsUrl'] = FPVSI_A11Y_EL_URL . 'assets/flags/';
    $json = wp_json_encode( $config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    echo '<script>document.addEventListener("DOMContentLoaded",function(){if(typeof FpvsiA11yWidget!=="undefined"){FpvsiA11yWidget.init(' . $json . ');}});</script>';
});

/**
 * Registrar widget de Elementor
 */
add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }
    add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
        require_once FPVSI_A11Y_EL_DIR . 'includes/elementor-widget.php';
        $widgets_manager->register( new \Fpvsi_A11y_Elementor_Widget() );
    });

    // Guardar config cuando Elementor guarda/publica una página
    add_action( 'elementor/document/after_save', function ( $document ) {
        $data = $document->get_elements_data();
        fpvsi_a11y_el_extract_config( $data );
    });
});

/**
 * Extraer config del widget desde los datos de Elementor al guardar
 */
function fpvsi_a11y_el_extract_config( $elements ) {
    foreach ( $elements as $element ) {
        if ( ! empty( $element['widgetType'] ) && $element['widgetType'] === 'fpvsi-a11y-widget' ) {
            $s = $element['settings'];
            $config = [
                'colors'     => [
                    'primary' => ! empty( $s['primary_color'] ) ? $s['primary_color'] : '#A10D5E',
                    'accent'  => ! empty( $s['accent_color'] )  ? $s['accent_color']  : '#F29429',
                ],
                'position'   => ! empty( $s['position'] ) ? $s['position'] : 'bottom-left',
                'offsetX'    => ! empty( $s['offset_x']['size'] ) ? (int) $s['offset_x']['size'] : 20,
                'offsetY'    => ! empty( $s['offset_y']['size'] ) ? (int) $s['offset_y']['size'] : 20,
                'zIndex'     => ! empty( $s['z_index_val'] ) ? (int) $s['z_index_val'] : 9998,
                'ttsLang'    => ! empty( $s['tts_lang'] ) ? $s['tts_lang'] : 'es-ES',
                'storageKey' => 'a11y-prefs',
            ];
            if ( ! empty( $s['trigger_icon'] ) ) {
                $config['triggerIcon'] = $s['trigger_icon'];
            }
            // Features
            $fmap = [ 'feat_fontSize' => 'fontSize', 'feat_contrast' => 'contrast', 'feat_bigCursor' => 'bigCursor', 'feat_textSpacing' => 'textSpacing', 'feat_dyslexiaFont' => 'dyslexiaFont', 'feat_highlightLinks' => 'highlightLinks', 'feat_tts' => 'tts', 'feat_languages' => 'languages' ];
            $features = [];
            foreach ( $fmap as $cid => $fk ) {
                if ( ! isset( $s[ $cid ] ) || $s[ $cid ] === 'yes' ) {
                    $features[] = $fk;
                }
            }
            $config['features'] = $features;
            // Labels
            $lmap = [ 'label_title' => 'title', 'label_fontSize' => 'fontSize', 'label_contrast' => 'contrast', 'label_bigCursor' => 'bigCursor', 'label_textSpacing' => 'textSpacing', 'label_dyslexiaFont' => 'dyslexiaFont', 'label_highlightLinks' => 'highlightLinks', 'label_ttsSection' => 'ttsSection', 'label_langSection' => 'langSection', 'label_footer' => 'footer', 'label_trigger' => 'trigger' ];
            $labels = [];
            foreach ( $lmap as $cid => $lk ) {
                if ( ! empty( $s[ $cid ] ) ) $labels[ $lk ] = $s[ $cid ];
            }
            if ( ! empty( $labels ) ) $config['labels'] = $labels;
            // Languages
            if ( ! empty( $s['languages_list'] ) && is_array( $s['languages_list'] ) ) {
                $langs = [];
                foreach ( $s['languages_list'] as $l ) {
                    if ( ! empty( $l['lang_code'] ) && ! empty( $l['lang_label'] ) && ! empty( $l['lang_flag'] ) ) {
                        $langs[] = [ 'code' => $l['lang_code'], 'label' => $l['lang_label'], 'flag' => $l['lang_flag'] ];
                    }
                }
                if ( ! empty( $langs ) ) $config['languages'] = $langs;
            }
            update_option( 'fpvsi_a11y_elementor_config', $config, false );
            return;
        }
        // Recurse into children
        if ( ! empty( $element['elements'] ) ) {
            fpvsi_a11y_el_extract_config( $element['elements'] );
        }
    }
}

/**
 * Limpiar config al desactivar
 */
register_deactivation_hook( __FILE__, function () {
    delete_option( 'fpvsi_a11y_elementor_config' );
});
