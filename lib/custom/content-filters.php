<?php 
function replace_content($content)
{
  // search and replace -- make sure you include all your commas
  $textSearch = array(
  		"®",                         // 1
  		"™",                         // 2
     	"Eastern Wire and Conduit",  // 3
     	"Eastern Wire & Conduit",    // 4
     	"Allied Tube and Conduit",    // 5
     	"Allied Tube &amp; Conduit",  // 6	
     	"Armor Duct",                // 7
  		"SS-40",                     // 8
  		"SS40",                      // 9
  		"SS-30",                     // 10
  		"SS30",                      // 11
  		"SS-20",                     // 12
  		"SS20",                      // 13
  		"SS-15",                     // 14
  		"SS15",                      // 15
  		"SS®",                       // 16
  		"POLYKOTE",                  // 17
  		"POLYKOTE®",                 // 18
  		"Polykote®",                 // 19
  		"POLYKOTE™",                 // 20
  		"Polykote™",                 // 21
  		"Protect N Duct",            // 22
  		"Protect-n-Duct",            // 23
  		"KORTECH",                   // 24
  		"Razor-Ribbon",              // 25
  		"RAZOR RIBBON",              // 26
  		"RAZOR-RIBBON",              // 27
  		"Flo Coat",                  // 28
  		"INSTABARRIER®",              // 29
  		"HOOK® BARB",                 // 30
  		"MC-Quik®",                     // 31
  		"MC-Stat",                     // 32
  		"MC-Stat®",                     // 33
  		"MC Stat®",                     // 34
  		"MC Lite®",                     // 35
  		"MC-Lite®",                     // 36
  		"MC-Lite",                     // 37
  		"MC-Plus®",                     // 38
  		"MC Plus",                     // 39
      "MC TUFF",                     // 40
  	);
  $textReplace = array(
  		"",                          // 1
  		"",                          // 2
  		"Eastern Wire + Conduit",    // 3
  		"Eastern Wire + Conduit",    // 4
     	"Allied Tube & Conduit",     // 5
     	"Allied Tube & Conduit",     // 6
  		"Armor-Duct",                // 7
  		"SS 40",                     // 8
  		"SS 40",                     // 9
  		"SS 30",                     // 10
  		"SS 30",                     // 11
  		"SS 20",                     // 12
  		"SS 20",                     // 13
  		"SS 15",                     // 14
  		"SS 15",                     // 15
  		"SS",                        // 16
  		"Polykote",                  // 17
  		"Polykote",                  // 18
  		"Polykote",                  // 19
  		"Polykote",                  // 20
  		"Polykote",                  // 21
  		"Protect-N-Duct",            // 22
  		"Protect-N-Duct",            // 23
  		"Kortech",                   // 24
  		"Razor-Ribbon",              // 25
  		"Razor-Ribbon",              // 26
  		"Razor-Ribbon",              // 27
  		"Flo-Coat",                  // 28
  		"INSTABARRIER",              //29
  		"HOOK BARB",                //30
  		"MC-Quik",                     //31
  		"MC Stat",                     //32
  		"MC Stat",                     //33
  		"MC Stat",                     //34
  		"MC Lite",                     //35
  		"MC Lite",                     //36
  		"MC Lite",                     //37
  		"MC-Plus",                     //38
  		"MC-Plus",                     //39
  		"MC Tuff",                     //40
  	);
  $content = str_replace($textSearch, $textReplace, $content);
  return $content;
}
add_filter('the_content','replace_content', 0);
add_filter('the_title','replace_content', 0);
add_filter('the_field','replace_content', 0);
add_filter('get_field','replace_content', 0);
add_filter('wp_nav_menu','replace_content', 0);


function add_reg($content)
{
  // search and replace
  $textSearch = array(
  		"Eastern Wire + Conduit", // 1
     	"Allied Tube & Conduit",  // 2
  		"Armor-Duct",             // 3
  		"SS 40",                  // 4
  		"SS 30",                  // 5
  		"SS 20",                  // 6
  		"SS 15",                  // 7
  		"Polykote",               // 8
  		"Protect-N-Duct",         // 9
  		"Kortech",                // 10
  		"Razor-Ribbon",           // 11
  		"Flo-Coat",               // 12
  		"INSTABARRIER",           // 13
  		"HOOK BARB",              // 14
  		"NEC",                    // 15
  		"UL ",                     // 16
  		"CEC",                     // 17
  		"MC-Quik",                     // 18
  		"MC Stat",                     // 19
  		"MC Lite",                     // 20
  		"Home Run Cable",                     // 21
  		"AC-Lite",                     // 22
  		"MC Tuff",                     // 23
  		"MC-Plus",                     // 24
  		"HCF-90",                     // 25
      "HCF-Lite",                     // 26
      "AC-90",                     // 27
      "Fire Alarm",                     // 28
      "Super Neutral Cable",                     // 29
  	);
  $textReplace = array(
  		"Eastern Wire + Conduit<sup>&trade;</sup>",   // 1
     	"Allied Tube &amp; Conduit<sup>&reg;</sup>",  // 2
  		"Armor-Duct<sup>&trade;</sup>",               // 3
  		"SS 40<sup>&reg;</sup>",                      // 4
  		"SS 30<sup>&reg;</sup>",                      // 5
  		"SS 20<sup>&reg;</sup>",                      // 6
  		"SS 15<sup>&reg;</sup>",                      // 7
  		"Polykote<sup>&trade;</sup>",                 // 8
  		"Protect-N-Duct<sup>&trade;</sup>",           // 9
  		"Kortech<sup>&reg;</sup>",                    // 10
  		"Razor-Ribbon<sup>&reg;</sup>",               // 11
  		"Flo-Coat<sup>&reg;</sup>",                   // 12
  		"INSTABARRIER<sup>&reg;</sup>",               // 13
  		"HOOK<sup>&reg;</sup> BARB",                  // 14
  		"NEC<sup>&reg;</sup>",                        // 15  
  		"UL<sup>&reg;</sup>",                         // 16  
  		"CEC<sup>&reg;</sup>",                        // 17 	
  		"MC-Quik<sup>&reg;</sup>",                        // 18	
  		"MC Stat<sup>&reg;</sup>",                        // 19
  		"MC Lite<sup>&reg;</sup>",                        // 20	
  		"Home Run Cable<sup>&reg;</sup>",                        // 21	
  		"AC-Lite<sup>&reg;</sup>",                        // 22
  		"MC Tuff<sup>&reg;</sup>",                        // 23
  		"MC-Plus<sup>&reg;</sup>",                        // 24
      "HCF-90<sup>&reg;</sup>",                        // 25
      "HCF-Lite<sup>&reg;</sup>",                        // 26
      "AC-90<sup>&reg;</sup>",                        // 27
      "Fire Alarm<sup>&reg;</sup>",                        // 28
      "Super Neutral Cable<sup>&reg;</sup>",                        // 29
  	);
  $content = str_replace($textSearch, $textReplace, $content);
  return $content;
}
add_filter('the_content','add_reg', 1);
add_filter('the_title','add_reg', 1);
add_filter('the_field','add_reg', 1);
add_filter('get_field','add_reg', 1);