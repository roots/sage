<?php

/*
 * The social core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_social_options' ) ) {
  function shoestrap_module_social_options() {

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Social
    $of_options[] = array(
      "name"      => __("Social Sharing", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "social_sharing_help_1",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">General Options</h3>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Button Text", "shoestrap"),
      "desc"      => __("Select the text for the social sharing button.", "shoestrap"),
      "id"        => "social_sharing_text",
      "std"       => "Share",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Button Location", "shoestrap"),
      "desc"      => __("Select between NONE, TOP, BOTTOM & BOTH", "shoestrap"),
      "id"        => "social_sharing_location",
      "std"       => "top",
      "type"      => "select",
      "customizer"=> array(),
      "options"   => array(
        'none'    =>"None",
        'top'     =>"Top",
        'bottom'  =>"Bottom",
        'both'    =>"Both",
      )
    );

    $of_options[] = array(
      "name"      => __("Show in Posts Archives", "shoestrap"),
      "desc"      => __("Show the sharing button in posts archives.", "shoestrap"),
      "id"        => "social_sharing_archives",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Show in Single Post", "shoestrap"),
      "desc"      => __("Show the sharing button in single post.", "shoestrap"),
      "id"        => "social_sharing_single_post",
      "std"       => "1",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Show in Single Page", "shoestrap"),
      "desc"      => __("Show the sharing button in single page.", "shoestrap"),
      "id"        => "social_sharing_single_page",
      "std"       => "1",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "social_sharing_help_2",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Select Socials</h3>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Facebook", "shoestrap"),
      "desc"      => __("Show the Facebook sharing icon in blog posts.", "shoestrap"),
      "id"        => "facebook_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Google+", "shoestrap"),
      "desc"      => __("Show the Google+ sharing icon in blog posts.", "shoestrap"),
      "id"        => "google_plus_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("LinkedIn", "shoestrap"),
      "desc"      => __("Show the LinkedIn sharing icon in blog posts.", "shoestrap"),
      "id"        => "linkedin_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Pinterest", "shoestrap"),
      "desc"      => __("Show the Pinterest sharing icon in blog posts.", "shoestrap"),
      "id"        => "pinterest_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Reddit", "shoestrap"),
      "desc"      => __("Show the Reddit sharing icon in blog posts.", "shoestrap"),
      "id"        => "reddit_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Tumblr", "shoestrap"),
      "desc"      => __("Show the Tumblr sharing icon in blog posts.", "shoestrap"),
      "id"        => "tumblr_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Twitter", "shoestrap"),
      "desc"      => __("Show the Twitter sharing icon in blog posts.", "shoestrap"),
      "id"        => "twitter_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Email", "shoestrap"),
      "desc"      => __("Show the Email sharing icon in blog posts.", "shoestrap"),
      "id"        => "email_share",
      "std"       => "",
      "type"      => "switch"
    );

    // Social
    $of_options[] = array(
      "name"      => __("Social Links", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Blogger", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Blogger icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "blogger_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("DeviantART", "shoestrap"),
      "desc"      => __("Provide the link you desire and the DeviantART icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "deviantart_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Digg", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Digg icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "digg_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Dribbble", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Dribbble icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "dribbble_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Facebook", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Facebook icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "facebook_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Flickr", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Flickr icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "flickr_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Forrst", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Forrst icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "forrst_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("GitHub", "shoestrap"),
      "desc"      => __("Provide the link you desire and the GitHub icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "github_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Google+", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Google+ icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "google_plus_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("LinkedIn", "shoestrap"),
      "desc"      => __("Provide the link you desire and the LinkedIn icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "linkedin_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("MySpace", "shoestrap"),
      "desc"      => __("Provide the link you desire and the MySpace icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "myspace_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Pinterest", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Pinterest icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "pinterest_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Reddit", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Reddit icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "reddit_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("RSS", "shoestrap"),
      "desc"      => __("Provide the link you desire and the RSS icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "rss_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Skype", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Skype icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "skype_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("SoundCloud", "shoestrap"),
      "desc"      => __("Provide the link you desire and the SoundCloud icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "soundcloud_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Tumblr", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Tumblr icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "tumblr_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Twitter", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Twitter icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "twitter_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Vimeo", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Vimeo icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "vimeo_link",
      "std"       => "",
      "type"      => "text"
    );


    $of_options[] = array(
      "name"      => "Vkontakte",
      "desc"      => "Provide the link you desire and the Vkontakte icon will appear. To remove it, just leave it blank.",
      "id"        => "vkontakte_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => "Yahoo TODO",
      "desc"      => "Provide the link you desire and the Yahoo icon will appear. To remove it, just leave it blank.",
      "id"        => "yahoo_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => "YouTube Link",
      "desc"      => "Provide the link you desire and the YouTube icon will appear. To remove it, just leave it blank.",
      "id"        => "youtube_link",
      "std"       => "",
      "type"      => "text"
    );
    do_action( 'shoestrap_module_social_options_modifier' );

    $smof_details = array();
    foreach( $of_options as $option ) {
      if (isset($option['id']))
        $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init', 'shoestrap_module_social_options', 85 );

include_once( dirname(__FILE__).'/functions.social.php' );
