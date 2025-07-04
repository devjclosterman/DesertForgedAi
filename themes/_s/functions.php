<?php
/**
 * _s functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _s
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function _s_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on _s, use a find and replace
		* to change '_s' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( '_s', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', '_s' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'_s_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', '_s_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function _s_content_width() {
	$GLOBALS['content_width'] = apply_filters( '_s_content_width', 640 );
}
add_action( 'after_setup_theme', '_s_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function _s_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', '_s' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', '_s' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', '_s_widgets_init' );

/**
 * Enqueue scripts and styles.
 */


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


add_action('admin_menu', 'custom_combined_admin_menu');

function custom_combined_admin_menu() {
    // Main menu item
    add_menu_page(
        'My Admin Page',                // Page title
        'My Admin Menu',               // Menu label
        'manage_options',              // Capability
        'my-admin-page-slug',          // Slug
        'my_admin_page_content',       // Function to display content
        'dashicons-admin-generic',     // Icon
        100                            // Position
    );

    // Optional: Redundant submenu for "Overview"
    add_submenu_page(
        'my-admin-page-slug',
        'Overview',
        'Overview',
        'manage_options',
        'my-admin-page-slug',
        'my_admin_page_content'
    );

    // Submenu for bot logs
    add_submenu_page(
        'my-admin-page-slug',
        'LLaMA Bot Logs',
        'LLaMA Bot Logs',
        'manage_options',
        'llama-bot-logs',
        'llama_logs_page'
    );
}

function my_admin_page_content() {
    echo '<div class="wrap"><h1>Welcome to My Admin Page</h1><p>This is your main admin dashboard.</p></div>';
}

function llama_logs_page() {
    echo '<div class="wrap"><h1>LLaMA Bot Logs</h1>';

    $db_path = ABSPATH . 'chat_logs.db';

    if (!file_exists($db_path)) {
        echo "<p><strong>Error:</strong> chat_logs.db not found at <code>{$db_path}</code></p></div>";
        return;
    }

    try {
        $db = new PDO('sqlite:' . $db_path);
        $results = $db->query("SELECT timestamp, user_id, prompt, reply FROM logs ORDER BY id DESC LIMIT 50");

        echo '<table class="widefat fixed striped"><thead><tr>
                <th>Timestamp</th><th>User ID</th><th>Prompt</th><th>Reply</th>
              </tr></thead><tbody>';

        foreach ($results as $row) {
            echo '<tr>
                <td>' . esc_html($row['timestamp']) . '</td>
                <td>' . esc_html($row['user_id']) . '</td>
                <td>' . esc_html($row['prompt']) . '</td>
                <td>' . esc_html($row['reply']) . '</td>
              </tr>';
        }

        echo '</tbody></table></div>';
    } catch (PDOException $e) {
        echo '<p><strong>Database Error:</strong> ' . esc_html($e->getMessage()) . '</p></div>';
    }
}

function _s_enqueue_bot_script() {
    wp_enqueue_script('bot-chat', get_template_directory_uri() . '/js/bot.js', array(), null, true);
}
add_action('wp_enqueue_scripts', '_s_enqueue_bot_script');


function _s_scripts() {
	wp_enqueue_style( '_s-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( '_s-style', 'rtl', 'replace' );

	wp_enqueue_script( '_s-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', '_s_scripts' );


wp_enqueue_script('llama-bot', get_template_directory_uri() . '/js/bot.js', array(), _S_VERSION, true);

function enqueue_tailwind() {
    wp_enqueue_style('tailwind', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_tailwind');

