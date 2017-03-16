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
      'hierarchical' => true,
      'has_archive' => true,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'thumbnail',
      ),
      'taxonomier' => array(
        'category',
        'post_tag',
      )
    ));
    register_taxonomy_for_object_type('category', 'stores-post');
    register_taxonomy_for_object_type('post_tag', 'stores-post');
  }

  add_action( 'init', 'create_stores_post' );

  function add_store_fields_meta_box() {
    add_meta_box(
      'store_fields_meta_box', // $id
      'Store Fields', // $title
      'show_store_fields_meta_box', // $callback
      'stores-post', // $screen
      'normal', // $context
      'high' // $priority
    );
  }
  
  add_action( 'add_meta_boxes', 'add_store_fields_meta_box' );

  function show_store_fields_meta_box() {
    global $post;
    $meta = get_post_meta($post->ID, 'store_fields', true); ?>
    
    <input type="hidden" name="store_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <!-- All fields will go here -->
    <p>
      <label for="store_fields[shop_name]">Shop Name</label>
      <br>
      <input type="text" name="store_fields[shop_name]" id="store_fields[shop_name]" class="regular-text" value="<?php echo $meta['shop_name']; ?>">
    </p>
    <p>
      <label for="store_fields[address]">Address</label>
      <br>
      <textarea name="store_fields[address]" id="store_fields[address]" rows="5" cols="30" style="width:500px;"><?php echo $meta['address']; ?></textarea>
    </p>

    <p>
      <label for="store_fields[country]">Country</label>
      <br>
      <select name="store_fields[country]" id="store_fields[country]">
          <option value="denmark" <?php selected( $meta['country'], 'denmark' ); ?>>Denmark</option>
          <option value="sweden" <?php selected( $meta['country'], 'sweden' ); ?>>Sweden</option>
      </select>
    </p>

    <p>
      <label for="store_fields[city]">City</label>
      <br>
      <select name="store_fields[city]" id="store_fields[city]">
          <option value="aarhus" <?php selected( $meta['city'], 'aarhus' ); ?>>Aarhus</option>
          <option value="copenhagen" <?php selected( $meta['city'], 'copenhagen' ); ?>>Copenhagen</option>
      </select>
    </p>

  <?php }

  function save_store_fields_meta( $post_id ) {
    // verify nonce
    if ( !wp_verify_nonce( $_POST['store_meta_box_nonce'], basename(__FILE__) ) ) {
      return $post_id; 
    }
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return $post_id;
    }
    // check permissions
    if ( 'page' === $_POST['post_type'] ) {
      if ( !current_user_can( 'edit_page', $post_id ) ) {
        return $post_id;
      } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
      }  
    }
    
    $old = get_post_meta( $post_id, 'store_fields', true );
    $new = $_POST['store_fields'];

    if ( $new && $new !== $old ) {
      update_post_meta( $post_id, 'store_fields', $new );
    } elseif ( '' === $new && $old ) {
      delete_post_meta( $post_id, 'store_fields', $old );
    }
  }
  add_action( 'save_post', 'save_store_fields_meta' );

  

?>
