<?php
/**
 * 
 * Uninstall the plugin
 * @package seocrawler
 * @author Adeoti Nurudeen
 */

 if(!WP_UNINSTALL_PLUGIN){
    die("I won't do well if called directly!");
 }

 $seoCrawler_options = array('seo_crawled_result');

 foreach($seoCrawler_options as $optionseoC){
    delete_option($optionseoC);
 }
