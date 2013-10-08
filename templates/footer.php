<footer class="content-info" role="contentinfo">
  <?php if ( shoestrap_getVariable( 'site_style' ) != 'boxed'  ) : ?><div class="<?php echo shoestrap_container_class(); ?>"><?php endif; ?>
    <div class="row">
      <?php shoestrap_footer_content(); ?>
      <?php shoestrap_footer_html(); ?>
    </div>
  </div>
</footer>

<?php wp_footer();