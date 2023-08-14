<?php

/**
 * SEO Crawler Actions
 */

 //namespace seocrawler\appClasses\actions;

 class SeoCrawlerActions {
   //Admin Top Leavel Menu (SEO Crawler)
    public function adminMenu(){
        add_menu_page(
         'SEO Crawler - WP Media',
         'SEO Crawler',
         'manage_options',
         'seo-crawler-by-adeoti',
         [$this, 'seoMenuTemplate'],
         'dashicons-editor-unlink',
         10
        );
    }

    //Admin Menu Template
    public function seoMenuTemplate(){
        wp_enqueue_style('adminCss', plugin_dir_url(__FILE__). '../../assets/style.css');
        //wp_enqueue_script('adminJs', plugin_dir_url(__FILE__). '../../assets/main-crawl.js', array('jquery'), 1.0, true);
        require_once plugin_dir_path(__FILE__). '../../views/admin/template.php';

        $this -> display_sitemap_html_link();
        if(isset($_POST['seoCrawl'])){
         $this -> handleCrawlForm();
       
        }
    }

    
  
  
    //Handle the form here
    public function handleCrawlForm(){
        check_admin_referer('crawl_none', 'crawl_nonce'); 
        //Call the method that Saves the crawled result now!
        $this -> save_crawled_result();
        $this -> create_sitemap_html_file();
        $this -> trigger_crawler('hourly');

        ?>
          <div class="updated notice notice-success is-dismissible">
                            <p>Crawled. Please reload the page!</p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
        <?php
    }


    // Crawl on schedule....
    public function trigger_crawler($recurrence = null){
        $seo_crawl_recurrence = $recurrence;
        if(!wp_next_scheduled('seo_crawl_agent')){
          wp_schedule_event(time(), $seo_crawl_recurrence, 'seo_crawl_agent');
        }
    }

    public function seoCrawl_hourly(){
        $this -> save_crawled_result();
        $this -> create_sitemap_html_file();
    }

    //Save crawled result!....
    public function save_crawled_result(){

        if(count($this -> crawl_homepage_and_get_links()) > 0){
          update_option('seo_crawled_result', $this -> crawl_homepage_and_get_links());
        }

    }

    public function create_sitemap_html_file() {
      $plugin_root = plugin_dir_path(__FILE__);
      $sitemap_file = $plugin_root . 'sitemap.html';

          $homepage_links = get_option('seo_crawled_result', array());

        $file_content = "<html><head><title>The Sitemap</title>";

        $file_content = <<<SITEMAPEntry
          <html>
            <head>
              <title>SEO Crawler Sitemap - WP Media</title>
              <style>
              body.seoCcrawler{
                background:lightblue;
            }
            .seoCcrawler .sitemap_wrapper{
                background: #ffffff;
                width:80%;
                padding:30px;
                margin:0px auto;
            }
            ul{
              list-style-type:none;
            }

              li{
                margin-top:10px;
              }
              </style>
            </head>
            <body class="seoCcrawler">
            <div class="sitemap_wrapper">
           
            <div style="padding:7px; background:#eee; border-radius:8px;">
                 <h1>SEO Sitemap Report</h1>
                 <p>
                    Below are the crawled internal links from the homepage of this website. By Adeoti Nurudeen (WP Media Test)
                 </p>
            </div>

            <table border="1">
              <thead>
                <tr>
                  <th>Anchor Text</th>
                  <th>Anchor Link</th>
                  <th>Anchor Redirect</th>
                </tr>
              </thead>
              <tbody>
          SITEMAPEntry;

          if (!empty($homepage_links)) {
            ob_start();
              echo '<ul>';
              foreach ($homepage_links as $link) {


                  //echo '<li><a href="' . esc_url($link['href']) . '">' . esc_html($link['text']) . '</a></li>';
                  echo '<tr>
                        <td>'.esc_html($link['text']).'</td>
                        <td>'.esc_html($link['href']).'</td>
                        <td><a href="' . esc_url($link['href']) . '">' . esc_html($link['text']) . '</a></td>
                        </tr>';
              
                }
              echo '</ul>'; 

              $file_content .= ob_get_clean();

              $file_content .=<<<SITEMAPClosure
                  </tbody>
                  </table>
                  </div>
                </body>
              </html>


          SITEMAPClosure;

          } else {
              echo 'No homepage links found.';
          }

      //Check the existence of the sitemap file
      if (file_exists($sitemap_file)) {
        if (unlink($sitemap_file)) {
           //File deleted successful
        } else {
            //echo "Error removing existing file. ";
        }
    }


      if (file_put_contents($sitemap_file, $file_content) !== false) {
          //echo "File created successfully."; 
         // $this -> display_sitemap_html_link();
      } else {
          echo "Error creating the file.";
      }
  }

    //Display the sitemap link if the file exists
    public function display_sitemap_html_link(){

      $plugin_root = plugin_dir_path(__FILE__);
      $sitemap_file = $plugin_root . 'sitemap.html';
      
      $shortcode_hint = "";
      $homepage_links = get_option('seo_crawled_result', array());
      if(!empty($homepage_links)){
        $shortcode_hint = "
        <div>
          To show the crawled result in the frontend to your visitors, kindly use this shortcode in any page 
            <br><br>
          <b style='background:pink; padding:4px 10px; border-radius:7px;'>[lay_crawled_links]</b>
        </div>
        ";
      }

      if (file_exists($sitemap_file)) {
          $file_url = plugin_dir_url(__FILE__) . 'sitemap.html';
          echo "<div class='wrap'>
                <div class='seoC-bg-white seoC-wide'>
                <h2 style='font-weight:500; font-size:15px;''>Sitemap</h2>
                <p>
                <a href='$file_url' target='_blank'>Click here to view the sitemap</a>
                $shortcode_hint
                </p>
                </div>
                </div>
              ";
      } else {
          echo "
          <div class='wrap'>
          <div class='seoC-bg-white seoC-wide'>
          <h2 style='font-weight:500; font-size:15px;''>Sitemap</h2>
          <p style='color:grey;'>
            The sitemap link will be available here after crawling.
          </p>
          </div>
          </div>
            ";
      }
  }
  
  


    //Handle the crawling logic
    public function crawl_homepage_and_get_links() {
      $homepage_url = home_url(); 
  
      $response = wp_safe_remote_get($homepage_url);
  
      if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
          $links_array = array();
  
          $html = wp_remote_retrieve_body($response);
  
          $dom = new DOMDocument();
          @$dom->loadHTML($html);
  
          $links = $dom->getElementsByTagName('a');
  
          foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $text = $link->nodeValue;

            if (!empty($href) && !empty($text)) {
                $links_array[] = array(
                    'text' => $text,
                    'href' => $href
                );
            }
        }
  
          return $links_array;
      } else {
          return array();
      }
  }


    //Handle the frontend site map rendering (shortcode)
    public function frontendSitemap($sitemap_shortcode){

      $homepage_links = get_option('seo_crawled_result', array());

      $sitemap_shortcode = <<<SITEMAPEntry
     
          <style>
          div.seoCcrawler{
            background:lightblue;
        }
        .seoCcrawler .sitemap_wrapper{
            background: #ffffff;
            width:80%;
            padding:30px;
            margin:0px auto;
        }
        ul{
          list-style-type:none;
        }

          li{
            margin-top:10px;
          }
          </style>
          <div class="secCcrawler">
        <div class="sitemap_wrapper">
       
        <div style="padding:7px; background:#eee; border-radius:8px;">
             <h1>SEO Sitemap Report</h1>
             <p>
                Below are the crawled internal links from the homepage of this website - By Adeoti Nurudeen (WP Media Test)
             </p>
        </div>

        <table border="1">
          <thead>
            <tr>
              <th>Anchor Text</th>
              <th>Anchor Link</th>
              <th>Anchor Redirect</th>
            </tr>
          </thead>
          <tbody>
      SITEMAPEntry;


      if (!empty($homepage_links)) {
        ob_start();
          echo '<ul>';
          foreach ($homepage_links as $link) {


              //echo '<li><a href="' . esc_url($link['href']) . '">' . esc_html($link['text']) . '</a></li>';
              echo '<tr>
                    <td>'.esc_html($link['text']).'</td>
                    <td>'.esc_html($link['href']).'</td>
                    <td><a href="' . esc_url($link['href']) . '">' . esc_html($link['text']) . '</a></td>
                    </tr>';
          
            }
          echo '</ul>'; 

          $sitemap_shortcode .= ob_get_clean();

          $sitemap_shortcode .=<<<SITEMAPClosure
              </tbody>
              </table>
              </div>
              </div>
      SITEMAPClosure;

      } else {
          echo 'No homepage links found.';
      }


        return $sitemap_shortcode;
    }





 }

