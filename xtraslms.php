<?php
/**
 * Plugin Name: Xtras LearnDash
 * Description: Some xtras for LearnDash (another grid to courses with category/tag filter && show professors).
 * Version: 1.0.0
 * Author: Ernesto Ortiz
 * Author URI:
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: xtras-learndash
 * Domain Path: /languages
 */

// load plugin text domain
function xtraslms_init() {
    load_plugin_textdomain( 'xtras-learndash', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action('plugins_loaded', 'xtraslms_init');

// Styles & Scripts
function xtraslms_frontend_scripts() {
    //plugin style
    if(is_admin()) return;
    //IMAGESLOADED
    wp_register_script( 'imagesloaded', plugins_url('/js/imagesloaded.pkgd.min.js'), array( 'jquery' ) );
    wp_enqueue_script('imagesloaded');
    //ISOTOPE
    wp_register_script( 'isotope-js', plugins_url('/js/isotope.pkgd.min.js'), array( 'jquery' ) );
    wp_enqueue_script('isotope-js');
    //TOOLTIP
    wp_register_script( 'tooltip_js', plugins_url('/js/tooltips.min.js',__FILE__), array('jquery'));
    wp_enqueue_script('tooltip_js');
    //frontend script & style
    wp_register_script( 'xtraslms_js', plugins_url('/js/xtraslms.js',__FILE__), array('jquery'));
    wp_enqueue_script('xtraslms_js');
    wp_enqueue_style('xtraslms_style', plugins_url('/css/style.css',__FILE__));
}
add_action('wp_enqueue_scripts', 'xtraslms_frontend_scripts');

/** SHORTCODES **/
include "shortcodes.php";
?>
