$(function() {

       $(document).on('click','.close', function(){
            $('.site_flash_msg').hide();
        });

       $(document).on('change','.bannerType', function(){
            var banner = $(this).val();
            if(banner == 2) {
            	$('.offerType').hide();
            	$('.matchType').hide();
            }
            if(banner == 3) {
            	$('.offerType').show();
            	$('.matchType').hide();
            }
            if(banner == 1) {
            	$('.matchType').show();
            	$('.offerType').hide();
            }
        });

        
       
});
