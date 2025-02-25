<!--============= Hero Section Starts Here =============-->
    <div class="hero-section style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>">Home</a>
                </li>
                
                <li>
                    <span><?php $srch = $this->input->get('srch'); if($srch){ echo 'Search';}else { echo 'Live Auctions';}?></span>
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
                            <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($featured->name) . '-' . $featured->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $featured->image1); ?>" alt="<?php echo $featured->name; ?>"></a>
                            <a href="javascript:void(0);" data-productid="<?php echo $featured->product_id; ?>" class="wthlst rating  <?php echo $watchclass; ?>"><i class="far fa-star with_<?php echo $featured->product_id; ?> <?php echo $watchclass; ?>"></i></a>
                            <?php if($featured->is_buy_now == "Yes"){?>
                            <a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
                            <?php }?>
                        </div>
                        <div class="auction-content">
                            <h6 class="title">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($featured->name) . '-' . $featured->product_id); ?>"><?php echo character_limiter($featured->name, 20); ?></a>
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
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($featured->name). '-' . $featured->product_id); ?>" class="custom-button"><?php echo lang('bid_now'); ?></a>
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
                    <h2 class="title mb-3"><?php $srch = $this->input->get('srch'); if($srch){ echo 'Search By <small>'.$srch.'</small>';}else { echo 'All Live Auctions';}?></h2>
                    
                </div>
                
            </div>
            <?php /*?><div class="product-header mb-40">
                <div class="product-header-item">
                    <div class="item">Sort By : </div>
                    <select name="sort-by" class="select-bar">
                        <option value="all">All</option>
                        <option value="name">Name</option>
                        <option value="date">Date</option>
                        <option value="type">Type</option>
                        <option value="car">Car</option>
                    </select>
                </div>
                <div class="product-header-item">
                    <div class="item">Show : </div>
                    <select name="sort-by" class="select-bar">
                        <option value="09">09</option>
                        <option value="21">21</option>
                        <option value="30">30</option>
                        <option value="39">39</option>
                        <option value="60">60</option>
                    </select>
                </div>
                <form class="product-search ml-auto">
                    <input type="text" placeholder="Item Name">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div><?php */?>
            <?php if($live_auctions){?>
            <div class="row mb-30-none">
                <?php 
					
						foreach($live_auctions as $live){
							$show_time = strtotime($live->end_date) - strtotime($current_date_time);
							$watch = false;
							if($user_watchlist)
								$watch = array_search($live->product_id, array_column($user_watchlist, 'auction_id'));;//$this->general->get_watchlist_check($this->session->userdata(SESSION . 'user_id'), $live->product_id);
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
                            <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($live->name) . '-' . $live->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $live->image1); ?>" alt="<?php echo $live->name; ?>"></a>
                            <a href="javascript:void(0);" data-productid="<?php echo $live->product_id; ?>" class="wthlst rating  <?php echo $watchclass; ?>"><i class="far fa-star with_<?php echo $live->product_id; ?> <?php echo $watchclass; ?>"></i></a>
                            <?php if($live->is_buy_now == "Yes"){?>
                            <a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
                            <?php }?>
                        </div>
                        <div class="auction-content">
                            <h6 class="title">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($live->name) . '-' . $live->product_id); ?>"><?php echo character_limiter($live->name, 20); ?></a>
                            </h6>
                            
                            <div class="bid-area">
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-auction"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current">Total Bids</div>
                                        <div class="amount"><?php echo $live->total_bids;?></div>
                                    </div>
                                </div>
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-money"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current"><?php echo lang('bid_fee'); ?></div>
                                        <div class="amount"><?php echo $live->bid_fee; ?> <?php echo lang('credits'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="countdown-area">
                                <div class="countdown">
                                    <div id="Ltimer_<?php echo $live->id; ?>"><script>get_timer('Ltimer_<?php echo $live->id; ?>', '<?php echo $live->id; ?>', '<?php echo $show_time; ?>', 1, '');</script></div>
                                </div>
                                <span class="total-bids"><?php
                                        if ($live->price == 0)
                                            echo "<span class='priceless'>" . lang('priceless') . '</span>';
                                        else
                                            echo $this->general->formate_price_currency_sign($live->price, '<span>', '</span>');
                                        ?></span>
                            </div>
                            <div class="text-center">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($live->name). '-' . $live->product_id); ?>" class="custom-button"><?php echo lang('bid_now'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
				<?php }?>
                
            </div>
            <?php if($pagination_links){ echo $pagination_links; } ?>
            <?php }?>
        </div>
    </div>
    <!--============= Product Auction Section Ends Here =============-->
    
<?php /*?><div class="col-md-9 col-sm-8 content_sec">
              <div class="sec_title relative"><div class="skew_bg"></div><?php echo lang('live_auctions'); ?> </div>
              <div class="row">
               <?php
			   		$current_date_time = $this->general->get_local_time('time'); 
			   			if($auctions)
						{
              // print_r($auctions);
              // exit;
							foreach($auctions as $auc_data)
							{
								$show_time = strtotime($auc_data->end_date)-strtotime($current_date_time); 
                 $watch=  $this->general->get_watchlist_check($this->session->userdata(SESSION.'user_id'),$auc_data->product_id);

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
                <div class="col-md-4 col-sm-6 col-xs-6">
                  <div class="product_container">
                    <figure>
                      <div class="overlay"></div>
                      <div class="overlay_btn"><a href="javascript:void(0)" class="wthlst" data-productid='<?php echo $auc_data->product_id;?>'  ><i class="fa fa-heart with_<?php echo $auc_data->product_id; ?> <?php echo $watchclass; ?>"></i></a></div>
                      <img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.'thumb_'.$auc_data->image1);?>" alt="<?php echo $auc_data->name;?>"> </figure>
                    <div class="timer">
                    <div class="timer text-center" id="timer_<?php echo $auc_data->id; ?>">
                        <script>
							get_timer('timer_<?php echo $auc_data->id; ?>','<?php echo $auc_data->id; ?>', '<?php echo $show_time; ?>',1,'');
                        </script>
                   
                    </div>
                    </div>
                    <div class="content">
                      <ul>
                        <li class="content_ttl"><a href="<?php echo $this->general->lang_uri('/auctions/'.$this->general->clean_url($auc_data->name).'-'.$auc_data->product_id);?>"><?php echo character_limiter($auc_data->name,20);?></a></li>
                        <li><?php echo lang('bid_fee'); ?>: <span><?php echo $auc_data->bid_fee;?> <?php echo lang('credits'); ?></span></li>
                       <!--  <li><?php //echo lang('retail_price'); ?>: <?php //echo $this->general->formate_price_currency_sign($auc_data->price,'<span>','</span>');?></li>
                        <li> -->
                         <li><?php echo lang('retail_price'); ?>: <span><?php if($auc_data->price==0)echo "<span class='priceless'>".lang('priceless').'</span>';else echo $this->general->formate_price_currency_sign($auc_data->price, '<span>', '</span>'); ?></span>
                         </li>
                               




                          <a href="<?php echo $this->general->lang_uri('/auctions/'.$this->general->clean_url($auc_data->name).'-'.$auc_data->product_id);?>" class="btn_bid btn-block text-center"><?php echo lang('bid_now'); ?></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
               <?php }}?>
               
               
              
              </div>
                <?php if($pagination_links){ echo $pagination_links; } ?>
              <div class="pad_15"></div>
            </div><?php */?>