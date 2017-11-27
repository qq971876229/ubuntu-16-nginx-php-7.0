<?php
/*
Template Name:产品中心
*/
?>
<?php
  get_header();
  if(have_posts()):
    while(have_posts()):
      the_post();
?>


      <div class="row">
        <div class="col-md-12">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_01-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_02-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_03-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_04-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_05-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_06-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_07-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_08-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_09-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_10-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_11-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_12-.jpg" alt="" width="100%">
          <img src="<?php echo get_template_directory_uri(); ?>/img/kuaibo_13-.jpg" alt="" width="100%">
        </div>
      </div>

<?php
  endwhile;
  endif;
  get_footer();
?>
