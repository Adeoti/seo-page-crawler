<div class="wrap">
    <h1>Admin Crawler Page</h1>
    <hr/>

    <div class="seoC-bg-white seoC-wide">
        <h2 style="font-weight:500; font-size:15px;">Instruction</h2>
        <p>
            Kindly Click on the button below to start crawling. Provide the auto-refreshing interval in the box provided.
        </p>

        <p> 
            Note that any existing crawling result and sitemap will be removed to allow fresh crawling reservation.
        </p>
        <form action="" method="POST">
            <br/><br/>
            <?php
                $crawl_nonce = wp_create_nonce('crawl_none');
            ?>
        <input type="hidden" name="crawl_nonce" value="<?php echo $crawl_nonce; ?>">
      <!-- <input type="text" id="seoC-crawl-schedule" placeholder="Enter interval (in minutes)"> -->
       <input class="button-primary" type="submit" name="seoCrawl" id="seoC-crawl-btn" value="Start Crawling">

        </form>
    </div>
</div>