<?php

// Use svg('error', size) to load an svg added to assets/icons/* folder
function svg($icon_name, $height, $width){
  $dir_name = get_stylesheet_directory();
  $url = "{$dir_name}/dist/icons/svg/icon-{$icon_name}.svg";
  $url_png = "{$dir_name}/dist/icons/png/icon-{$icon_name}.png";
  $svg = file_get_contents($url);
  $fallback = "onerror='this.onerror=null; this.src=".$url_png."'";
  echo "<div class='svg-icon {$icon_name}' style='height:{$height}; width:{$width};'>" . $svg . "</div>";
}

// Use imgSrc like imgSrc('image-name.jpg') to access images in the dist/images/ folder
function imgSrc($img_name){
  echo get_stylesheet_directory_uri() . "/dist/images/{$img_name}";
}
