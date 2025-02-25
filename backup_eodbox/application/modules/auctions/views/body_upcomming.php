<!--============= Hero Section Starts Here =============-->
    <div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>">Home</a>
                </li>
                
                <li>
                    <span>Upcomming Auctions</span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>banner/hero-bg.png"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->
	
    <!--============= Featured Auction Section Starts Here =============-->
    <?php $current_date_time = $this->general->get_local_time('time'); if($featured_auctions){?>
    <section class="featured-auction-section padding-bottom mt--240 mt-lg--440 pos-rel">
        <div class="container">
            <div class="section-header cl-white mw-100 left-style">
                <h3 class="title">Bid on These Featured Auctions!</h3>
            </div>
            <div class="row justify-content-center mb-30-none">
                
                
                <?php 
						foreach($featured_auctions as $featured){
							$show_time = strtotime($featured->end_date) - strtotime($current_date_time);
							$watch = false;
							if($user_watchlist)
								$watch = array_search($featured->product_id, array_column($user_watchlist, 'auction_id'));;//$this->general->get_watchlist_check($this->session->userdata(SESSION . 'user_id'), $featured->product_id);
							$watchclass = '';
							if ($this->session->userdata(SESSION . 'user_id')) {
								if ($watch != false) {
									$watchclass = 'wthselect';
								} else {
									$watchclass = '';
								}
							}
				?>
                <div class="col-sm-10 col-md-6 col-lg-4">
                    <div class="auction-item-2">
                        <div class="auction-thumb">
                            <a href="<?php echo $this->general->lang_uri('/upcomming/' . $this->general->clean_url($featured->name) . '-' . $featured->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $featured->image1); ?>" alt="<?php echo $featured->name; ?>"></a>
                            <a href="javascript:void(0);" data-productid="<?php echo $featured->product_id; ?>" class="wthlst rating  <?php echo $watchclass; ?>"><i class="far fa-star with_<?php echo $featured->product_id; ?> <?php echo $watchclass; ?>"></i></a>
                            <?php if($featured->is_buy_now == "Yes"){?>
                            <a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
                            <?php }?>
                        </div>
                        <div class="auction-content">
                            <h6 class="title">
                                <a href="<?php echo $this->general->lang_uri('/upcomming/' . $this->general->clean_url($featured->name) . '-' . $featured->product_id); ?>"><?php echo character_limiter($featured->name, 20); ?></a>
                            </h6>
                            
                            <div class="bid-area">
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-auction"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current">Total Bids</div>
                                        <div class="amount"><?php echo $featured->total_bids;?></div>
                                    </div>
                                </div>
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-money"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current"><?php echo lang('bid_fee'); ?></div>
                                        <div class="amount"><?php echo $featured->bid_fee; ?> <?php echo lang('credits'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="countdown-area">
                                <div class="countdown">
                                    <div id="Ftimer_<?php echo $featured->id; ?>"><script>get_timer('Ftimer_<?php echo $featured->id; ?>', '<?php echo $featured->id; ?>', '<?php echo $show_time; ?>', 1, '');</script></div>
                                </div>
                                <span class="total-bids"><?php
                                        if ($featured->price == 0)
                                            echo "<span class='priceless'>" . lang('priceless') . '</span>';
                                        else
                                            echo $this->general->formate_price_currency_sign($featured->price, '<span>', '</span>');
                                        ?></span>
                            </div>
                            <div class="text-center">
                                <a href="<?php echo $this->general->lang_uri('/upcomming/' . $this->general->clean_url($featured->name). '-' . $featured->product_id); ?>" class="custom-button"><?php echo lang('bid_now'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
				<?php }?>
            </div>
        </div>
    </section>
    <?php }?>
    <!--============= Featured Auction Section Ends Here =============-->
    
    
    <!--============= Product Auction Section Starts Here =============-->
    <div class="product-auction padding-bottom">
        <div class="container">
        <div class="section-header-3">
                <div class="left d-block">
                    <h2 class="title mb-3">Upcomming Auctions</h2>
                    
                </div>
                
            </div>
            <div class="row mb--50">
                
                <div class="col-lg-12 mb-50">
                   
                    <div class="row mb-30-none">
                        
                        <?php
			   			$current_date_time = $this->general->get_local_time('time'); 
			   			if($upcomming_auctions)
						{
              // print_r($auctions);
              // exit;
							foreach($upcomming_auctions as $auc_data)
							{
								$show_time = strtotime($auc_data->end_date)-strtotime($current_date_time); 
                				 $watch= $this->general->get_watchlist_check($this->session->userdata(SESSION.'user_id'),$auc_data->product_id);

                             $watchclass='';
                             if($this->session->userdata(SESSION.'user_id'))
                             {
                                if($watch>0)
                                {
                                    $watchclass='wthselect';
                                }
                                else
                                {
                                    $watchclass='';
                                }
                             }

				?>       
                
                <div class="col-sm-10 col-md-6 col-lg-4">
                    <div class="auction-item-2">
                        <div class="auction-thumb">
                            <a href="<?php echo $this->general->lang_uri('/upcomming/' . $this->general->clean_url($auc_data->name) . '-' . $auc_data->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image1); ?>" alt="<?php echo $auc_data->name; ?>"></a>
                            <a href="javascript:void(0);" data-productid="<?php echo $auc_data->product_id; ?>" class="wthlst rating  <?php echo $watchclass; ?>"><i class="far fa-star with_<?php echo $auc_data->product_id; ?> <?php echo $watchclass; ?>"></i></a>
                            <?php if($auc_data->is_buy_now == "Yes"){?>
                            <a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
                            <?php }?>
                        </div>
                        <div class="auction-content">
                            <h6 class="title">
                                <a href="<?php echo $this->general->lang_uri('/upcomming/' . $this->general->clean_url($auc_data->name) . '-' . $auc_data->product_id); ?>"><?php echo character_limiter($auc_data->name, 20); ?></a>
                            </h6>
                            <p class="shortDescription"><?php echo $auc_data->meta_desc; ?></p>
                            <div class="bid-area">
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-auction"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current">Total Bids</div>
                                        <div class="amount"><?php echo $auc_data->total_bids;?></div>
                                    </div>
                                </div>
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-money"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current"><?php echo lang('bid_fee'); ?></div>
                                        <div class="amount"><?php echo $auc_data->bid_fee; ?> <?php echo lang('credits'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="countdown-area">
                                <div class="countdown">
                                    <div><?=$this->general->get_days($auc_data->start_date)?></div>
                                </div>
                                <span class="total-bids"><?php
                                        if ($auc_data->price == 0)
                                            echo "<span class='priceless'>" . lang('priceless') . '</span>';
                                        else
                                            echo $this->general->formate_price_currency_sign($auc_data->price, '<span>', '</span>');
                                        ?></span>
                            </div>
                            <div class="text-center">
                                <a href="<?php echo $this->general->lang_uri('/upcomming/' . $this->general->clean_url($auc_data->name). '-' . $auc_data->product_id); ?>" class="custom-button">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
               <?php }}else{?>
                        <p>:: No record found ::</p>
                        <?php }?>
                    </div>
                    <?php if($pagination_links){ echo $pagination_links; } ?>
                </div>
            </div>
        </div>
    </div>
    <!--============= Product Auction Section Ends Here =============-->