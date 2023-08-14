let seoCcrawl_btn = document.getElementById('seoC-crawl-btn');
let seoCcrawl_schedule = document.getElementById('seoC-crawl-schedule');

let seoCcrawl_schedule_val = 0;

if(seoCcrawl_btn){
    seoCcrawl_btn.addEventListener('click', function(e){
        e.preventDefault();
        var nonce = '<?php echo wp_create_nonce("start-crawling"); ?>';

        if(seoCcrawl_schedule){
            
            if(seoCcrawl_schedule.value.length > 0){
                seoCcrawl_schedule_val = seoCcrawl_schedule.value;
            }else{
                seoCcrawl_schedule_val = 30;
            }
        }else{
            seoCcrawl_schedule_val = "";    
        }

    // Send AJAX request
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'start_crawling_action',
            crawlFrequency: seoCcrawl_schedule_val,
            nonce: nonce
        },
        success: function(response) {
            // Display success message or handle response
            alert('Crawling process started successfully.'+response.data);
        }
    });


    });
}


