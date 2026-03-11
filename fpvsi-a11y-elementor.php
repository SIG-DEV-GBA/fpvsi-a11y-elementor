<?php
/**
 * Plugin Name: FPVSI Accessibility Widget for Elementor
 * Plugin URI:  https://github.com/SIG-DEV-GBA/fpvsi-a11y-elementor
 * Description: Widget de accesibilidad para Elementor. Arrastra, configura y se aplica a todo el sitio.
 * Version:     1.0.0
 * Author:      SIG-DEV-GBA
 * Author URI:  https://github.com/SIG-DEV-GBA
 * License:     MIT
 * Text Domain: fpvsi-a11y-elementor
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'FPVSI_A11Y_EL_VERSION', '1.0.0' );
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
});

/**
 * Limpiar config al desactivar
 */
register_deactivation_hook( __FILE__, function () {
    delete_option( 'fpvsi_a11y_elementor_config' );
});
