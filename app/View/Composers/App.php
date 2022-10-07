<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
  /**
   * List of views served by this composer.
   *
   * @var array
   */
  protected static $views = [
    '*',
  ];

  /**
   * Data to be passed to view before rendering.
   *
   * @return array
   */
  public function with()
  {
    return [
      'siteName' => $this->siteName(),
      'primaryMenu' => $this->primaryMenu(),
      'utilityMenu' => $this->utilityMenu(),
      'footerMenu' => $this->footerMenu(),
    ];
  }

  /**
   * Returns the site name.
   *
   * @return string
   */
  public function siteName()
  {
    return get_bloginfo('name', 'display');
  }

  /**
   * Return the menu arguments
   */
  public function primaryMenu()
  {
    $args = array(
      'theme_location'    => 'primary_navigation',
      'menu_class'        => 'navbar-nav',
      'depth'             => 4,
      'walker'            => new \App\wp_bootstrap5_navwalker(),
    );
    return $args;
  }

  /**
   * Return the menu arguments
   */
  public function utilityMenu()
  {
    $args = array(
      'theme_location'    => 'utility_navigation',
      'menu_class'        => 'navbar-nav',
      'depth'             => 4,
      'walker'            => new \App\wp_bootstrap5_navwalker(),
    );
    return $args;
  }

  /**
   * Return the menu arguments
   */
  public function footerMenu()
  {
    $args = [
      'theme_location'    => 'footer_navigation',
      'container'         => '',
      'container_class'   => '',
      'menu_class'        => 'nav flex-column',
      'depth'             => 1,
      'link_after'        => ''
    ];
    return $args;
  }
}
