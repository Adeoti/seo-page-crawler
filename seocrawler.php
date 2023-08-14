<?php
/**
 * Plugin Name: SEO Crawler - WP MEDIA
 * Plugin URI: ***
 * Description: Crawl your site to know how your site pages are connected for best SEO performance and analysis. Technical assessment - created by Adeoti.
 * Version: 1.0
 * Author: Adeoti Nurudeen
 * Author URI: https//:github.com/Adeoti
 * License: GPLv2 or Later
 * Requires PHP: 5.2
 * Requires at least: 5.0
 * Text Domain: seocrawler
 */

//Reject direct access
if(!defined('ABSPATH')){
    die('I can\'t do much things if called directly!');
}

require_once plugin_dir_path(__FILE__). "appClasses/actions/SeoCrawlerActions.class.php";
//require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';


 class SeoCrawlerAdeoti extends SeoCrawlerActions{
        public function __construct()
        {
                add_action('admin_menu', [$this,'adminMenu']);
                add_shortcode('lay_crawled_links', [$this, 'frontendSitemap']);
                add_action('seo_crawl_agent', [$this, 'seoCrawl_hourly']);
                register_deactivation_hook(__FILE__, array($this, 'renderDeactivation'));
        }

        //render activation actions
        public function renderActivation(){

        }

        //render deactivation actions
        public function renderDeactivation(){
            wp_clear_scheduled_hook('seo_crawl_agent');
        }

    }



   $seocrawleradeoti = new SeoCrawlerAdeoti;
