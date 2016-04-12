<?php

function rehabtabs_menu() {
    add_options_page( 'Rehabtabs options', 'Rehabtabs', 'manage_options', 'rehabtabs', 'rehabtabs_options' );
}

add_action( 'admin_menu', 'rehabtabs_menu' );

function rehabtabs_register_settings() {
    register_setting( 'rehabtabs_settings_group', 'rehabtabs_options' );
    add_settings_section( 'rehabtabs_section', '', 'rehabtabs_section_text', 'plugin' );
    add_settings_field( 'rehabtabs_theme', 'Theme', 'rehabtabs_theme_callback', 'plugin', 'rehabtabs_section' );
    add_settings_field( 'rehabtabs_fx', 'Use fade transition', 'rehabtabs_fx_callback', 'plugin', 'rehabtabs_section' );
    add_settings_field( 'rehabtabs_collapsible', 'Make tabs collapsible', 'rehabtabs_collapsible_callback', 'plugin', 'rehabtabs_section' );
    add_settings_field( 'rehabtabs_cookie', 'Use cookie to remember tab state', 'rehabtabs_cookie_callback', 'plugin', 'rehabtabs_section' );
    add_settings_field( 'rehabtabs_spinner', 'Ajax loading message', 'rehabtabs_spinner_callback', 'plugin', 'rehabtabs_section' );
}

add_action( 'admin_init', 'rehabtabs_register_settings' );

function rehabtabs_section_text() {
    
}

function rehabtabs_theme_callback() {
    $options = get_option( 'rehabtabs_options' );
    echo '<select name="rehabtabs_options[theme]" id="theme" class="postform">';
    rehabtabs_scan_themes();
    echo '<option value="none"' . selected( $options['theme'], 'none' ) . '>None. I\'ll handle the theme myself.</option>';
    echo '</select>';
}

function rehabtabs_scan_themes( $get_default = false ) {
    $options = get_option( 'rehabtabs_options' );
    $themes_directory = plugin_dir_path( __FILE__ ) . 'themes';
    if ( class_exists( 'FilesystemIterator' ) ) {
        $themes = new DirectoryIterator( $themes_directory );
        foreach ( $themes as $theme ) {
            if ( $theme->isDir() && !$theme->isDot() ) {
                $theme_items = new FilesystemIterator( $themes_directory . '/' . $theme );
                foreach ( $theme_items as $theme_item ) {
                    if ( pathinfo( $theme_item->getFilename(), PATHINFO_EXTENSION ) == 'css' ) {
                        $theme_path = $theme . '/' . $theme_item->getFilename();
                        if ( $get_default )
                            return $theme_path;
                        echo '<option value="' . $theme_path . '"' . selected( $options['theme'], $theme_path, false ) . '>' . ucfirst( $theme ) . '</option>';
                    }
                }
            }
        }
    } else {
        // Alternative method for PHP version < 5.3.0
        $themes = scandir( $themes_directory );
        foreach ( $themes as $theme ) {
            if ( is_dir( $themes_directory . '/' . $theme ) ) {
                foreach ( glob( $themes_directory . '/' . $theme . '/*.css' ) as $theme_item ) {
                    $path_parts = pathinfo( $theme_item );
                    $theme_path = $theme . '/' . $path_parts['filename'] . '.css';
                    if ( $get_default )
                        return $theme_path;
                    echo '<option value="' . $theme_path . '"' . selected( $options['theme'], $theme_path, false ) . '>' . ucfirst( $theme ) . '</option>';
                }
            }
        }
    }
}

function rehabtabs_fx_callback() {
    $options = get_option( 'rehabtabs_options' );
    echo '<label for="fx"><input type="checkbox" name="rehabtabs_options[fx]" id="fx" value="1" ' . checked( isset( $options['fx'] ) ? $options['fx'] : 0, 1, false ) . ' /></label>';
}

function rehabtabs_collapsible_callback() {
    $options = get_option( 'rehabtabs_options' );
    echo '<label for="collapsible"><input type="checkbox" name="rehabtabs_options[collapsible]" id="collapsible" value="1" ' . checked( isset( $options['collapsible'] ) ? $options['collapsible'] : 0, 1, false ) . ' /></label>';
}

function rehabtabs_cookie_callback() {
    $options = get_option( 'rehabtabs_options' );
    echo '<label for="cookie"><input type="checkbox" name="rehabtabs_options[cookie]" id="cookie" value="1" ' . checked( isset( $options['cookie'] ) ? $options['cookie'] : 0, 1, false ) . ' /></label>';
}

function rehabtabs_spinner_callback() {
    $options = get_option( 'rehabtabs_options' );
    echo '<input type="text" name="rehabtabs_options[spinner]" id="spinner" value="' . (isset( $options['spinner'] ) ? $options['spinner'] : '') . '" />';
}

function rehabtabs_options() {
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $tabs = array( 'options' => 'Rehabtabs Options', 'usage' => 'Rehabtabs Usage' );
    $current = isset( $_GET['tab'] ) ? $_GET['tab'] : 'options';
    echo '<div class="wrap">';
    screen_icon();
    echo '<h2 class="nav-tab-wrapper">';
    foreach ( $tabs as $tab => $name ) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=rehabtabs&tab=$tab'>$name</a>";
    }
    echo '</h2>';

    switch ( $current ) {
        case 'options' :
            echo '<form method="post" action="options.php">';
            settings_fields( 'rehabtabs_settings_group' );
            do_settings_sections( 'plugin' );
            echo '<p class="submit"><input name="Submit" type="submit" class="button-primary" value="Save Changes" /></p>';
            echo '</form>';
            break;
        case 'usage' :
            ?>
            <h3>Basic usage</h3>
            <pre>
[rehabtabs]
[rehabtab title="Tab 1"]Content of tab 1[/rehabtab]
[rehabtab title="Tab 2"]Content of tab 2[/rehabtab]
[rehabtab title="Tab 3"]Content of tab 3[/rehabtab]
[/rehabtabs]
            </pre>

            <h3>Ajax usage</h3>
            <p>To load tab contents via ajax, set the shortcode's <em>ajax</em> attribute to <em>true</em>. Then, between the opening and closing tags, enter the path of the page to load.</p>
            <pre>
[rehabtab title="Ajax tab" ajax="true"]my-directory/my-page[/rehabtab]
            </pre>

            <h3>Themes</h3>
            <p>Rehabtabs comes loaded with a few themes, but here’s how to install more.</p>
            <ol>
                <li>Go to the jQuery ThemeRoller</li>
                <li>Choose your theme and click the download button</li>
                <li>Deselect all components, then reselect Tabs (under Widgets)</li>
                <li>Download, giving you a directory called jquery-ui-x.x.xx.custom</li>
                <li>Open the subdirectory called css, and copy your chosen theme folder</li>
                <li>Paste this folder into plugins/rehabtabs/themes</li>
                <li>Your theme should now be available on the Settings page</li>
            </ol>
            <?php
            break;
    }
    echo '</div>';
}
?>