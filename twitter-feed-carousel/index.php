<?php
/*
Plugin Name: Twitter Feed Carousel by Mittun
Plugin URI:  http://www.mittun.com
Description: Easily show your Twitter feed through a beautiful carousel. Fully responsive and ready to rock. This is the best WordPress Twitter widget plugin available.
Version:     1.0.0.2
Author:      Mittun
Author URI:  http://www.mittun.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: tfc
*/

if(!defined('TFC_PATH'))define('TFC_PATH',plugin_dir_path( __FILE__ ));
if(!defined('TFC_URL'))define('TFC_URL',plugins_url( '',__FILE__));

/*
Register default value
*/

function tfc_activate() {
	
    $default=array(
		'logo'=>TFC_URL.'/img/logo.png',
		'auto_rotate'=>'',
		'auto_rotate_speed'=>7000,
		'transition_type'=>'slide',
	);
	
	$tfc=get_option('tfc');
	
	if(empty($tfc))
	update_option('tfc',$default);
}
register_activation_hook( __FILE__, 'tfc_activate' );

/*
Create admin menu
*/
add_action( 'admin_menu', 'tfc_admin_menu' );

function tfc_admin_menu() {

$tfc_menu=add_menu_page( __('Twitter Feed Carousel','tfc'), __('Twitter Feed Carousel','tfc'), 'manage_options', 'tfc','tfc_settings',TFC_URL.'/img/icon.png');
add_action( 'admin_print_scripts-' . $tfc_menu, 'tfc_admin_scripts' );

}

function tfc_settings()
{
	?>
    <div class="wrap">
		<h2><?php _e('General Settings','tfc');?></h2>
            <form method="post" action="options.php">
                <?php settings_errors(); ?>               
                <?php              
				settings_fields( 'tfc-settings' );
                do_settings_sections( 'tfc-settings' );				
                ?>   
                <table class="form-table">
                <tr valign="top">                   
                     <td colspan="2">
                     <hr />
                   <h2><?php _e('Twitter API'); ?></h2>
                   <hr/>
                    </td>
                 </tr> 
                <tr valign="top">
                    <th scope="row"><?php _e('User Name','tfc'); ?></th>
                    <td>
                    <input name="tfc[user_name]" type="text" class="regular-text" value="<?php echo tfc_get_option('user_name'); ?>"/>
                    </td>
                 </tr>             
                <tr valign="top">
                    <th scope="row"><?php _e('Consumer Key','tfc'); ?></th>
                    <td>
                    <input name="tfc[consumer_key]" type="text" class="regular-text" value="<?php echo tfc_get_option('consumer_key'); ?>"/>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Consumer Secret','tfc'); ?></th>
                    <td>
                    <input name="tfc[consumer_secret]" type="text" class="regular-text" value="<?php echo tfc_get_option('consumer_secret'); ?>"/>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Access Token','tfc'); ?></th>
                    <td>
                    <input name="tfc[access_token]" type="text" class="regular-text" value="<?php echo tfc_get_option('access_token'); ?>"/>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Access Token Secret','tfc'); ?></th>
                    <td>
                    <input name="tfc[access_token_secret]" type="text" class="regular-text" value="<?php echo tfc_get_option('access_token_secret'); ?>"/>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Twitter Logo','pix'); ?></th>
                    <td>
                    <?php $logo=tfc_get_option('logo');?>
					 <input type="text" name="tfc[logo]" class="regular-text" value="<?php echo $logo ; ?>" size="25" />
                     <input type='button' class="button-primary tfc-upload" value="<?php _e('Upload Logo','tfc');?>" />
                     <input type='button' class="button-primary tfc-remove" value="<?php _e('Remove','tfc');?>" />
                     <div class="tfc-upload-snap"><?php if(!empty($logo)){ ?><img src="<?php echo $logo;?>"/><?php } ?></div>                 
                    </td>
                 </tr>
                 <tr valign="top">                  
                    <td colspan="2">
                    <hr>
                   <h2><?php _e('Slider Settings'); ?></h2>
                   <hr>
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Auto Rotate','tfc'); ?></th>
                    <td>
                   <input type="checkbox" name="tfc[auto_rotate]" value="true" class="lcs_check lcs_tt1" <?php checked(tfc_get_option('auto_rotate'),'true',true) ?>  autocomplete="off" />
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Auto Rotate Speed','tfc'); ?></th>
                    <td>
                  	
                    <div id="auto_rotate_speed_slider" style="width:60%"></div>
                    <input type="text" id="auto_rotate_speed" name="tfc[auto_rotate_speed]" value="<?php echo tfc_get_option('auto_rotate_speed'); ?>" readonly size="5" style="border:0; color:#f6931f; font-weight:bold;text-align:center;">
                    </td>
                 </tr>
                 <tr valign="top">
                    <th scope="row"><?php _e('Transition Type','tfc'); ?></th>
                    <td>
                    <div id="transition_type">
                  		<input type="radio" id="transition_type_slide" name="tfc[transition_type]" value="slide" <?php checked(tfc_get_option('transition_type'),'slide',true); ?>/><label for="transition_type_slide"><?php _e('Slide','tfc'); ?></label>
                        <input type="radio" id="transition_type_fade" name="tfc[transition_type]"  value="fade" <?php checked(tfc_get_option('transition_type'),'fade',true); ?>/><label for="transition_type_fade"><?php _e('Fade','tfc');?></label>
                    </div>
                    </td>
                 </tr>
                 
               </table>
                <?php submit_button(); ?>
            
            </form>
			
		</div>
    	
    <?php 
}

/*
Admin settings options
*/
add_action( 'admin_init', 'tfc_admin_options_settings' );

function tfc_admin_options_settings()
{
	register_setting( 'tfc-settings', 'tfc' );
}


function tfc_admin_scripts($hook)
{
	
	wp_enqueue_style( 'tfc-lc-switch','//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
	wp_enqueue_style( 'tfc-ui',TFC_URL.'/css/lc_switch.css' );
	
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-button');
	wp_enqueue_media();
	wp_enqueue_script('tfc-lc-switch',TFC_URL.'/js/lc_switch.min.js',array( 'jquery' ));
	wp_enqueue_script('tfc-admin',TFC_URL.'/js/admin.js',array( 'jquery' ));
}

/*
Custom function to get plugin options
*/
$tfc_settings=get_option('tfc');
function tfc_get_option($option)
{
	global $tfc_settings;
	
	if(empty($option))
	return false;
	
	if(!empty($tfc_settings[$option]))
	return $tfc_settings[$option];
	else
	return false;
	
}

add_shortcode('tfc','tfc_shortcode_callback');

function tfc_shortcode_callback($atts)
{
	$atts = extract(shortcode_atts( array(
		'count' =>5,
	), $atts, 'tfc' ));
	
	$output='';
	$feeds=tfc_get_twitter_feed($count);
	
	if(!empty($feeds))
	{
		$user_name=tfc_get_option('user_name');
		$logo=tfc_get_option('logo');
		
		if(!empty($logo))
		{
			$output.='<div id="container-master-mittun-tfc-plugin" class="responsive-universal-mittun"><div class="container-inner-mittun"> <div class="tfc-logo"><a href="http://twitter.com/'.$user_name.'" target="_blank"><img src="'.$logo.'" /></a></div>';
		}
	
		$output.='<div class="tfc-carousel '.(!empty($logo)?'tfc-fixed-width':'tfc-full-width').'"><ul class="slides">';
 
		 foreach($feeds as $feed)
		 { 
		 	 	$output.='<li><div class="item">';
				$output.='<span class="tweet-text">';
				$latestTweet =tfc_json_tweet_text_to_HTML( $feed);
				$latestTweet = preg_replace( '/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $latestTweet );
				$latestTweet = preg_replace( '/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $latestTweet );
				$output .= $latestTweet;
				$output .= '</span>';
				$twitterTime = strtotime( $feed->created_at );
				$timeAgo = tfc_ago( $twitterTime );
				$output .= '<a href="http://twitter.com/'.$feed->user->screen_name.'/statuses/'.$feed->id_str.'" class="tweet-time">'.$timeAgo.'</a>';
				
				$output .='<div class="tweet-button"><div class="tweet-reply"><a href="https://twitter.com/intent/tweet?in_reply_to='.$feed->id_str.'" class="mk-button outline-btn-light button-682 light mk-shortcode outline-dimension large twitter-button">'.__('reply!','tfc').'</a></div>';
				$output .='<div class="tweet-retweet"><a href="https://twitter.com/intent/retweet?tweet_id='.$feed->id_str.'" class="mk-button outline-btn-light button-682 light mk-shortcode outline-dimension large twitter-button">'.__('retweet!','tfc').'</a></div></div>';
			$output.='</div></li>';
		 }
		 		 
		$output.='</ul></div></div></div>';
	}
	return $output;
}

function tfc_json_tweet_text_to_HTML($tweet, $links=true, $users=true, $hashtags=true)
{
    $return = $tweet->text;

    $entities = array();

    if($links && is_array($tweet->entities->urls))
    {
        foreach($tweet->entities->urls as $e)
        {
            $temp["start"] = $e->indices[0];
            $temp["end"] = $e->indices[1];
            $temp["replacement"] = "<a href='".$e->url."' target='_blank'>".$e->display_url."</a>";
            $entities[] = $temp;
        }
    }
    if($users && is_array($tweet->entities->user_mentions))
    {
        foreach($tweet->entities->user_mentions as $e)
        {
            $temp["start"] = $e->indices[0];
            $temp["end"] = $e->indices[1];
            $temp["replacement"] = "<a href='https://twitter.com/".$e->screen_name."' target='_blank'>@".$e->screen_name."</a>";
            $entities[] = $temp;
        }
    }
    if($hashtags && is_array($tweet->entities->hashtags))
    {
        foreach($tweet->entities->hashtags as $e)
        {
            $temp["start"] = $e->indices[0];
            $temp["end"] = $e->indices[1];
            $temp["replacement"] = "<a href='https://twitter.com/hashtag/".$e->text."?src=hash' target='_blank'>#".$e->text."</a>";
            $entities[] = $temp;
        }
    }

    usort($entities, function($a,$b){return($b["start"]-$a["start"]);});


    foreach($entities as $item)
    {
        $return = substr_replace($return, $item["replacement"], $item["start"], $item["end"] - $item["start"]);
    }

    return($return);
}

function tfc_get_twitter_feed($count=5)
{
	$user_name=tfc_get_option('user_name');
	$consumer_key=tfc_get_option('consumer_key');
	$consumer_secret=tfc_get_option('consumer_secret');
	$access_token=tfc_get_option('access_token');
	$access_token_secret=tfc_get_option('access_token_secret');
	$feeds=false;
	if(!empty($user_name) && !empty($consumer_key) && !empty($consumer_secret) && !empty($access_token) && !empty($access_token_secret))
	{
		require_once(TFC_PATH.'/inc/twitteroauth.php');
		
		$twitterConnection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
		$feeds = $twitterConnection->get(
			'statuses/user_timeline',
			array(
				'screen_name'     => $user_name,
				'count'           => $count,
				'exclude_replies' => false
			)
		);
	
	}
	return $feeds;
}

function tfc_ago( $time ) {
    $periods = array( "second", "minute", "hour", "day", "week", "month", "year", "decade" );
    $lengths = array( "60", "60", "24", "7", "4.35", "12", "10" );

    $now = time();

    $difference     = $now - $time;
    $tense         = "ago";

    for ( $j = 0; $difference >= $lengths[$j] && $j < count( $lengths )-1; $j++ ) {
        $difference /= $lengths[$j];
    }

    $difference = round( $difference );

    if ( $difference != 1 ) {
        $periods[$j].= "s";
    }

    return "$difference $periods[$j] ago ";
}

add_action( 'wp_enqueue_scripts', 'tfc_scripts' );

function tfc_scripts()
{	
	
	wp_enqueue_style( 'tfc-flexslider', TFC_URL.'/css/flexslider.css' );
	wp_enqueue_style( 'tfc-style', TFC_URL.'/css/style.css' );
	
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'tfc-flexslider',TFC_URL.'/js/jquery.flexslider-min.js',array('jquery'),false,true);
	wp_enqueue_script( 'tfc-script',TFC_URL.'/js/script.js',array('jquery'),false,true);
	wp_localize_script( 'tfc-script','tfcObj',array('autoRotate'=>tfc_get_option('auto_rotate'),'autoRotateTimeout'=>tfc_get_option('auto_rotate_speed'),'animtion'=>tfc_get_option('transition_type')));
}
?>
