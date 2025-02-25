        
            
<!--============= Hero Section Starts Here =============-->
    <div class="hero-section">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="index.html">Home</a>
                </li>
                <li>
                    <span>Contact Us</span>
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
                    <h5 class="cate">Contact Us</h5>
                    <h2 class="title">get in touch</h2>
                    <p>We'd love to hear from you! Let us know how we can help.</p>
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
                                <input type="text" placeholder="Your Name"  name="fname" value="<?php echo set_value('fname');?>" id="name">
                            </div>
                            <div class="form-group">
                                <label for="name"><i class="fas fa-envelope-open-text"></i></label>
                                <input type="text" placeholder="Enter Your Email ID"  name="email" value="<?php echo set_value('email');?>">
                            </div>
                            <div class="form-group">
                                <label for="message" class="message"><i class="far fa-envelope"></i></label>
                                <textarea name="message" id="message" placeholder="Type Your Message"></textarea>
                            </div>
                            <div class="form-group text-center mb-0">
                                <button type="submit" class="custom-button" id="btnContact">Send Message</button>
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
          
     