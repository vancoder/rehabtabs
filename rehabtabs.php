<?php

/*
  Plugin Name: Rehabtabs
  Plugin URI: http://vancoder.ca/plugins/rehabtabs
  Description: Rehabtabs makes it easy to add pretty jQuery UI tabs to your pages, posts and custom posts using simple shortcodes.
  Version: 1.1.2
  Author: vancoder
  Author URI: http://vancoder.ca

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


include(plugin_dir_path( __FILE__ ) . 'options.php');

function rehabtabs_activate() {
    add_option( 'rehabtabs_options', array( 'theme' => rehabtabs_scan_themes( true ) ) );
}

register_activation_hook( __FILE__, 'rehabtabs_activate' );

function rehabtabs_tabs_handler( $atts, $content = '' ) {
    global $tabs, $panels;
    do_shortcode( $content );
    $list = $divs = '';
    foreach ( $tabs as $key => $tab ) {
        $tab_url = '';
        $tab_id = 'rehabtabs-' . uniqid();
        if ( $tab['ajax'] === 'true' ) {
            $tab_url = site_url() . '/' . $panels[$key];
        }
        $list .= '<li><a href="' . ($tab_url ? $tab_url : '#' . $tab_id) . '"><span>' . $tab['title'] . '</span></a></li>';
        if ( !$tab_url ) {
            $divs .= '<div id="' . $tab_id . '">';
            $divs .= do_shortcode( $panels[$key] );
            $divs .= '</div>';
        }
    }
    $html = '<div class="rehabtabs"><ul>';
    $html .= $list;
    $html .= '</ul>';
    $html .= $divs;
    $html .= '</div>';
    $tabs = null;
    $panels = null;
    return $html;
}

add_shortcode( 'rehabtabs', 'rehabtabs_tabs_handler' );

function rehabtabs_tab_handler( $atts, $content = '' ) {
    extract( shortcode_atts( array(
                'title' => '',
                'ajax' => ''
                    ), $atts ) );
    global $tabs, $panels;
    $tabs[] = array( 'title' => $title, 'ajax' => $ajax );
    $panels[] = $content;
    return null;
}

add_shortcode( 'rehabtab', 'rehabtabs_tab_handler' );

function rehabtabs_print_scripts() {
    $options = get_option( 'rehabtabs_options' );
    $data = array( );
    $data['fx'] = isset( $options['fx'] );
    $data['collapsible'] = isset( $options['collapsible'] );
    $data['cookie'] = isset( $options['cookie'] );
    $data['spinner'] = $options['spinner'];
    wp_enqueue_script( 'jquery-ui-tabs' );
    if ( $data['cookie'] ) {
        wp_enqueue_script( 'jquery-cookie', WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) ) . 'js/jquery.cookie.js' );
    }
    wp_enqueue_script( 'rehabtabs', WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) ) . 'js/rehabtabs.packed.js' );
    wp_localize_script( 'rehabtabs', 'options_object', $data );
}

add_action( 'wp_print_scripts', 'rehabtabs_print_scripts' );

function rehabtabs_print_styles() {
    $options = get_option( 'rehabtabs_options' );
    if ( 'none' != $options['theme'] ) {
        wp_enqueue_style( 'rehabtabs', WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) ) . 'themes/' . $options['theme'] );
    }
}

add_action( 'wp_print_styles', 'rehabtabs_print_styles' );
?>