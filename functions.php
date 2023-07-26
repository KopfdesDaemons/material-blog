<?php
function enqueue_custom_styles()
{
    wp_enqueue_style('custom-font', get_stylesheet_directory_uri() . '/fonts/fonts.css', array(), '1', 'all');
    wp_enqueue_style('custom-styles', get_stylesheet_directory_uri() . '/styles.css', array(), '1', 'all');
    wp_enqueue_style('searchform-styles', get_stylesheet_directory_uri() . '/searchform.css', array(), '1', 'all');
    wp_enqueue_style('header-styles', get_stylesheet_directory_uri() . '/header.css', array(), '1', 'all');
    wp_enqueue_style('footer-styles', get_stylesheet_directory_uri() . '/footer.css', array(), '1', 'all');
    wp_enqueue_style('sidebar-styles', get_stylesheet_directory_uri() . '/sidebar.css', array(), '1', 'all');
    wp_enqueue_style('comments-styles', get_stylesheet_directory_uri() . '/comments.css', array(), '1', 'all');
    wp_enqueue_style('single-styles', get_stylesheet_directory_uri() . '/single.css', array(), '1', 'all');
    wp_enqueue_style('404-styles', get_stylesheet_directory_uri() . '/404.css', array(), '1', 'all');
    wp_enqueue_style('fontawesome', get_stylesheet_directory_uri() . '/fonts/fontawesome/css/all.min.css', array(), '1', 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

// Anzahl der Wörter in der Vorschau im Feed
function mytheme_custom_excerpt_length($length)
{
    return 30;
}
add_filter('excerpt_length', 'mytheme_custom_excerpt_length', 999);

add_theme_support('post-thumbnails');

function custom_comment_reply_script()
{
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('custom-comment-reply', get_template_directory_uri() . '/js/custom-comment-reply.js', array('comment-reply'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'custom_comment_reply_script');

function register_my_menus()
{
    register_nav_menus(
        array(
            'header-menu' => __('Header Menu'),
            'footer-menu' => __('Footer Menu')
        )
    );
}
add_action('init', 'register_my_menus');

function sidebar()
{
    register_sidebar(array(
        'name' => __('Sidebar'),
        'id' => 'my-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'sidebar');


function custom_comment_form_fields($fields)
{
    $commenter = wp_get_current_commenter(); // Get the comment author's information

    // Change the label and input for the Author (Name) field
    $fields['author'] = '<p class="comment-form-author">' .
        '<input id="author" name="author" placeholder="&nbsp;" type="text" value="' . esc_attr($commenter['comment_author']) .
        '" size="30" ' . 'aria-required="true" required />' .
        '<label for="author">' . __('Your Name', 'domain') . '<span class="required">*</span></label>' .
        '</p>';

    // Change the label and input for the Email field
    $fields['email'] = '<p class="comment-form-email">' .
        '<input id="email" name="email" placeholder="&nbsp;" type="text" value="' . esc_attr($commenter['comment_author_email']) .
        '" size="30" ' . 'aria-required="true" required />' .
        '<label for="email">' . __('Your Email', 'domain') . '<span class="required">*</span></label>' .
        '</p>';

    // Add the URL field back with its label and input
    $fields['url'] = '<p class="comment-form-url">' .
        '<input id="url" name="url" placeholder="&nbsp;" type="text" value="' . esc_attr($commenter['comment_author_url']) .
        '" size="30" />' .
        '<label for="url">' . __('Your Website', 'domain') . '</label>' .
        '</p>';

    // Add more custom fields here if desired

    return $fields;
}

add_filter('comment_form_default_fields', 'custom_comment_form_fields');



// #########################################
// Share Buttons
// #########################################

function theme_slug_social_sharing()
{

    // Get current page URL.
    $page_url = get_permalink();

    // Get current page title.
    $page_title = get_the_title();

    // Create Array with Social Sharing links.
    $links = array(
        'facebook' => array(
            'url'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $page_url . '&t=' . $page_title,
            'text' => 'Facebook',
        ),
        'twitter' => array(
            'url'  => 'https://twitter.com/intent/tweet?text=' . $page_title . '&url=' . $page_url,
            'text' => 'Twitter',
        ),
        'reddit' => array(
            'url'  => 'https://reddit.com/submit?url=' . $page_url . '&title=' . $page_title,
            'text' => 'Reddit',
        )
    );

    // Create HTML list with Social Sharing links.
    $html = '<div class="social-sharing-links"><span>Share:</span><ul>';

    foreach ($links as $key => $link) {
        $html .= sprintf(
            '<li><a class="' . $link['text'] . '" href="%1$s" target="_blank">%2$s</a></li>',
            esc_url($link['url']),
            esc_html($link['text'])
        );
    }

    $html .= '</ul></div>';

    return $html;
}



// #########################################
// Costum Settings
// #########################################

// Funktion zum Hinzufügen einer benutzerdefinierten Einstellung im Customizer
function custom_theme_settings($wp_customize)
{
    // Sektionen
    // ######################################################################

    // Füge eine neue Sektion zum Customizer hinzu
    $wp_customize->add_section('custom_theme_options', array(
        'title' => __('Header', 'dein-theme-textdomain'),
        'priority' => 30,
    ));


    // Optionen
    // ######################################################################

    // Füge das Eingabefeld für die benutzerdefinierte Option hinzu
    $wp_customize->add_setting('search_button', array(
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_checkbox',
    ));

    $wp_customize->add_control('search_button', array(
        'type' => 'checkbox',
        'label' => __('Zeige Suchbutton', 'dein-theme-textdomain'),
        'section' => 'custom_theme_options',
    ));


    $wp_customize->add_setting('header_menu', array(
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_checkbox',
    ));

    $wp_customize->add_control('header_menu', array(
        'type' => 'checkbox',
        'label' => __('Zeige Menü im Header', 'dein-theme-textdomain'),
        'section' => 'custom_theme_options',
    ));

    $wp_customize->add_setting('searchbar', array(
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_checkbox',
    ));

    $wp_customize->add_control('searchbar', array(
        'type' => 'checkbox',
        'label' => __('Zeige Suchleiste', 'dein-theme-textdomain'),
        'section' => 'custom_theme_options',
    ));
}
add_action('customize_register', 'custom_theme_settings');

// Sanitize-Funktion zum Überprüfen des Checkbox-Werts (true/false)
function sanitize_checkbox($input)
{
    return (isset($input) && true === $input) ? true : false;
}
