<?php
// Hook to handle the crawling button click
          public function run_crawl() {
            $root_url = home_url('/'); 
            $crawl_results = $this -> crawl_page($root_url);

            // Store results in the database temporarily
            update_option('crawl-results', $crawl_results);

            // Create sitemap.html
            $this -> create_sitemap_html();

            echo 'Crawl completed successfully.';
          }



          // Callback function to display crawl results on the admin page
          public function crawl_results_section_cb() {
            $crawl_results = get_option('crawl-results', array());

            echo '<h3>Crawl Results</h3>';
            
            if (!empty($crawl_results)) {
                echo '<ul>';
                foreach ($crawl_results as $link) {
                    echo '<li><a href="' . esc_url($link) . '">' . esc_html($link) . '</a></li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No crawl results available.</p>';
            }
          }

          // Callback function to create the sitemap.html file
          public function create_sitemap_html() {
            $crawl_results = get_option('crawl-results', array());
            
            if (!empty($crawl_results)) {
                ob_start();
                echo '<ul>';
                foreach ($crawl_results as $link) {
                    echo '<li><a href="' . esc_url($link) . '">' . esc_html($link) . '</a></li>';
                }
                echo '</ul>';
                $sitemap_content = ob_get_clean();

                // Save the sitemap content to a file
                $upload_dir = wp_upload_dir();
                $sitemap_file = $upload_dir['basedir'] . '/sitemap.html';
                file_put_contents($sitemap_file, $sitemap_content);
            }
          }
    

            // Function to crawl a page and extract links
            public function crawl_page($url) {
              $crawl_results = array();

              $response = wp_remote_get($url);

              if (!is_wp_error($response) && isset($response['body'])) {
                  $html = $response['body'];
                  $dom = new DOMDocument();

                  @$dom->loadHTML($html); 

                  // Get all anchor (a) tags
                  $anchors = $dom->getElementsByTagName('a');

                  foreach ($anchors as $anchor) {
                      $href = $anchor->getAttribute('href');

                      // Check if it's an internal link ....
                      if (strpos($href, home_url('/')) === 0) {
                          $crawl_results[] = $href;
                      }
                  }
              }

              return $crawl_results;
            }