<?php
/*
Plugin Name: Enable HLS video playback in Elementor heros
Description: Make HLS video playback possible in Elementor heros. 
Version: 0.0.2
Author: BoldOrion
Author URI: https://www.boldorion.com
Text Domain: boldorion
License: MIT
*/

/**
 * Let's make sure we can get updates for this down the road
 */
require 'plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/boldorion/wp-hlsherovideo',
    __FILE__,
    'hlsherovideo'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//On the page that has been loaded, let's see if there's an Elementor section with a video background on it
add_action('wp_footer', 'add_hls_script',10);
add_action('wp_footer', 'add_hls_snippet',100);

function add_hls_script()
{
    wp_enqueue_script('hls-light', plugin_dir_url(__FILE__) . 'js/hls.light.js');
}

function add_hls_snippet()
{
    echo "<script>
    var hlsconfig = {
        autoStartLoad: true,
            debug: false,
            maxBufferLength: 5,
            testBandwidth: true,
            progressive: false,
            lowLatencyMode: true,
            enableWorker: true,
    };

    jQuery(document).ready(function($) {
        setTimeout(function() {
            var hlsurl = '';
            let count = 0;
                $('.elementor-html5-video').each(function() {
                    var videoElement = $(this);
                    var src = videoElement.attr('src');
                    if (src && src.endsWith('.m3u8')) {
                        if (Hls.isSupported()) {
                            videoElement.removeAttr('src');
                            var video = document.getElementsByClassName('elementor-html5-video')[count];
                            var hls = new Hls();
                            hls.on(Hls.Events.MEDIA_ATTACHED, function () {
                            });
                            hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
                            });
                            hls.loadSource(src);
                            hls.attachMedia(video);
                            video.play();
                        }
                        count++;
                        //return false; // Stop looping after the first matching video element
                    }
                });
        }, 200);
    }); 
    </script>";
}