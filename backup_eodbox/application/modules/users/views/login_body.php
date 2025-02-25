<script src="https://apis.google.com/js/client:platform.js?onload=renderButton" async defer></script>

 <!--============= Hero Section Starts Here =============-->
    <div class="hero-section">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>">Home</a>
                </li>
                
                <li>
                    <span>Login</span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/banner/hero-bg.png"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->


    <!--============= Account Section Starts Here =============-->
    <section class="account-section padding-bottom">
        <div class="container">
            <div class="account-wrapper mt--100 mt-lg--440">
                <div class="left-side">
                    <div class="section-header">
                        <h2 class="title">HI, THERE</h2>
                        <p>You can log in to your eodbox account here.</p>
                    </div>
                    <ul class="login-with">
                        <li>
                            <a href="javascript:void(0);" onclick="return facebookLogin();"><i class="fab fa-facebook"></i>Log in with Facebook</a>
                        </li>
                        <li>	
                        <?php /*?><div id="gSignIn"></div><?php */?>
                            <a href="javascript:void(0);" onclick="googlelogin()"><i class="fab fa-google-plus"></i>Log in with Google</a>
                        </li>
                    </ul>
                    <div class="or">
                        <span>Or</span>
                    </div>
                    
                    <?php
      if($this->session->flashdata('loginerror'))
        { ?>
      <div role="alert" class="alert alert-danger">
        <span aria-label="Close" data-dismiss="alert" class="close" type="button">×</span>
        <i class="fa fa-warning">&nbsp;</i><?php echo $this->session->flashdata('loginerror') ?></div>
      <?php
        }
      ?>
      <?php
        if($this->session->flashdata('message'))
        { ?>
      <div role="alert" class="alert alert-success">
        <span aria-label="Close" data-dismiss="alert" class="close" type="button">×</span>
        <i class="fa fa-check">&nbsp;</i> <?php echo $this->session->flashdata('message') ?></div>
      <?php

        }
         ?>
         
         <div role="alert" id="error_block" class="alert alert-danger" style="display:none;">
        <span aria-label="Close" data-dismiss="alert" class="close" type="button">×</span>
        <i class="fa fa-warning">&nbsp;</i><span id="error_message"></span></div>
        
                    <form class="login-form" id="lgn-form" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                        <div class="form-group mb-30">
                            <label for="login-email"><i class="far fa-user"></i></label>
                            <input type="text" id="login-email" name="user_name" value="<?php echo trim($this->input->cookie(SESSION.'username'));?>" placeholder="<?php echo lang('username'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="login-pass"><i class="fas fa-lock"></i></label>
                            <input type="password"  name="password" id="login-pass" placeholder="<?php echo lang('password'); ?>" value="<?php echo trim($this->input->cookie(SESSION.'password'));?>">
                            <span class="pass-type"><i class="fas fa-eye"></i></span>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo $this->general->lang_uri('/users/login/forgot')?>"><?php echo lang('forgot_password_?'); ?></a>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="custom-button"><?php echo lang('log_in'); ?></button>
                        </div>
                    </form>
                </div>
                <div class="right-side cl-white">
                    <div class="section-header mb-0">
                        <h3 class="title mt-0">ARE YOU NEW HERE? REGISTERATION TAKES LESS THAN A MINUTE</h3>
                        <p>Sign up and create your Account</p>
                        <a href="<?php echo $this->general->lang_uri('/users/register')?>" class="custom-button transparent">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Account Section Ends Here =============-->

 
          
  <!-- email form for social login if they are not available -->

  </script>
<script type="text/javascript">
  var FacebookAppID = '<?php echo FACEBOOK_APP_ID; ?>'; 
  var login_url='<?php echo $this->general->lang_uri('/users/login') ?>';
  var login_add='<?php echo $this->general->lang_uri('/users/login/check_unique_email/') ?>';
  var urlGoogleLogin = '<?php echo $this->general->lang_uri("/users/login/google_login/");?>';
  var check_existing_user='<?php echo $this->general->lang_uri("/users/login/check_existing_user/");?>';
  var googleAppKey = '<?php echo GOOGLE_APP_KEY; ?>';
  var googleClientId = '<?php echo GOOGLE_CLIENT_ID; ?>'; 
  var check_unique_email_url="<?php echo $this->general->lang_uri('/users/login/check_unique_email') ;?>";
var check_email_username_url="<?php echo $this->general->lang_uri('/users/register/check_username')?>";
var check_email_url="<?php echo $this->general->lang_uri('/users/register/check_email')?>";
</script>

<script>var urlFacebookLogin="<?php echo $this->general->lang_uri('/users/login/facebook_login')?>";</script>
<script type="text/javascript" src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>facebook_login.js"></script> 
<script type="text/javascript" src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>google_login.js"></script> 




