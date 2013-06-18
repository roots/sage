<?php
function shoestrap_secondary_navbar() {

  if (shoestrap_getVariable( 'secondary_navbar_toggle') == 0)
    return;

  $left = shoestrap_getVariable( 'navbar_secondary_left');
  $right = shoestrap_getVariable( 'navbar_secondary_right');

  // Social links
  $networks = shoestrap_get_social_links();
  $social = "";
  foreach ($networks as $network) {
    if ($network['url'] == "")
      continue;
      $social .= '<a href="'.$network['url'].'" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top"><span class="glyphicon glyphicon-'.$network['icon'].'"></span></a>';
  }
  $social = '<div class="secondary_nav_social">' . $social . '</div>';
  
  if (has_nav_menu('secondary_navigation')) {
    // Navbar
    $menu = '<div class="secondary_nav_menu">';
    $menu .= wp_nav_menu(
        array (
            'theme_location' => 'secondary_navigation',
            'walker'         => new shoestrap_secondary_nav_walker,
            'depth'          => 0,
            'items_wrap'     => '<nav>%3$s</nav>',
            'echo'           => false
        )
    );
    $menu .= "</div>";      
  }

  // Text
  $text = shoestrap_getVariable( 'navbar_secondary_text');
  

  ?>

<style type="text/css">
.secondary_nav {
  height: 30px;
  line-height: 30px;
}
.secondary_nav_text {
  font-size: 13px;
}
.secondary_nav_menu {
  
}
.secondary_nav_menu span {
  font-weight: normal;
  padding: 0 5px;
}
.secondary_nav_social {

}
.secondary_nav_social a {
  margin-left: 7px;
  font-size: 14px;
  text-decoration: none;
}
</style>

    <div class="secondary_nav <?php echo shoestrap_navbar_class(); ?>">
      <div class="<?php echo shoestrap_container_class(); ?>">
        <?php if ($left != "none") : ?>
          <div class=".col col-lg-<?php if ($right == "") echo "12"; else echo '6'; ?>">
            <?php 
              if ($left == "menu" && has_nav_menu('secondary_navigation')) {
                echo $menu; 
              } else if ($left == "social") {
                echo $social;
              } else if ($left == "text") {
                echo '<div class="secondary_nav_text">'.$text."</div>";
              }
            ?>
          </div>
        <?php endif; ?>
        <?php if ($right != "none") : ?>
          <div class=".col col-lg-<?php if ($left == "") echo "12"; else echo '6'; ?>" style="text-align: right;">
            <?php 
              if ($right == "menu" && has_nav_menu('secondary_navigation')) {
                echo $menu; 
              } else if ($right == "social") {
                echo $social;
              } else if ($right == "text") {
                echo '<div class="secondary_nav_text">'.$text."</div>";
              }
            ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php 
}
add_action( 'shoestrap_pre_navbar', 'shoestrap_secondary_navbar' );


class shoestrap_secondary_nav_walker extends Walker
{
    public function walk( $elements, $max_depth )
    {
        $list = array ();

        foreach ( $elements as $item ) {
          if ($item->menu_item_parent == 0)
            $list[] = " <a href='$item->url'>$item->title</a> <span>/</span>";
        }
        $list[count($list)-1] = str_replace("/", '', $list[count($list)-1]);


        return join( "\n", $list );
    }
}

function shoestrap_secondary_navbar_css() {

  if (shoestrap_getVariable( 'secondary_navbar_toggle') == 0)
    return;

  if (shoestrap_getVariable( 'navbar_secondary_bg' ) == 1)
    $bg = shoestrap_getVariable( 'color_brand_primary' );
  else
    $bg = shoestrap_getVariable( 'color_brand_secondary' );

  $cl = shoestrap_getVariable( 'navbar_secondary_color' );
  $opacity = (intval(shoestrap_getVariable( 'navbar_secondary_opacity' )))/100;

  $style = '<style id="core.navbar.secondary">';
    $style .= '.secondary_nav { ';
      if ($opacity != 1 && $opacity != "") {
        $rgb = shoestrap_get_rgb($bg, true);
        $style .= 'background: rgb('.$rgb.');';
        $style .= 'background: rgba('.$rgb.', '.$opacity.');';
      } else {
        $style .= 'background: '.$bg.';';
      }
      $style .= 'color: '.$cl.';';
    $style .= '}';

    $style .= '.secondary_nav a { ';
      $style .= 'text-transform: uppercase;';
      $style .= 'color: '.$cl.';';
      $style .= 'font-size: 12px;';
    $style .= '}';


  $style .= '</style>';

  echo $style;
}
add_action( 'wp_head', 'shoestrap_secondary_navbar_css' );