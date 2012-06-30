<header id="banner" class="navbar navbar-fixed-top" role="banner">
  <?php roots_header_inside(); ?>
  <div class="navbar-inner">
    <div class="<?php echo WRAP_CLASSES; ?>">
     <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
    <?php
      if ( is_user_logged_in() ) { echo '<a class="brand" href="/profile">Courious</a>';
        } else { echo '<a class="brand" href="/">Courious</a>'; };
     ?>
     
    <nav id="nav-main" class="nav-collapse" role="navigation">
      <?php if ( is_user_logged_in() ) { echo wp_nav_menu(array('theme_location' => 'primary_navigation', 'walker' => new Roots_Navbar_Nav_Walker(), 'menu_class' => 'nav'));
        } else { echo  wp_nav_menu(array('theme_location' => 'visitor', 'walker' => new Roots_Navbar_Nav_Walker(), 'menu_class' => 'nav')); };
      ?>       
    </nav>
         <?php if ( is_user_logged_in() ) { echo '<a button class="btn pull-right" href="/wp-login.php?action=logout&amp;_wpnonce=1f8726f1a4">Logout</a>';
            } else { echo '<div class="btn-group pull-right"><div class="dropdown" id="loginform"><a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#loginform">Sign In<b class="caret"></b></a>'; };
          ?>
             <div class="dropdown-menu pull-right">
               <form class="form-vertical" name="loginform" id="loginform" action="/wp-login.php" method="post" style="margin: 0px">
                 <fieldset class='textbox' style="padding:10px">
                    <input type="text" name="log" id="user_login" class="input-medium" value="" size="20" tabindex="10" placeholder="Username" required="required"/>
  	                <input type="password" name="pwd" id="user_pass" class="input-medium" value="" size="20" tabindex="20" placeholder="Password" required="required" /><br>
		                <input type="submit" name="wp-submit" id="wp-submit" class="btn-primary pull-right" value="Log In" tabindex="100" /><br><hr>
	                  <input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" />&nbsp;Remember Me&nbsp;<a href="/wp-login.php?action=lostpassword" title="Password Lost and Found">What's my password?</a>
		                <input type="hidden" name="redirect_to" value="/profile" />
		                <input type="hidden" name="testcookie" value="1" />
                 </fieldset>
               </form>
            </div>
          </div>
    </div>
  </div>
</header>