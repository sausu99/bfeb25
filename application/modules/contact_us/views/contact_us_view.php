        
            
<!--============= Hero Section Starts Here =============-->
    <div class="hero-section">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>"><?php echo lang("home");?></a>
                </li>
                <li>
                    <span><?php echo lang('contact_us');?></span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/banner/hero-bg.png"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->


    <!--============= Contact Section Starts Here =============-->
    <section class="contact-section padding-bottom">
        <div class="container">
            <div class="contact-wrapper padding-top padding-bottom mt--100 mt-lg--440">
                <div class="section-header">
                    <h5 class="cate"><?php echo lang('contact_us');?></h5>
                    <h2 class="title"><?php echo lang("label_get_in_touch");?></h2>
                    <p><?php echo lang("label_contact_short_msg");?></p>
                </div>
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                     <?php if($this->session->flashdata('message')){?>
        <div role="alert" class="alert alert-success alert_msg">
         <?php echo $this->session->flashdata('message');?></div>
        <?php }?>
                        <form method="post" class="contact-form" id="contact-form" >
                            <div class="form-group">
                                <label for="name"><i class="far fa-user"></i></label>
                                <input type="text" placeholder="<?php echo lang('label_ur_name');?>"  name="fname" value="<?php echo set_value('fname');?>" id="name">
                            </div>
                            <div class="form-group">
                                <label for="name"><i class="fas fa-envelope-open-text"></i></label>
                                <input type="text" placeholder="<?php echo lang('label_ur_email');?>"  name="email" value="<?php echo set_value('email');?>">
                            </div>
                            <div class="form-group">
                                <label for="message" class="message"><i class="far fa-envelope"></i></label>
                                <textarea name="message" id="message" placeholder="<?php echo lang('label_ur_message');?>"></textarea>
                            </div>
                            <div class="form-group text-center mb-0">
                                <button type="submit" class="custom-button" id="btnContact"><?php echo lang('label_send_message');?></button>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-4 col-lg-5 d-lg-block d-none">
                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/contact.png" class="w-100" alt="images">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Contact Section Ends Here =============-->
          
     