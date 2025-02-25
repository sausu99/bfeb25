<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php echo $template['title']; ?></title>
        <meta name="keywords" content="<?php echo $meta_keys; ?>" />
        <meta name="description" content="<?php echo $meta_desc; ?>" />
		<meta name="google-signin-client_id" content="<?php echo GOOGLE_CLIENT_ID;?>">
   	 <?php if (isset($facebook_opengraph) && $facebook_opengraph == 'yes') { ?>
            <meta property="og:url" content="<?php echo current_url(); ?>" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="<?php echo $og_title; ?>" />
            <meta property="og:description" content="<?php echo strip_tags($this->general->string_limit($og_description, 50)); ?>" />
            <meta property="og:image" content="<?php echo site_url(AUCTION_IMG_PATH . $og_image); ?>" />
            <meta property="og:site_name" content="<?php echo WEBSITE_NAME; ?>" />
            <meta property="fb:app_id" content="<?php echo FACEBOOK_APP_ID; ?>"/>
        <?php } ?>
    <script type="text/javascript">
			var site_url = '<?php echo $this->general->lang_uri(''); ?>';    
            var user_id = '<?php echo $this->session->userdata(SESSION . 'user_id') ?>';
    </script>
          
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/all.min.css">
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/animate.css">
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/nice-select.css">
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/owl.min.css">
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/flaticon.css">
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>/main.css">

    <link rel="shortcut icon" href="<?php echo  site_url(MAIN_IMG_DIR_FULL_PATH); ?>/favicon.png" type="image/x-icon">
    
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>jquery-3.3.1.min.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>jquery.timer.js"></script>
    <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>language-en.js"></script> 
	<script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>emts.js"></script>
</head>

<body>
    <!--============= ScrollToTop Section Starts Here =============-->
    <div class="overlayer" id="overlayer">
        <div class="loader">
            <div class="loader-inner"></div>
        </div>
    </div>
    <a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
    <div class="overlay"></div>
    <!--============= ScrollToTop Section Ends Here =============-->


    <!--============= Header Section Starts Here =============-->
    <header>
        <div class="header-top">
            <div class="container">
                <div class="header-top-wrapper">
                    <ul class="customer-support">
                        <li>
                        <a href="<?php echo $this->general->lang_uri('/contact-us'); ?>" class="mr-3"><i class="fas fa-phone-alt"></i><span class="ml-2 d-none d-sm-inline-block"><?php echo lang('contact_us'); ?></span></a>
                        </li>
                        <li>
                        
                        <?php 
						$countries_menu = $this->general->get_active_countries();
                        if (count($countries_menu)>1) {
						if (!$this->session->userdata(SESSION . 'short_code')) { ?>
						<i class="fas fa-globe"></i>
                            <select name="language" id="language" class="select-bar">
                        <?php
                        
                            foreach ($countries_menu as $cnty) {
                                ?>
								<option value="<?php echo $cnty->short_code;?>" <?php if(LANG_SHORT_CODE == $cnty->short_code){ echo 'selected';}?> data-url="<?php echo $this->general->lang_switch_uri($cnty->short_code); ?>"><?php echo $cnty->country; ?></option>                         <?php
                            }
                        
                        ?>
                        </select>
            <?php }} ?>

                        </li>
                    </ul>
                    
                    <?php if ($this->session->userdata(SESSION . 'user_id')){ ?>
                                <div class="col-sm-4 col-xs-7 pull-right text-right after_login">
                                    <ul class="cart-button-area">
                                    	<li>
                            <a href="<?php echo $this->general->lang_uri('/my-account/user/purchases'); ?>" class="cart-button"><i class="flaticon-money"></i><span class="amount" id="normalCredit"><?php echo $balance = $this->general->get_user_balance($this->session->userdata(SESSION . 'user_id')); ?></span> </a>
                        </li>
                                        <li>
                                            
                                                <?php
                                                
												$user_profile_imag = $this->session->userdata(SESSION . 'profile_pic');
												
                                                if ($user_profile_imag!='') {
                                                    $p_pic = site_url(USER_PROFILE_PATH . $user_profile_imag);
                                                } else {
                                                    if ($this->session->userdata(SESSION . 'gender') == 'M') {
                                                        $p_pic = site_url('assets/images/MALE.jpg');
                                                    } else {
                                                        $p_pic = site_url('assets/images/FEMALE.jpg');
                                                    }
                                                }
                                                ?>
                                                <span class="nav_user_img"> <img src="<?php echo $p_pic ?>" id="p_pic"> </span> <a href="<?php echo $this->general->lang_uri('/my-account/user/index'); ?>"><?php echo lang('account_my_account');?></a>
                                                
                                           
                                        </li>
                                        <li>
                            <?php /*if($this->session->userdata(SESSION.'reg_type')=="google"){?>
                            <a href="#" onClick="signOut()" style="padding-top:11px;"><?php echo lang('sign_out'); ?></a>
                            <?php }else{*/?>
                            <a href="<?php echo $this->general->lang_uri('/users/logout'); ?>" style="padding-top:11px;"><?php echo lang('sign_out'); ?></a>
                            <?php /*}*/?>
                        </li>
                                    </ul>
                                    
                                    
                                </div>
                            <?php }else{ ?>
                            
                    <ul class="cart-button-area">
                        
                        <li>
                            <a href="<?php echo $this->general->lang_uri('/users/login') ?>" class="user-button"><i class="flaticon-user"></i></a>
                        </li>                        
                    </ul>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="header-bottom">
            <div class="container">
                <div class="header-wrapper">
                    <div class="logo">
                        <a href="<?php echo $this->general->lang_uri();?>">
                            <img src="<?php echo site_url(SITE_LOGO_PATH) . SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>">
                        </a>
                    </div>
                    <ul class="menu ml-auto">
                       <li <?php
                                    if (isset($active_menu) && $active_menu == 'home') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="<?php echo $this->general->lang_uri(); ?>"><?php echo lang('home'); ?></a></li>
                                    <li <?php
                                    if (isset($active_menu) && $active_menu == 'live') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="<?php echo $this->general->lang_uri('/auctions/live'); ?>"><?php echo lang('live_auctions'); ?></a></li>
                                    
                                    
                                    <li <?php
                                    if (isset($account_menu_active) && $account_menu_active == 'buybids') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/buybids') ?>"><?php echo lang('purchase_bid_credits'); ?></a></li>
                                    
                                        <?php
                                        $first_row_cms = $this->general->get_cms_lists(array('6'));
                                        if ($first_row_cms) {
                                            foreach ($first_row_cms as $fcms) {
                                                ?>
                                            <li><a href="<?php echo $this->general->lang_uri("/page/" . $fcms->cms_slug); ?> "><?php echo lang('how_it_works'); ?></a></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <li><a href="<?php echo $this->general->lang_uri("/help/index");?>"><?php echo lang('faq'); ?></a></li>
                                    
                    </ul>
                    <form class="search-form" action="<?php echo $this->general->lang_uri("/auctions/live");?>" method="get">
                        <input type="text" name="srch" placeholder="<?php echo lang('placeholder_search_for_name');?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <div class="search-bar d-md-none">
                        <a href="#0"><i class="fas fa-search"></i></a>
                    </div>
                    <div class="header-bar d-lg-none">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--============= Header Section Ends Here =============-->
    
<script>
$("#language").change(function(){
  window.location.href = $(this).find(':selected').data('url');
});
</script>