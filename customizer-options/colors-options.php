<?php

function custom_theme_colors($wp_customize)
{
    // Sektionen
    $wp_customize->add_section('custom_theme_colors', array(
        'title' => __('Colors', 'my-theme'),
        'priority' => 30,
    ));

    // Optionen ######################################################################

    $wp_customize->add_setting('primary_color', array(
        'default' => '#0076e5',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'diwp_background_color', array(
        'label' => 'Primary Color',
        'section' => 'custom_theme_colors',
        'settings' => 'primary_color'
    )));


    $wp_customize->add_setting('dark_mode', array(
        'default' => 'dark',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_dark_mode_option',
    ));

    $wp_customize->add_control('dark_mode', array(
        'type' => 'select',
        'label' => __('Dark Mode', 'my-theme'),
        'section' => 'custom_theme_colors',
        'choices' => array(
            'dark' => __('Dark', 'my-theme'),
            'light' => __('Light', 'my-theme'),
            'system' => __('System', 'my-theme'),
        ),
    ));
}
add_action('customize_register', 'custom_theme_colors');


function sanitize_dark_mode_option($input)
{
    $valid_options = array('dark', 'light', 'system');

    if (in_array($input, $valid_options)) {
        return $input;
    }

    return 'system'; // Standardwert "System", falls eine ungültige Option übergeben wird.
}

function add_darkmode_class_to_html()
{
    $dark_mode_option = get_theme_mod('dark_mode', 'system');

    if ($dark_mode_option === 'dark') {
        echo '<script>
            document.documentElement.classList.add("darkmode");
        </script>';
    } elseif ($dark_mode_option === 'system') {
        echo '<script>
            if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
                document.documentElement.classList.add("darkmode");
            }
        </script>';
    }
}
add_action('wp_head', 'add_darkmode_class_to_html', 999);