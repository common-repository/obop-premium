<?php
/**
 * Plugin Name: OBOP Premium
 * Plugin URI: http://www.obop.co
 * Description: This plugins allow you to limit the access of some post to the OBOP users only
 * Version: 0.1
 * Author: Yves Bochatay
 * Author URI: http://www.obop.co
 * License: GPL2
 */

include 'settings.php';

$settings = new ObopPremiumSettings();


require_once("obop.php");

obop::init();

/* ------ tinyMCE PLUGIN ------ */
function enqueue_plugin_scripts($plugin_array)
{
    $plugin_array["obop_premium_plugin"] = plugin_dir_url(__FILE__) . "js/index.js";
    
    echo '<script type="text/javascript">
        var pluginUrl = "' . plugin_dir_url( __FILE__ ) . '";
        var curLang = "' . substr(get_bloginfo( 'language' ), 0, 2) . '";
    </script>';
    
    return $plugin_array;
}

add_filter("mce_external_plugins", "enqueue_plugin_scripts");

function register_buttons_editor($buttons)
{
    array_push($buttons, "readmore");
    
    return $buttons;
}

add_filter("mce_buttons", "register_buttons_editor");


/* ------- CONTENT FILTER ------ */ 

function custom_text($content)
{
    $curLang = substr(get_bloginfo( 'language' ), 0, 2);
    
    if(is_home() && is_front_page()) 
    {
        if(strpos($content, 'readmore-button') !== false)
        {
            switch(obop::status())
            {
                case Obop::OBOP_OK:
                    $string = '<img class="readmore-button" src="'.plugin_dir_url( __FILE__ ).'img/read-more-'.$curLang.'.png" alt="Read more" width="150" height="75" data-type="more" />';
                    $content = str_replace($string, ' ', $content);
                    break;
                case Obop::OBOP_REDIRECT:
                    echo "<script type='text/javascript'>window.location='http://dev.obop.co/token/';</script>";
                    break;
                case obop::OBOP_NOT_IDENTIFIED:
                    $partial = explode("<img class=\"readmore-button", $content);
                    $url = get_permalink();
                    $partial[0] .= '<a href="' . $url . '"><img class="readmore-button" style="margin:auto" alt="button read more" height="75" width="150" src="'.plugin_dir_url( __FILE__ ).'img/read-more-'.$curLang.'.png" data-type="more"></a>';
                    $content = $partial[0];
                    break;
            }
        }
    } 
    else
    {
        switch(Obop::status())
        {
            case Obop::OBOP_OK:
                $string = '<img class="readmore-button" src="'.plugin_dir_url( __FILE__ ).'img/read-more-'.$curLang.'.png" alt="Read more" width="150" height="75" data-type="more" />';
                $content = str_replace($string, ' ', $content);
                break;
            case Obop::OBOP_REDIRECT:
                echo "<script type='text/javascript'>window.location='http://dev.obop.co/token/';</script>";
                break;
            case Obop::OBOP_NOT_IDENTIFIED:
                $string = '<p style="text-align:center;"><a href="http://www.obop.co/signup/" target="_blank"><img class="readmore-button" src="'.plugin_dir_url( __FILE__ ).'img/read-more-'.$curLang.'.png" alt="Read more" width="150" height="75" data-type="more" /></a></p>';
				$partial = explode("<img class=\"readmore-button", $content);
                $premiumtext = get_option('premiumtext');
                $premiumtext = (empty($premiumtext)) ? __('You must logged in to read this article, you can subscribe by our partner Obop here ', 'wp-admin-obop-premium') : $premiumtext;
                
				$content = $partial[0].$string.'<p>'.$premiumtext.' <a href="http://www.obop.co/signup/" target="_blank">www.obop.co</a></p>';
                break;
        }
    }

    return $content;
}

add_filter( "the_content", "custom_text");
