<?php get_header(); ?>
<div class="row">

  <div class="col-sm-12 blog-main">

    <?php
        $args =  array( 
        'post_type' => 'stores-post',
        'orderby' => 'menu_order',
        'order' => 'ASC'
      );

        $custom_query = new WP_Query( $args );

      if ( $custom_query->have_posts() ) : while ( $custom_query->have_posts() ): $custom_query->the_post(); 
        $meta = get_post_meta($post->ID, 'store_fields', true); ?>
          <div class="blog-post">
            <h2 class="blog-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_excerpt(); ?>
          </div>
        <?php endwhile; endif; wp_reset_postdata(); ?>

    <nav>
      <ul class="pager">
        <li><a href="#">Previous</a></li>
        <li><a href="#">Next</a></li>
      </ul>
    </nav>

  </div><!-- /.blog-main -->

</div><!-- /.row -->
<?php get_footer(); ?>
