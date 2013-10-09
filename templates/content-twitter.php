<?php // draft sample display for array returned from oAuth Twitter Feed for Developers WP plugin
// http://wordpress.org/extend/plugins/oauth-twitter-feed-for-developers/
?>
<div class="panel panel-default">
  <div class="panel-body">
<?php
$tweets = getTweets(1);//change number up to 20 for number of tweets
if(is_array($tweets)){

// to use with intents
echo '<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>';

foreach($tweets as $tweet){

    if($tweet['text']){
        $the_tweet = $tweet['text'];
        /*
        Twitter Developer Display Requirements
        https://dev.twitter.com/terms/display-requirements

        2.b. Tweet Entities within the Tweet text must be properly linked to their appropriate home on Twitter. For example:
          i. User_mentions must link to the mentioned user's profile.
         ii. Hashtags must link to a twitter.com search with the hashtag as the query.
        iii. Links in Tweet text must be displayed using the display_url
             field in the URL entities API response, and link to the original t.co url field.
        */

        // i. User_mentions must link to the mentioned user's profile.
        if(is_array($tweet['entities']['user_mentions'])){
            foreach($tweet['entities']['user_mentions'] as $key => $user_mention){
                $the_tweet = preg_replace(
                    '/@'.$user_mention['screen_name'].'/i',
                    '<a href="http://www.twitter.com/'.$user_mention['screen_name'].'" target="_blank">@'.$user_mention['screen_name'].'</a>',
                    $the_tweet);
            }
        }

        // ii. Hashtags must link to a twitter.com search with the hashtag as the query.
        if(is_array($tweet['entities']['hashtags'])){
            foreach($tweet['entities']['hashtags'] as $key => $hashtag){
                $the_tweet = preg_replace(
                    '/#'.$hashtag['text'].'/i',
                    '<a href="https://twitter.com/search?q=%23'.$hashtag['text'].'&src=hash" target="_blank">#'.$hashtag['text'].'</a>',
                    $the_tweet);
            }
        }

        // iii. Links in Tweet text must be displayed using the display_url
        //      field in the URL entities API response, and link to the original t.co url field.
        if(is_array($tweet['entities']['urls'])){
            foreach($tweet['entities']['urls'] as $key => $link){
                $the_tweet = preg_replace(
                    '`'.$link['url'].'`',
                    '<a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a>',
                    $the_tweet);
            }
        }

        echo $the_tweet;


        // 4. Tweet Timestamp
        //    The Tweet timestamp must always be visible and include the time and date. e.g., “3:00 PM - 31 May 12”.
        // 5. Tweet Permalink
        //    The Tweet timestamp must always be linked to the Tweet permalink.
        echo '
          <p class="timestamp">
              <a href="https://twitter.com/YOURUSERNAME/status/'.$tweet['id_str'].'" target="_blank">
                  '.date('h:i A M d',strtotime($tweet['created_at']. '- 8 hours')).'
              </a>
          </p>';// -8 GMT for Pacific Standard Time

        // 3. Tweet Actions
        //    Reply, Retweet, and Favorite action icons must always be visible for the user to interact with the Tweet. These actions must be implemented using Web Intents or with the authenticated Twitter API.
        //    No other social or 3rd party actions similar to Follow, Reply, Retweet and Favorite may be attached to a Tweet.
        // get the sprite or images from twitter's developers resource and update your stylesheet
        echo '
        </div>
        <div class="panel-footer">
        <div class="btn-group btn-group-xs btn-group-justified twitter_intents">
            <a class="btn btn-default reply" href="https://twitter.com/intent/tweet?in_reply_to='.$tweet['id_str'].'"><span class="glyphicon glyphicon-reply"></span> Reply</a>
            <a class="btn btn-default retweet" href="https://twitter.com/intent/retweet?tweet_id='.$tweet['id_str'].'"><span class="glyphicon glyphicon-retweet"></span>Retweet</a>
            <a class="btn btn-default favorite" href="https://twitter.com/intent/favorite?tweet_id='.$tweet['id_str'].'"><span class="glyphicon glyphicon-star"></span>Favorite</a>
        </div>
        </div>';



    } else {
        echo '
        <br /><br />
        <a href="http://twitter.com/YOURUSERNAME" target="_blank">Click here to read YOURUSERNAME\'S Twitter feed</a>';
    }
}
} ?>
</div>