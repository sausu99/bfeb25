<!--============= Footer Section Starts Here =============-->
    <footer class="bg_img padding-top oh" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/footer-bg.jpg">
        <div class="footer-top-shape">
            <img src="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>img/footer-top-shape.png" alt="css">
        </div>
        <div class="anime-wrapper">
            <div class="anime-1 plus-anime">
                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/p1.png" alt="footer">
            </div>
            <div class="anime-2 plus-anime">
                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/p2.png" alt="footer">
            </div>
            <div class="anime-3 plus-anime">
                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/p3.png" alt="footer">
            </div>
            <div class="anime-5 zigzag">
                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/c2.png" alt="footer">
            </div>
            <div class="anime-6 zigzag">
                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/c3.png" alt="footer">
            </div>
            <div class="anime-7 zigzag">
                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/c4.png" alt="footer">
            </div>
        </div>
        <div class="newslater-wrapper">
            <div class="container">
                <div class="newslater-area">
                    <div class="newslater-thumb">
                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/newslater.png" alt="footer">
                    </div>
                    <div class="newslater-content">
                        <div class="section-header left-style mb-low">
                            <h5 class="cate"><?php echo lang('label_subs_to');?></h5>
                            <h3 class="title"><?php echo lang('label_subs_to_get_benifit');?></h3>
                        </div>
                        
                        <form name="subscribe" id="subscribe" class="subscribe-form" method="post" action="">
                            <input type="text" placeholder="Enter Your Email" name="subscribe_email" id="subscribe_email" required="required">
                            <button type="submit" class="custom-button"><?php echo lang('label_subscribe_bttn');?></button>
                        </form>
                        <div class="form_validation_error text-danger"></div>
                        <div id="form_validation_success" class="text-success"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-top padding-bottom padding-top">
            <div class="container">
                <div class="row mb--60">
                    <div class="col-sm-6 col-lg-3">
                        <div class="footer-widget widget-links">
                            <h5 class="title"><?php echo lang('label_auctions'); ?></h5>
                            <ul class="links-list">
                              <li><a href="<?php echo $this->general->lang_uri('/auctions/live');?>"><?php echo lang('live_auctions'); ?></a></li>
			                  <?php /*?><li><a href="<?php echo $this->general->lang_uri('/auctions/vote');?>"><?php echo lang('make_your_wish'); ?></a></li><?php */?>
                              <li><a href="<?php echo $this->general->lang_uri('/auctions/upcomming');?>"><?php echo lang('label_upcomming_auc'); ?></a></li>
            			      <li><a href="<?php echo $this->general->lang_uri('/auctions/winners');?>"><?php echo lang('closed_auctions'); ?></a></li>
            			      
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="footer-widget widget-links">
                            <h5 class="title"><?php echo lang('legal'); ?></h5>
                            <ul class="links-list">
                            <?php 
							 $first_row_cms =  $this->general->get_cms_lists(array('3','4','43','44','45')); 
							 if($first_row_cms){foreach($first_row_cms as $fcms){?>
							 <li><a href="<?php echo $this->general->lang_uri("/page/".$fcms->cms_slug);?> "><?php echo $fcms->heading;?></a></li>
							 <?php }}?>
                                
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="footer-widget widget-links">
                            <h5 class="title"><?php echo lang('informations'); ?></h5>
                            <ul class="links-list">
									<?php 
									$first_row_cms =  $this->general->get_cms_lists(array('1','46','47','48','49','50')); 
									if($first_row_cms){foreach($first_row_cms as $fcms){?>
									<li><a href="<?php echo $this->general->lang_uri("/page/".$fcms->cms_slug);?>"><?php echo $fcms->heading;?></a></li>
									<?php }}?>
									<li><a href="<?php echo $this->general->lang_uri("/contact-us");?> "><?php echo lang('contact_us'); ?></a></li>
									<li><a href="<?php echo $this->general->lang_uri("/help/index");?>"><?php echo lang('faq'); ?></a></li>
                                    <li><a href="<?php echo $this->general->lang_uri("/sitemap.xml");?>"><?php echo lang('label_sitemap'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="footer-widget widget-follow">
                            <h5 class="title"><?php echo lang('label_follow_us');?></h5>
                            <ul class="links-list">
                                <?php if(CONTACT_PHONE){?>
                                <li>
                                    <a href="tel:<?php echo CONTACT_PHONE;?>"><i class="fas fa-phone-alt"></i><?php echo CONTACT_PHONE;?></a>
                                </li>
                                <?php }?>
                                <li>
                                    <a href="mail:<?php echo CONTACT_EMAIL;?>"><i class="fas fa-envelope-open-text"></i><?php echo CONTACT_EMAIL;?></a>
                                </li>
                                <?php if(CONTACT_ADDRESS){?>
                                <li>
                                    <a><i class="fas fa-location-arrow"></i><?php echo CONTACT_ADDRESS;?></a>
                                </li>
                                <?php }?>
                            </ul>
                            <ul class="social-icons">
                            
                            <?php if(FACEBOOK_URL){?>
                 <li><a href="<?php echo FACEBOOK_URL;?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>                    
                 <?php }if(TWITTER_URL){?>
                 <li><a href="<?php echo TWITTER_URL;?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                 <?php }if(GOOGLE_URL){?>
                 <li><a href="<?php echo GOOGLE_URL;?>" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                 <?php }if(INSTAGRAM_URL){?>
                 <li><a href="<?php echo INSTAGRAM_URL;?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                 <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="copyright-area">
                    <div class="footer-bottom-wrapper">
                        <div class="logo">
                            <a href="<?php echo $this->general->lang_uri(); ?>"><img src="<?php echo site_url(SITE_LOGO_PATH) . SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>"></a>
                        </div>
                        <ul class="gateway-area">
                            <li>
                                <a href="#0"><img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/paypal.png" alt="footer"></a>
                            </li>
                            <li>
                                <a href="#0"><img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/visa.png" alt="footer"></a>
                            </li>
                            <li>
                                <a href="#0"><img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/discover.png" alt="footer"></a>
                            </li>
                            <li>
                                <a href="#0"><img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>footer/mastercard.png" alt="footer"></a>
                            </li>
                        </ul>
                        <div class="copyright"><p>&copy; Copyright <?php echo date("Y");?> | <a href="<?php echo site_url();?>">Cresco Vision LLP (Eodbox.com)</a> </p></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--============= Footer Section Ends Here =============-->
	
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>modernizr-3.6.0.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>plugins.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>bootstrap.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>isotope.pkgd.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>wow.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>waypoints.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>nice-select.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>counterup.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>owl.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>magnific-popup.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>yscountdown.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>jquery-ui.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>main.js"></script>
    
    <script>
  var SUBSCRIBE_URL = '<?php echo $this->general->lang_uri('/users/register/subscribe');?>';
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>

<script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>custom.js"></script>


<script>
  $(document).ready(function() {

    $("#subscribe").validate({
		
      submitHandler: function(form) {
       $(".form_validation_error").html('<i class="fa fa fa-spinner fa-spin"></i>');
       var input = $("#subscribe_email").val();
		   //alert(input);
		   $.post(SUBSCRIBE_URL,{subscribe_email:input},function(result){	
		   		data = jQuery.parseJSON(result);
				$(".form_validation_error").html("");
				$("#form_validation_success").html("");
		  		if(data.status == "success")
				{
					$("#form_validation_success").html(data.message);				
			         $("#subscribe_email").val('');
				}
				else{
					$(".form_validation_error").html(data.message);
				}
         
       });
     },
     errorElement: "div",
     errorClass: "form_validation_error",
     rules: {					
       subscribe_email: {
        required: true,
        //email:true
      },
    },
    errorPlacement: function(error, element) {
      if(element.attr("name") == "subscribe_email") {
       error.appendTo( $('.form_validation_error') );
     } else {
       error.insertAfter(element);
     }
   },

	});//formid validate
  });
</script>

<!-- FCM Start -->
<script>
var FCM_TOKEN_URL = '<?php echo $this->general->lang_uri('/users/login/token');?>';
var device_id = '<?php echo md5($_SERVER['HTTP_USER_AGENT']); ?>';
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-messaging.js"></script>

    <!-- Just include firebase.js file. The other firebase will be internally registered from this file -->
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>/fcm/firebase.js"></script>
    
    
<!-- FCM End -->
    <?php
echo GOOGLE_ANALYTICAL_CODE;
echo HTML_TRACKING_CODE;
?>
</body>

</html>