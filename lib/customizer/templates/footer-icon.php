<?php
function bc_customizer_footer_icon(){ ?>
  <?php if (current_user_can( 'edit_theme_options' )){ ?>
    <style>
      #bc_customizer_icon{
        position: fixed;
        bottom: 0;
        left: 0;
      }
      #bc_customizer_icon a{
        width: 100px;
        height: 20px;
        display: block;
        background: #08c;
        background: url(<?php echo plugins_url('/img/bottom-corner.png', __FILE__ ); ?>) no-repeat bottom left;
        color: #fff;
        font-size: 50px;
        opacity: 0.3;
        text-decoration: none;
        padding: 60px 0 20px 5px;
        -webkit-transition: all 350ms linear;
        -moz-transition: all 350ms linear;
        -ms-transition: all 350ms linear;
        -o-transition: all 350ms linear;
        transition: all 350ms linear;
      }
      #bc_customizer_icon a:hover{
        opacity: 1;
      }
    </style>
    <div id="bc_customizer_icon">
      <?php
      $current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      $href = add_query_arg( 'url', urlencode( $current_url ), wp_customize_url() ); ?>
      <a href="<?php echo $href; ?>"><i class="icon-cogs"></i></a>
    </div>
  <?php } ?>
  </div>
<?php }
add_action( 'bc_core_after_footer', 'bc_customizer_footer_icon' );
