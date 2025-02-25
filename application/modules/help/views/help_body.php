<!--============= Hero Section Starts Here =============-->
    <div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri(); ?>"><?php echo lang("home");?></a>
                </li>
                <li>
                    <span><?php echo lang('faq');?></span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>banner/hero-bg.png"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->


    <!--============= Faq Section Starts Here =============-->
    <section class="faq-section padding-bottom mt--240 mt-lg--440 pos-rel">
        <div class="container">
            <div class="section-header cl-white mw-100 left-style">
                <h2 class="title"><?php echo lang('faq');?></h2>
                <p><?php echo lang("label_faq_short_desc");?></p>
            </div>
            <div class="row mb--50">
                <div class="col-lg-12 mb-50">
                    <div class="faq-wrapper">
                    <?php 
					if($help_cat)
					{
						$i = 1;
						foreach($help_cat as $help)
						{
					?>
                    
                    <h3><?php echo $help->help_category_name;?></h3>
                    
                    <?php
            		
					foreach($this->front_help->get_help_bycatid($help->id) as $help_topic)
					{
				?>
                <div class="faq-item">
                            <div class="faq-title">
                                <img src="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>img/faq.png" alt="css"><span class="title"><?php echo $help_topic->title;?></span><span class="right-icon"></span>
                            </div>
                            <div class="faq-content">
                                <p><?php echo $help_topic->description;?></p>
                            </div>
                        </div>
  			<?php $i++;}	?>
  			
			</ul>
           <?php
				}
			}
	?>
    
                        
                    </div>
                </div>
                <?php /*?><div class="col-lg-4 mb-50">
                    <aside class="sticky-menu">
                        <div class="faq-video mb-30">
                            <a href="https://www.youtube.com/watch?v=Mj3QejzYZ70" class="video-area popup">
                                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>faq/video.png" alt="faq">
                                <div class="video-button-2">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <i class="fas fa-play"></i>
                                </div>
                            </a>
                            <h5 class="title">Watch a Video before you Bid</h5>
                        </div>
                        <div class="popular-article pt-30 mb--20">
                            <h4 class="title mb-20">Most Popular Articles</h4>
                            <div class="popular-article-item">
                                <!-- <a href="#0" class="right-con"><i class="flaticon-right-arrow"></i></a> -->
                                <h5 class="title"><a href="#0">Tips for winning</a></h5>
                                <p>Found an item you love? Here are some tips for winning your next item:</p>
                            </div>
                            <div class="popular-article-item">
                                <!-- <a href="#0" class="right-con"><i class="flaticon-right-arrow"></i></a> -->
                                <h5 class="title"><a href="#0">How to bid at an Auction</a></h5>
                                <p>Bidding at auction can be terrifying,
                                    especially your first time.</p>
                            </div>
                            <div class="popular-article-item">
                                <!-- <a href="#0" class="right-con"><i class="flaticon-right-arrow"></i></a> -->
                                <h5 class="title"><a href="#0">Bid increments</a></h5>
                                <p>Each auction house sets their own
                                    bidding increments</p>
                            </div>
                        </div>
                    </aside>
                </div><?php */?>
            </div>
        </div>
    </section>
    <!--============= Faq Section Ends Here =============-->
            
