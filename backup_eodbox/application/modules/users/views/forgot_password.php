 <!--============= Hero Section Starts Here =============-->
    <div class="hero-section">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>">Home</a>
                </li>
                
                <li>
                    <span><?php echo lang('reset_password'); ?></span>
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
                        <h2 class="title"><?php echo lang('reset_password'); ?></h2>
                        <p><?php echo lang('to_rest_psd_message'); ?></p>
                    </div>
                    <?php	if($this->session->flashdata('message_err')){ ?>
                      <div role="alert" class="alert alert-danger">
                        <span aria-label="Close" data-dismiss="alert" class="close">×</span>
                        <i class="fa fa-warning">&nbsp;</i><?php echo $this->session->flashdata('message_err'); ?></div>
                      <?php } ?>
                      <?php
                     if($this->session->flashdata('message')){ ?>
                      <div role="alert" class="alert alert-success">
                        <span aria-label="Close" data-dismiss="alert" class="close">×</span>
                        <i class="fa fa-check">&nbsp;</i> <?php echo $this->session->flashdata('message'); ?></div>
                      <?php
                       }
                  ?>
                    <form id="forget-form" class="login-form" method="post" action="" >
                        <div class="form-group mb-30">
                            <label for="login-email"><i class="far fa-envelope"></i></label>
                            
                            <input type="email" name="email" placeholder="Enter your email address" autofocus="">
                        </div>
                        
                        
                        <div class="form-group mb-0">
                            <button type="submit" class="custom-button"><?php echo lang('send'); ?></button>
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