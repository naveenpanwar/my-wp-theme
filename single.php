<?php get_header(); ?>
<div class="row">

  <div class="col-sm-12 blog-main">

    <?php
        if ( have_posts() ): while ( have_posts() ): the_post();
          get_template_part('content-single', get_post_format());
          if ( comments_open() || get_comments_number() ):
            comments_template();
          endif;
        endwhile; endif;
    ?>

    <nav>
      <ul class="pager">
        <li><a href="#">Previous</a></li>
        <li><a href="#">Next</a></li>
      </ul>
    </nav>

  </div><!-- /.blog-main -->

</div><!-- /.row -->
<?php get_footer(); ?>
