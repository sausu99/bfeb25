 <!--============= Hero Section Starts Here =============-->
    <div class="hero-section">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>"><?php echo lang("home");?></a>
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
                <div class="left-side" style="width:100%">
                	
                    <div class="row justify-content-md-center">
                    	<div class="col-sm-6">
                    <div class="section-header">
                        <h2 class="title"><?php echo lang('reset_password'); ?></h2>
                        
                    </div>
                    <?php	if($this->session->flashdata('loginerror')){ ?>
                      <div role="alert" class="alert alert-danger">
                        <span aria-label="Close" data-dismiss="alert" class="close">×</span>
                        <i class="fa fa-warning">&nbsp;</i><?php echo $this->session->flashdata('loginerror'); ?></div>
                      <?php } ?>
                      <?php
                     if($this->session->flashdata('message')){ ?>
                      <div role="alert" class="alert alert-success">
                        <span aria-label="Close" data-dismiss="alert" class="close">×</span>
                        <i class="fa fa-check">&nbsp;</i> <?php echo $this->session->flashdata('message'); ?></div>
                      <?php
                       }
                  ?>
                    
                    <form id="register-form" class="login-form" method="post" action="" >
                    
                    <div class="row">
                    
              <div class="col-md-12 mb-3">
                <label for="firstName"><?php echo lang('change_pass_new'); ?></label>
                <input type="text" class="form-control" name="password" value="<?php echo set_value('password'); ?>" >
                <?= form_error('password') ?>
              </div>
              <div class="col-md-12 mb-3">
                <label for="lastName"><?php echo lang('confirm_new_password'); ?></label>
                <input type="text" name="repassword" value="<?php echo set_value('repassword'); ?>" class="form-control">
                  <?= form_error('repassword') ?>
              </div>
            </div>
            
            
            
            <div class="form-group mb-0">
                            <button type="submit" class="custom-button"><?php echo lang('reset'); ?></button>
                        </div>
            
            
      </form>
      				</div>
      				</div>
                </div>
                
            </div>
        </div>
    </section>
    <!--============= Account Section Ends Here =============-->


