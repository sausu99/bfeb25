<!--============= Hero Section Starts Here =============-->
        <div class="hero-section style-2">
            <div class="container">
                <ul class="breadcrumb">
                    <li>
                        <a href="<?php echo $this->general->lang_uri(); ?>"><?php echo lang("home");?></a>
                    </li>
                    
                    <li>
                        <span><?php if(isset($cms->heading))echo $cms->heading; ?></span>
                    </li>
                </ul>
            </div>
            <div class="bg_img hero-bg bottom_center" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>banner/hero-bg.png"></div>
        </div>
        <!--============= Hero Section Ends Here =============-->

    <!--============= PS section Starts =============-->
    
    <section class="about-section">
        <div class="container">
            <div class="about-wrapper mt--100 mt-lg--440 padding-top">
                <div class="row">
                    <div class="about-content">
                        <h4 class="subtitle"><?php if(isset($cms->heading))echo $cms->heading; ?></h4>
                        <?php if($cms->video_file){ ?>
                <video width="100%"  poster="" controls>
                                                <source src="<?php echo base_url(BANNER_PATH . $cms->video_file); ?>" type="video/mp4">
                                             
                                                Your browser does not support the video tag.
                    </video>

              <?php } ?>

            <?php if(isset($cms->content))echo $cms->content;?>
                        
                    </div>
                </div>
            </div>
            
        </div>
        

    </section>