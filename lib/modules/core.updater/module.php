<?php

if ( is_admin() ) {
  require_once 'class-theme-updater.php';
  new GitHub_Theme_Updater;
}