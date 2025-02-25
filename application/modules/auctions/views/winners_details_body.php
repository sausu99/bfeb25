<?php
$current_date_time = $this->general->get_local_time('time');
$show_time = strtotime($auc_data->end_date) - strtotime($current_date_time);
?>

<?php
if( $auc_data->price>0)
$auction_price = $auc_data->price;
else
   $auction_price = 1; 

$shipping_cost = $auc_data->shipping_cost;
$curren_winning_amot = $auc_data->current_winner_amount;
$total_price = $curren_winning_amot + $shipping_cost;

$saving_per = number_format(( ($auction_price - $total_price) / $auction_price ) * 100, '2', '.', '');
?>
<script>
    var aid = '<?php echo $auc_data->product_id; ?>';
    var share_url = "<?php echo current_url(); ?>"
</script>
<script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH); ?>bidlub.js"></script>
<?php
    if ($this->session->flashdata('error_message')) {
        ?>
        <div role="alert" class="alert alert-danger">
            <button aria-label="Close" data-dismiss="alert" class="close" type="button">×</button>
            <i class="fa fa-warning">&nbsp;</i><?php echo $this->session->flashdata('error_message') ?></div>
        <?php
    }
    ?>
<?php
                    $watch = $this->general->get_watchlist_check($this->session->userdata(SESSION . 'user_id'), $auc_data->product_id);

                    $watchclass = '';
                    if ($this->session->userdata(SESSION . 'user_id')) {
                        if ($watch > 0) {
                            $watchclass = 'wthselect';
                        } else {
                            $watchclass = '';
                        }
                    }
                    ?>
<!--============= Hero Section Starts Here =============-->
    <div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>"><?php echo lang("home");?></a>
                </li>
                
                <li>
                    <span><?php echo $auc_data->cat_name;?></span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>banner/hero-bg.png"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->


    <!--============= Product Details Section Starts Here =============-->
    <section class="product-details padding-bottom mt--240 mt-lg--440">
        <div class="container">
            <div class="product-details-slider-top-wrapper">
                <div class="product-details-slider owl-theme owl-carousel" id="sync1">
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . $auc_data->image1); ?>">
                        </div>
                    </div>
                    <?php if ($auc_data->image2) { ?>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . $auc_data->image2); ?>">
                        </div>
                    </div>
                    <?php }if ($auc_data->image3) { ?>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . $auc_data->image3); ?>">
                        </div>
                    </div>
                    <?php }if ($auc_data->image4) { ?>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . $auc_data->image4); ?>">
                        </div>
                    </div>
                    <?php }?>
                    
                    
                </div>
            </div>
            
            <?php if ($auc_data->image2) { ?>
            <div class="product-details-slider-wrapper">
                <div class="product-bottom-slider owl-theme owl-carousel" id="sync2">
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image1); ?>">
                        </div>
                    </div>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image2); ?>">
                        </div>
                    </div>
                    <?php if ($auc_data->image3) { ?>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image3); ?>">
                        </div>
                    </div>
                    <?php }if ($auc_data->image4) { ?>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image4); ?>">
                        </div>
                    </div>
                    <?php } ?>
                    
                </div>
                <span class="det-prev det-nav">
                    <i class="fas fa-angle-left"></i>
                </span>
                <span class="det-next det-nav active">
                    <i class="fas fa-angle-right"></i>
                </span>
            </div>
            <?php }?>
            <div class="row mt-40-60-80">
                <div class="col-lg-4">
                    <div class="product-sidebar-area">
                        <div class="product-single-sidebar mb-3">
                            <h6 class="title"><?php echo lang('item_auc_ended_on'); ?>:</h6>
                            <div class="countdown">
                                <div id="timer_<?php echo $auc_data->id; ?>">
                                <?php
                                        $user_id = $this->session->userdata(SESSION . 'country_id');
                                        if (isset($user_id))
                                            $timeZone = $this->general->get_user_timezone_by_country($user_id);
                                        else
                                            $timeZone = DEFAULT_TIMEZONE;

                                        echo $this->general->convert_local_time($auc_data->end_date, $timeZone);
                                        ?>
                                </div>
                            </div>
                            <div class="side-counter-area">
                                <div class="side-counter-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/icon2.png" alt="product">
                                    </div>
                                    <div class="content">
                                        <h3 class="count-title"><span><?php echo $total_watchlist;?></span></h3>
                                        <p><?php echo lang("label_watching");?></p>
                                    </div>
                                </div>
                                <div class="side-counter-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/icon3.png" alt="product">
                                    </div>
                                    <div class="content">
                                        <h3 class="count-title"><span><?php echo $total_bids;?></span></h3> 
                                        <p><?php  echo lang("label_total_bids");?></p>
                                    </div>
                                </div>
                                <div class="side-counter-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/icon1.png" alt="product">
                                    </div>
                                    <div class="content">
                                        <h3 class="count-title"><span><?php echo $auc_data->bid_fee; ?></span></h3> 
                                        <p><?php echo lang('bid_fee'); ?></p>
                                    </div>
                                </div>
                                <?php /*?><div class="side-counter-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/icon3.png" alt="product">
                                    </div>
                                    <div class="content">
                                        <h3 class="count-title"><span>2</span></h3> 
                                        <p>Bonus earned</p>
                                    </div>
                                </div><?php */?>
                            </div>
                        </div>
                        
                    </div>
                    
                        
                </div>
                <div class="col-lg-8">
                    <div class="product-details-content">
                        <div class="product-details-header">
                            <h2 class="title"><?php echo $auc_data->name; ?></h2>
                            <ul>
                                <li><?php echo lang("label_listing_id");?>: <?php echo $auc_data->id; ?></li>
                                <li><?php echo lang("label_item");?> #: <?php echo $auc_data->product_id; ?></li>
                                <li><?php echo lang('retail_price'); ?> : <span> <?php echo $this->general->formate_price_currency_sign($auc_data->price, '<span>', '</span>'); ?></span></li>
                            </ul>
                            
                            <hr class="mb-4">
                            
                            <div class="well">
                    <div class="row winner_block">
                        <div class="col-md-3 no-pad-right text-center">
                            <?php
                            if ($auc_data->gender == 'M') {
                                $profile_image = base_url('assets/images/MALE.jpg');
                            } else {
                                $profile_image = base_url('assets/images/FEMALE.jpg');
                            }
                            ?>
                            <figure><img src="<?php echo ($auc_data->image) ? site_url(USER_PROFILE_PATH . $auc_data->image) : $profile_image; ?>" height="75" alt="<?php echo $auc_data->name; ?>"></figure>
                            <?php echo ($auc_data->current_winner_name) ? $auc_data->current_winner_name : $auc_data->first_name . ' ' . $auc_data->last_name; ?>
                        </div>
                        <div class="col-md-9">
                        	<div class="row bid_a">
                    			<span class="col-md-12"><i class="fa fa-trophy"></i> &nbsp;&nbsp;<?php echo lang('winning_bid'); ?> : <?php echo $this->general->formate_price_currency_sign($auc_data->current_winner_amount, '<b>', '</b>'); ?></span>
                    			<span class="col-md-12"><i class="fa fa-cog" aria-hidden="true"></i> &nbsp;&nbsp;<?php echo lang('total_savings'); ?>: <b><?php echo $saving_per; ?>%</b></span>
                                
                                <span class="col-md-12"><i class="fa fa-plus"></i> &nbsp;&nbsp; <?php echo lang('total_credits_used'); ?>: <b><?php echo $auc_data->total_bids; ?></b></span>
                			</div>
                            
                            
                        </div>
                    </div>
                </div>
                			<hr>
                            <div class="buy-now-area" style="padding-top:0px;">
                            
                            <div class="share-area">
                                <span><?php echo lang("label_share_to");?>:</span>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)" onclick="share_on_fb()"><i class="fab fa-facebook-f"></i></a>
                                    </li>
                                    <li>
                                        <a  href="http://twitter.com/intent/tweet/?url=<?php echo current_url();?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo current_url();?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/?url=<?php echo current_url();?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                    </li>
                                </ul>
                            </div>
                            
                        </div>                
                        	<hr class="mb-4">
                        </div>
                        
                        </div>
                        <div class="container">
                           
                            <span><?php echo lang("view");?> </span>
                            <?php 
							 $first_row_cms =  $this->general->get_cms_lists(array('3','4','43','44')); 
							 if($first_row_cms){foreach($first_row_cms as $fcms){?>
							 <span><a href="<?php echo $this->general->lang_uri("/page/".$fcms->cms_slug);?> "><?php echo $fcms->heading;?></a></span>, 
							 <?php }}?>
                        </div>

                    </div>
                </div>
                
            </div>
        </div>
        <div class="product-tab-menu-area mb-40-60 mt-70-100">
            <div class="container">
                <ul class="product-tab-menu nav nav-tabs">
                    <li>
                        <a href="#details" class="active" data-toggle="tab" style="justify-content:left;">
                            <div class="thumb">
                                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/tab1.png" alt="product">
                            </div>
                            <div class="content"><?php echo lang("account_purchase_description");?></div>
                        </a>
                    </li>
                    
                    
                    <?php if($auction_faq_data){?>
                    <li>
                        <a href="#questions" data-toggle="tab">
                            <div class="thumb">
                                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/tab4.png" alt="product">
                            </div>
                            <div class="content"><?php echo lang("label_questions");?> </div>
                        </a>
                    </li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="details">
                    <div class="tab-details-content">
                        <?php echo $auc_data->description; ?>
                    </div>
                </div>
                
                
                <?php if($auction_faq_data){?>
                <div class="tab-pane fade show" id="questions">
                        <h5 class="faq-head-title"><?php echo lang("label_faq_full");?></h5>
                        <div class="faq-wrapper">
                        <?php $fi=1; foreach($auction_faq_data as $faq){?>
                            <div class="faq-item <?php if($fi == 1){echo 'open active';}?>">
                                <div class="faq-title">
                                    <img src="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>img/faq.png" alt="css"><span class="title"><?php echo $faq->title;?></span><span class="right-icon"></span>
                                </div>
                                <div class="faq-content">
                                    <p><?php echo $faq->description;?></p>
                                </div>
                            </div>
                            <?php $fi++;}?>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </section>
    <!--============= Product Details Section Ends Here =============-->