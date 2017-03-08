<?php
  function nature_scripts() {
    wp_enqueue_style('style', get_template_directory_uri().'/assets/css/style.css');
    wp_enqueue_style('bootstrap', get_template_directory_uri().'/assets/bootstrap/css/bootstrap.min.css', array(), '3.3.7');
    wp_enqueue_script('bootstrap', get_template_directory_uri().'/assets/bootstrap/js/bootstrap.min.js', array(), '3.3.7', true);
  }

  // Add Google Fonts
  function nature_google_fonts() {
          wp_register_style('OpenSans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800');
          wp_enqueue_style( 'OpenSans');
      }

  add_action('wp_print_styles', 'nature_google_fonts');

  add_action('wp_enqueue_scripts','nature_scripts');

  add_theme_support('title-tag');


  // Custom Settings
  function custom_settings_add_menu() {
    add_menu_page('Custom Settings', 'Custom Settings', 'manage_options', 'custom-settings', 'custom_settings_page', null, 99 );
  }

  add_action('admin_menu', 'custom_settings_add_menu');


  // Create Custom Global Settings
  function custom_settings_page() { ?>
    <div class="wrap">
      <h1>Custom Settings</h1>
      <form method="POST" action="options.php">
          <?php 
              settings_fields('section');
              do_settings_sections('theme-options');
              submit_button();
          ?>
      </form>
    </div>
  <?php }


  // Twitter
  function setting_twitter() { ?>
    <input type="text" name="twitter" id="twitter" value="<?php echo get_option( 'twitter' ); ?>" />
  <?php }


  // Github
  function setting_github() { ?>
    <input type="text" name="github" id="github" value="<?php echo get_option( 'github' ); ?>" />
  <?php }


  // Facebook
  function setting_facebook() { ?>
    <input type="text" name="facebook" id="facebook" value="<?php echo get_option( 'facebook' ); ?>" />
  <?php }


  // Set up page to show, accept and save options field
  function custom_settings_page_setup() {
    add_settings_section( 'section', 'All Settings', null, 'theme-options' );
    add_settings_field( 'twitter', 'Twitter URL', 'setting_twitter', 'theme-options', 'section' );
    add_settings_field( 'github', 'Github URL', 'setting_github', 'theme-options', 'section' );
    add_settings_field( 'facebook', 'Facebook URL', 'setting_facebook', 'theme-options', 'section' );

    register_setting('section', 'twitter');
    register_setting('section', 'github');
    register_setting('section', 'facebook');
  }

  add_action( 'admin_init', 'custom_settings_page_setup' );

  // Featured Images
  add_theme_support('post-thumbnails');

  // Function Creating Custom Posts like address of retail stores
  // Custom Post Type
  function create_stores_post() {
    register_post_type( 'stores-post',
        array(
        'labels' => array(
            'name' => __( 'Stores' ),
            'singular_name' => __( 'Store' ),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'custom-fields'
        )
    ));
  }

  add_action( 'init', 'create_stores_post' );

?>
