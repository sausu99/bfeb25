<?php
$current_date_time = $this->general->get_local_time('time');
$show_time = strtotime($auc_data->end_date) - strtotime($current_date_time);
?>
<script>
    var perpage = '10';
    var aid = '<?php echo $auc_data->product_id; ?>';
    var baseUrl = '<?php echo base_url() ?>';
    var loginURL = '<?php echo $this->general->lang_uri('/user/login'); ?>';
    var LUB_single_bid_URL = '<?php echo $this->general->lang_uri("/bidding/lub_bidding/single_bid"); ?>';
    var LUB_multi_bid_URL = '<?php echo $this->general->lang_uri("/bidding/lub_bidding/multiple_bid"); ?>';
    var LUB_get_user_bids_URL = '<?php echo $this->general->lang_uri("/bidding/lub_bidding/get_user_bids"); ?>';
	var LUB_get_other_user_bids_URL = '<?php echo $this->general->lang_uri("/bidding/lub_bidding/get_other_user_bids"); ?>';
    var share_url = "<?php echo current_url(); ?>"
</script>
<script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH); ?>bidlub.js"></script>

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
            
            <?php
			if ($this->session->flashdata('error_message')) {
				?>
				<div role="alert" class="alert alert-danger alert-dismissible fade show">
					
                    <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
                            <p><span id="bid_error"><?php echo $this->session->flashdata('error_message') ?></span></p>
                            
                </div>
                    
				<?php
			}
			?>
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
                    <?php }if ($auc_data->image5) { ?>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . $auc_data->image5); ?>">
                        </div>
                    </div>
                    <?php }if ($auc_data->image6) { ?>
                    <div class="slide-top-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . $auc_data->image6); ?>">
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
                    <?php }if ($auc_data->image5) { ?>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image5); ?>">
                        </div>
                    </div>
                    <?php }if ($auc_data->image6) { ?>
                    <div class="slide-bottom-item">
                        <div class="slide-inner">
                            <img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image6); ?>">
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
                            <h6 class="title"><?php echo lang('remaining_times'); ?>:</h6>
                            <div class="countdown">
                                <div id="timer_<?php echo $auc_data->id; ?>">
                                <script>
									    get_timer('timer_<?php echo $auc_data->id; ?>', '<?php echo $auc_data->id; ?>', '<?php echo $show_time; ?>', '', '2');
                            	</script>
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
                    
                        <div class="buy-now-area">
                            
                            
                            <a href="javascript:void(0)" class="rating custom-button active border wthlst <?php echo $watchclass; ?>" data-productid='<?php echo $auc_data->product_id; ?>'  ><i class="fas fa-star with_<?php echo $auc_data->product_id; ?> <?php echo $watchclass; ?>"></i> <?php echo lang("label_add_fevorites");?></a>
                            
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
                </div>
                <div class="col-lg-8">
                    <div class="product-details-content">
                        <div class="product-details-header">
                            <h2 class="title"><?php echo $auc_data->name; ?></h2>
                            <ul>
                                <li><?php echo lang("label_listing_id");?>: <?php echo $auc_data->id; ?></li>
                                <li><?php echo lang("label_item");?> #: <?php echo $auc_data->product_id; ?></li>
                            </ul>
                            <hr class="mb-4">
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show" style="display:none" id="bidResponse">
                            <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
                            <p><strong>Error</strong> - <span id="bid_error">You have already placed a bid for 0.2 Range should not contain repeat bids, please retry.</span></p>
                        </div>                        
                        <div class="alert alert-success alert-dismissible fade show" style="display:none" id="bidResponseSuccess">
                            <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
                            <p><span id="bid_success"></span></p>
                        </div>       
                        <div style="color: rgb(224, 124, 17); font-weight: bold;">
                            <?php echo lang('single_price'); ?>
                        </div>
                        
                        
                        <div class="product-bid-area">
                        
                            <div class="container">
                                <form method="post" action="" class="product-bid-form" id="singleBidForm">
                                    <input type="number" class="form-control" name="amount" id="amount" value="" placeholder="ex 0.01">
									<?php if ($this->session->userdata(SESSION . 'user_id')) { ?>
                                    <button class="custom-button" id="singleBidBtn"><?php echo lang("LUB_bidding_bid_placed_text");?></button>
                                    <input type="hidden" name="auc_id" value="<?php echo $auc_data->product_id; ?>" />
                                <?php } else { ?>
                                    <a class="custom-button" href="<?php echo $this->general->lang_uri("/users/login"); ?>"><?php echo lang("LUB_bidding_bid_placed_text");?></a>
                                <?php } ?>
                                
                                    
                                </form>
                            </div>
                        </div>
                        <div style="color: rgb(224, 124, 17); font-weight: bold;">
                            <?php echo lang('price_range'); ?>
                        </div>
                        <div class="product-bid-area">
                            
                            <div class="container">
                                
                                <form class="product-bid-form" name="multiBidForm" id="multiBidForm">
                                <input type="number" class="form-control" name="amount_f" id="amount_f" value="" placeholder="From Ex 0.01" >
                                <input type="number" class="form-control" name="amount_t" id="amount_t" value="" placeholder="To Ex 0.90" >
                                <div class="col-lg-12">
                                <?php if ($this->session->userdata(SESSION . 'user_id')) { ?>
                                    <button class="custom-button" id="multiBidBtn"><?php echo lang("LUN_bidding_bid_bttn_rang");?></button>
                                    <input type="hidden" name="auc_id" value="<?php echo $auc_data->product_id; ?>" />
                                <?php } else { ?>
                                    <a class="custom-button" href="<?php echo $this->general->lang_uri("/users/login"); ?>"><?php echo lang("LUN_bidding_bid_bttn_rang");?></a>
                                <?php } ?>
                                </div>
                                </form>
                            </div>
                        </div>
                        <?php if ($auc_data->is_buy_now == 'Yes' && $auc_data->no_qty > $this->general->total_sold_product($auc_data->product_id)) { ?>
                        <div style="color: rgb(224, 124, 17); font-weight: bold;">
                            <?php echo lang("label_for_direct_pur");?>
                        </div>
                        <div class="buy-now-area">
                            <div class="container">
                                <a href="<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/buynow/' . $auc_data->product_id . '/' . str_replace(' ','-',$auc_data->name)) ?>" class="custom-button"><?php echo lang('buy_now'); ?>: <?php echo $this->general->formate_price_currency_sign($auc_data->buy_now_price, '<b>', '</b>'); ?></a>
                            
                            </div>
                            <?php } ?>
                            
                        </div>
                        <div class="container">
                            <hr class="mb4" />
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
                        <a href="#details" class="active" data-toggle="tab">
                            <div class="thumb">
                                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/tab1.png" alt="product">
                            </div>
                            <div class="content"><?php echo lang("account_purchase_description");?></div>
                        </a>
                    </li>
                    <li>
                        <a href="#MyBidHistory" data-toggle="tab">
                            <div class="thumb">
                                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/tab3.png" alt="product">
                            </div>
                            <div class="content"><?php echo lang("LUB_home_Live_page_bidding_history_title");?> (<span id="total_bid_hist"><?php if($total_records){?><?php echo $total_records;?><?php }else{ echo '0';}?></span>)</div> <!--This section relates to total number of bids placed by the user on this listing-->
                        </a>
                    </li>
                    <li>
                        <a href="#OtherBidHistory" data-toggle="tab">
                            <div class="thumb">
                                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/product/tab3.png" alt="product">
                            </div>
                            <div class="content"><?php echo lang("LUB_other_bidhistory_title");?> <?php if($total_records_other){?>(<?php echo $total_records_other;?>)<?php }?></div> <!-- The number should be of the total number of bids on this auction listing-->
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
                <div class="tab-pane fade show" id="MyBidHistory">
                    <div class="shipping-wrapper">
                        <div class="item">
                            <h5 class="title"><?php echo lang("LUB_home_Live_page_bidding_history_title");?></h5>
                            <Span><?php echo lang("label_refresh");?></Span>
                        </div>
                        <!-- <div class="item">
                            <h5 class="title">Notes</h5>
                            <p>Please carefully review our shipping and returns policy before committing to a bid.
                            From time to time, and at its sole discretion, Sbidu may change the prevailing fee structure for shipping and handling.</p>
                        </div> -->
                        <div class="dashboard-purchasing-tabs">
                            <ul class="nav-tabs nav">
                                
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane show active fade" id="current">
                                    <table class="purchasing-table">
                                        <thead style="font-size: 12px;">
                                            <th><?php echo lang("date_and_time");?></th>                                            
                                            <th><?php echo lang("label_credit_used");?></th>
                                            <th><?php echo lang("LUB_home_Live_page_bid_amount");?></th>
                                            <th><?php echo lang("label_rem_credit");?></th>
                                            <th><?php echo lang("account_bonus_points");?></th>
                                            <th><?php echo lang("won_status");?></th>
                                        </thead>
                                        <tbody id="bidHistory">
                        <?php if ($this->session->userdata(SESSION . 'user_id')) { ?>
                            <?php
                            $timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION . 'country_id'));
                            if ($user_bid_history) {
                                $class = '';
                                $status_type = '';

                                $current_winner_amt = $this->general->get_winner_amt($auc_data->product_id);

                                //echo $i = $per_page * $current_page + 1;
                                //print_r($user_bid_history);
                                foreach ($user_bid_history as $biddata) {
                                    //echo $biddata->userbid_bid_amt;
                                    $class = '';
                                    if ($biddata->freq > 1) {
                                        $status_type = lang('general_user_bid_status_nu');
                                        $class = 'class="color_red"';
                                    } else if ($biddata->freq == 1 && $current_winner_amt == $biddata->userbid_bid_amt) {
                                        $status_type = lang('general_user_bid_status_lub'); //Lowest Unique Bid
                                        $class = 'class="active color_green"';
                                    } else if ($biddata->freq == 1) {
                                        $status_type = lang('general_user_bid_status_uth');
                                    }

                                    if ($biddata->user_id == $this->session->userdata(SESSION . 'user_id'))
                                        $bid_amount = $this->general->formate_price_currency_sign($biddata->userbid_bid_amt);
                                    else
                                        $bid_amount = '****';
                                    ?>
                                    <tr <?php echo ($class != '') ? $class : ''; ?>>
                                        <td><?php echo $this->general->convert_local_time($biddata->bid_date, $timeZone); ?></td>
                                        <td><?php echo $biddata->click_cost; ?></td>
                                        <td><?php echo $bid_amount; ?></td>
                                        <td><?php echo $biddata->remaining_bids; ?></td>
                                        <td><?php echo $biddata->remaining_bonus; ?></td>
                                        <td><?php echo $status_type; ?></td>
                                    </tr>
                                <?php }?>
                                
                                    <?php
                                
                            }else {
                                ?>
                                <tr>
                                    <td class="text-center" colspan="6"><?php echo lang('LUB_home_Live_page_bid_not_found'); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                <br>
								<p><?php echo lang('please_login_to'); ?> </p>
                                        <h5><a href="<?php echo $this->general->lang_uri('/users/login'); ?>"><strong><?php echo lang('click_here_to_log_in') ?></strong></a></h5></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                                    </table>
                                    <nav class="pull-right pagination_sec" id="pagination">
									<?php
                                    if (isset($pagination_link)) {
                                        echo $pagination_link;
                                    }
                                    ?>
                                </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="OtherBidHistory">
                    <div class="history-wrapper">
                        <div class="item">
                            <h5 class="title"><?php echo lang("label_other_bidding_history");?></h5>
                            <?php echo lang("label_recent_10bidder");?>
                            <div class="history-table-area">
                                <table class="history-table">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang("label_bidder");?></th>
                                            <th><?php echo lang("date");?></th>
                                            <th><?php echo lang("time");?></th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody id="otherUsersBidHistory">
                                    <?php 
											if ($this->session->userdata(SESSION . 'user_id')) { 
												if($other_bid_history){
													$timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION . 'country_id'));
													foreach($other_bid_history as $other_bid){
														
									?>
									
                                        <tr>
                                            <td data-history="bidder">
                                                <div class="user-info">
                                                    <div class="thumb">
                                                        <?php /*?><img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/history/01.png" alt="history"><?php */?>
                                                    </div>
                                                    <div class="content">
                                                        <?php echo $other_bid->user_name;?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-history="date"><?php echo $this->general->convert_local_time($other_bid->bid_date, $timeZone,'date'); ?></td>
                                            <td data-history="time"><?php echo $this->general->convert_local_time($other_bid->bid_date, $timeZone,'time'); ?></td>
                                            
                                        </tr>
                                        <?php }?>
                                        
                                
                                        <?php }else{?>
                                     <tr>
                                    <td  class="text-center" colspan="3"><?php echo lang('LUB_home_Live_page_bid_not_found'); ?></td>
                                </tr>   
                                        <?php }?>
                                         <?php
												
                        } else {
                            ?>
                            <tr>
                                <td colspan="3" class="text-center">
                                <br>
								<p><?php echo lang('please_login_to'); ?> </p>
                                        <h5><a href="<?php echo $this->general->lang_uri('/users/login'); ?>"><strong><?php echo lang('click_here_to_log_in') ?></strong></a></h5></td>
                            </tr>
                        <?php } ?>   
                                    </tbody>
                                </table>
                                <nav class="pull-right pagination_sec" id="pagination_other">
									<?php
                                    if (isset($pagination_link_other)) {
                                        echo $pagination_link_other;
                                    }
                                    ?>
                                </nav>
                                <!--<div class="text-center mb-3 mt-4">
                                    <a href="#0" class="button-3">Load More</a>
                                </div>-->
                            </div>
                        </div>
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