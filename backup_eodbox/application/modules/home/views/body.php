<!--============= Banner Section Starts Here =============-->
    <section class="banner-section bg_img" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>banner/banner-bg-1.png">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-6 col-xl-6">
                    <div class="banner-content cl-white">
                        <h5 class="cate"><?php echo lang('label_next_g_auc');?></h5>
                        <h1 class="title"><?php echo lang('label_next_deal');?></h1>
                        <p>
                            <?php echo lang('label_next_deal_desc');?>
                        </p>
                        <a href="<?php echo $this->general->lang_uri('/auctions/live'); ?>" class="custom-button yellow btn-large"><?php echo lang('label_get_start');?></a>
                    </div>
                </div>
                <div class="d-none d-lg-block col-lg-6">
                    <div class="banner-thumb-2">
                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>banner/banner-1.png" alt="banner">
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-shape d-none d-lg-block">
            <img src="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH);?>img/banner-shape.png" alt="css">
        </div> 
    </section>
    <!--============= Banner Section Ends Here =============-->

   
    <div class="browse-section ash-bg">
        <!--============= Hightlight Slider Section Starts Here =============-->
        <?php if($category){?>
        <div class="browse-slider-section mt--140">
            <div class="container">
                <div class="section-header-2 cl-white mb-4">
                    <div class="left">
                        <h6 class="title pl-0 w-100"><?php echo lang('label_browse_cat');?></h6>
                    </div>
                    <?php if(count($category)>6){?>
                    <div class="slider-nav">
                        <a href="#0" class="bro-prev"><i class="flaticon-left-arrow"></i></a>
                        <a href="#0" class="bro-next active"><i class="flaticon-right-arrow"></i></a>
                    </div>
                    <?php }?>
                </div>
                <div class="m--15">
                    <div class="browse-slider owl-theme owl-carousel">
                    	<?php foreach($category as $cat){?>
                        <a href="<?php echo $this->general->lang_uri('/category/' . $this->general->clean_url($cat->name) . '-' . $cat->parent_id); ?>" class="browse-item">
                            <img src="<?php echo site_url(PRODUCT_CATEGORY_PATH);?><?php echo $cat->image; ?>" alt="auction">
                            <span class="info"><?php echo $cat->name; ?></span>
                        </a>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
        <!--============= Hightlight Slider Section Ends Here =============-->
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        
    <!--============= Featured Section Starts Here =============-->
    <?php $current_date_time = $this->general->get_local_time('time'); if($featured_auctions){?>
    <section class="car-auction-section padding-bottom pos-rel oh">
        <div class="car-bg"><img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>auction/car/car-bg.png" alt="car"></div>
        <div class="container">
            <div class="section-header-3">
                <div class="left d-block">
                    <h2 class="title mb-3"><?php echo lang('label_featured_items');?></h2>
                    <p><?php echo lang('label_featured_items_slug');?></p>
                </div>
                <!-- <a href="#0" class="normal-button">View All</a> -->
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
                            <p class="shortDescription"><?php echo $featured->meta_desc; ?></p>
                            <div class="bid-area">
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-auction"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current"><?php echo lang('label_total_bids');?></div>
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
    <!--============= Trending Section Starts Here =============-->
    <?php if($trending_auctions){?>
    <section class="trending-section padding-bottom padding-top">
        <div class="container">
            <div class="section-header-3">
                <div class="left d-block">
                    <h2 class="title mb-3"><?php echo lang('label_trending_items');?></h2>
                    <p><?php echo lang('label_trending_items_desc');?></p>
                </div>
                <!-- <a href="#0" class="normal-button">View All</a> -->
            </div>
            <div class="row justify-content-center mb-30-none">
            	<?php 
						foreach($trending_auctions as $trending){
							$show_time = strtotime($trending->end_date) - strtotime($current_date_time);
							$watch = false;
							if($user_watchlist)
							$watch = array_search($trending->product_id, array_column($user_watchlist, 'auction_id'));
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
                            <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($trending->name) . '-' . $trending->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $trending->image1); ?>" alt="<?php echo $trending->name; ?>"></a>
                            <a href="javascript:void(0);" data-productid="<?php echo $trending->product_id; ?>" class="wthlst rating  <?php echo $watchclass; ?>"><i class="far fa-star with_<?php echo $trending->product_id; ?> <?php echo $watchclass; ?>"></i></a>
                            <?php if($trending->is_buy_now == "Yes"){?>
                            <a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
                            <?php }?>
                        </div>
                        <div class="auction-content">
                            <h6 class="title">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($trending->name) . '-' . $trending->product_id); ?>"><?php echo character_limiter($trending->name, 20); ?></a>
                            </h6>
                            <p class="shortDescription"><?php echo $trending->meta_desc; ?></p>
                            <div class="bid-area">
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-auction"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current"><?php echo lang('label_total_bids');?></div>
                                        <div class="amount"><?php echo $trending->total_bids;?></div>
                                    </div>
                                </div>
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-money"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current"><?php echo lang('bid_fee'); ?></div>
                                        <div class="amount"><?php echo $trending->bid_fee; ?> <?php echo lang('credits'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="countdown-area">
                                <div class="countdown">
                                    <div id="Ttimer_<?php echo $trending->id; ?>"><script>get_timer('Ttimer_<?php echo $trending->id; ?>', '<?php echo $trending->id; ?>', '<?php echo $show_time; ?>', 1, '');</script></div>
                                </div>
                                <span class="total-bids"><?php
                                        if ($trending->price == 0)
                                            echo "<span class='priceless'>" . lang('priceless') . '</span>';
                                        else
                                            echo $this->general->formate_price_currency_sign($trending->price, '<span>', '</span>');
                                        ?></span>
                            </div>
                            <div class="text-center">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($trending->name). '-' . $trending->product_id); ?>" class="custom-button"><?php echo lang('bid_now'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
				<?php }?>
                
                
            </div>
        </div>
    </section>
    <?php }?>
    <!--============= Trending Section Ends Here =============-->
        
    <!--============= Ending Auction Section Starts Here =============-->
    <?php if($ending_soon_auctions){?>
    <section class="ending-auction padding-top pos-rel">
        <div class="popular-bg bg_img" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>auction/popular/popular-bg.png"></div>
        <div class="container">
            <div class="section-header cl-white">
                <span class="cate"><?php echo lang('label_closing_within_text');?></span>
                <h2 class="title">Auctions Ending soon</h2>
                <p>Bid and win great deals, Our auction process is simple, efficient, and transparent.</p>
            </div>
            <div class="popular-auction-wrapper">
                <div class="row mb-40-none">
                	<?php foreach($ending_soon_auctions as $ending_soon){?>
                    <div class="col-lg-6">
                        <div class="auction-item-3">
                            <div class="auction-thumb">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($ending_soon->name) . '-' . $ending_soon->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $ending_soon->image1); ?>" alt="<?php echo $ending_soon->name; ?>"></a>
                                <?php if($ending_soon->is_buy_now == "Yes"){?>
                                        <a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
                                        <?php }?>
                            </div>
                            <div class="auction-content">
                                <h6 class="title">
                                    <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($ending_soon->name) . '-' . $ending_soon->product_id); ?>"><?php echo character_limiter($ending_soon->name, 50); ?></a>
                                </h6>
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-auction"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current"><?php echo lang('label_total_bids');?></div>
                                        <div class="amount"><?php echo $ending_soon->total_bids;?></div>
                                    </div>
                                </div>
                                <div class="bids-area">
                                    MRP : <span class="total-bids"><?php
                                        if ($ending_soon->price == 0)
                                            echo "<span class='priceless'>" . lang('priceless') . '</span>';
                                        else
                                            echo $this->general->formate_price_currency_sign($ending_soon->price, '<span>', '</span>');
                                        ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <?php /*?><div class="load-wrapper">
                    <a href="#0" class="normal-button">See All Auction</a>
                </div><?php */?>
            </div>
        </div>
    </section>
    <?php }?>
    <!--============= Ending Auction Section Ends Here =============-->

       <!--============= Upcoming Auction Section Starts Here =============-->
       <?php if($get_all_auctions){?>
       <section class="upcoming-auction padding-bottom padding-top">
        <div class="container">
            <div class="section-header">
                <h2 class="title">Upcoming Auctions</h2>
                <p>You are welcome to attend and join in the action at any of our upcoming auctions.</p>
            </div>
        </div>
        <div class="filter-wrapper no-shadow">
            <div class="container">
                <ul class="filter niche-border">
                    <li class="active" data-check="*">
                        <span><i class="flaticon-right-arrow"></i>All</span>
                    </li>
                    <?php /*?><li data-check=".live">
                        <span><i class="flaticon-auction"></i>Live Auction</span>
                    </li>
                    <li data-check=".time">
                        <span><i class="flaticon-time"></i>Time Auction</span>
                    </li><?php */?>
                    <li data-check=".buy">
                        <span><i class="flaticon-bag"></i>buy now</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="auction-wrapper-7 m--15">
            	<?php 
						
						foreach($get_all_auctions as $auc){
							$auc_data = $auc[0];
							
							$is_buy = "";
							if($auc_data->is_buy_now == "Yes")
								$is_buy = "buy";
							
							$upcomming = "";
							if(strtotime($auc_data->start_date) > strtotime($current_date_time))
								$upcomming = "time";
							
							$watch = false;
							if($user_watchlist)
							$watch = array_search($auc_data->product_id, array_column($user_watchlist, 'auction_id'));;//$this->general->get_watchlist_check($this->session->userdata(SESSION . 'user_id'), $featured->product_id);
							$watchclass = '';
							if ($this->session->userdata(SESSION . 'user_id')) {
								if ($watch != false) {
									$watchclass = 'wthselect';
								} else {
									$watchclass = '';
								}
							}
							if($upcomming == "time")
							$item_url = $this->general->lang_uri('/upcomming/'.$this->general->clean_url($auc_data->name).'-'.$auc_data->product_id);
							else
							$item_url = $this->general->lang_uri('/auctions/' .$this->general->clean_url($auc_data->name).'-'.$auc_data->product_id);
				?>
                <div class="auction-item-7 live <?php echo $is_buy;?> <?php echo $upcomming;?>">
                    <div class="auction-inner">
                        <a href="#0" class="upcoming-badge-2" title="Upcoming Auction">
                            <?php if($auc_data->is_buy_now == "Yes"){?>
                            <i class="flaticon-shopping-basket"></i>
                            <?php }else{?>
                            <i class="flaticon-auction"></i>
                            <?php }?>
                        </a>
                        
                        <div class="auction-thumb bg_img" data-background="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image1); ?>">
                            <img class="d-lg-none" src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $auc_data->image1); ?>" alt="<?php echo $auc_data->name; ?>">
                            <a href="javascript:void(0);" data-productid="<?php echo $auc_data->product_id; ?>" class="wthlst rating  <?php echo $watchclass; ?>"><i class="far fa-star with_<?php echo $auc_data->product_id; ?> <?php echo $watchclass; ?>"></i></a>
                            
                        </div>
                        <div class="auction-content">
                            <div class="title-area">
                                <h6 class="title">
                                    <a href="<?php echo $item_url; ?>"><?php echo character_limiter($auc_data->name, 100); ?></a>
                                </h6>
                                <p class="shortDescription"><?php echo $auc_data->meta_desc; ?></p>
                            </div>
                            <div class="bid-area">
                                <div class="bid-inner">
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
                                        	<?php if($auc_data->is_buy_now=="Yes"){?>
                                            <div class="current">Buy Now</div>
                                            <div class="amount">
                                            
                                            <?php echo $this->general->formate_price_currency_sign($auc_data->buy_now_price);?>
                                            </div>
                                            <?php }else{?>
                                            <div class="current">MRP</div>
                                            <div class="amount">
                                            
                                            <?php echo $this->general->formate_price_currency_sign($auc_data->price);?>
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="auction-bidding">
                        	<?php if($upcomming == "time"){?>
                            <a href="<?php echo $item_url; ?>" class="custom-button"><?=$this->general->get_days($auc_data->start_date)?></a>
                            <?php }else{?>
                            <a href="<?php echo $item_url; ?>" class="custom-button">Bid Now</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php }?>
                
            </div>
        </div>
    </section>
    <?php }?>
    <!--============= Upcoming Auction Section Ends Here =============-->

    <!--============= Category Auction Section Starts Here =============-->
    <?php 
			
			if($home_cat_auctions){ 
				foreach($home_cat_auctions as $category_data){
					
	?>
    <section class="trending-section padding-bottom padding-top">
        <div class="container">
            <div class="section-header-3">
                <div class="left d-block">
                    <h2 class="title mb-3"><?php echo $category_data['cat_name'];?></h2>
                    <p><?php echo $category_data['cat_desc'];?></p>
                </div>
                <a href="<?php echo $this->general->lang_uri('/category/' . $this->general->clean_url($category_data['cat_name']) . '-' . $category_data['cat_parent_id']); ?>" class="normal-button">View All</a>
            </div>
            <div class="row mb-30-none">
                
                <?php 
						foreach($category_data['auction'] as $catAuc){
							$show_time = strtotime($catAuc->end_date) - strtotime($current_date_time);
							
							$watch = false;
							if($user_watchlist)
							$watch = array_search($catAuc->product_id, array_column($user_watchlist, 'auction_id'));
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
                            <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($catAuc->name) . '-' . $catAuc->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $catAuc->image1); ?>" alt="<?php echo $catAuc->name; ?>"></a>
                            <a href="javascript:void(0);" data-productid="<?php echo $catAuc->product_id; ?>" class="wthlst rating  <?php echo $watchclass; ?>"><i class="far fa-star with_<?php echo $catAuc->product_id; ?> <?php echo $watchclass; ?>"></i></a>
                            <?php if($catAuc->is_buy_now == "Yes"){?>
                            <a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
                            <?php }?>
                        </div>
                        <div class="auction-content">
                            <h6 class="title">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($catAuc->name) . '-' . $catAuc->product_id); ?>"><?php echo character_limiter($catAuc->name, 20); ?></a>
                            </h6>
                            <p class="shortDescription"><?php echo $catAuc->meta_desc; ?></p>
                            <div class="bid-area">
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-auction"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current">Total Bids</div>
                                        <div class="amount"><?php echo $catAuc->total_bids;?></div>
                                    </div>
                                </div>
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-money"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current"><?php echo lang('bid_fee'); ?></div>
                                        <div class="amount"><?php echo $catAuc->bid_fee; ?> <?php echo lang('credits'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="countdown-area">
                                <div class="countdown">
                                    <div id="CAtimer_<?php echo $catAuc->id; ?>"><script>get_timer('CAtimer_<?php echo $catAuc->id; ?>', '<?php echo $catAuc->id; ?>', '<?php echo $show_time; ?>', 1, '');</script></div>
                                </div>
                                <span class="total-bids"><?php
                                        if ($catAuc->price == 0)
                                            echo "<span class='priceless'>" . lang('priceless') . '</span>';
                                        else
                                            echo $this->general->formate_price_currency_sign($catAuc->price, '<span>', '</span>');
                                        ?></span>
                            </div>
                            <div class="text-center">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($catAuc->name). '-' . $catAuc->product_id); ?>" class="custom-button"><?php echo lang('bid_now'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
				<?php }?>
                
            </div>
        </div>
    </section>
    <?php }}?>
    <!--============= Category Auction Section Ends Here =============-->
       
 

    <!--============= Popular Auction Section Starts Here =============-->
    <?php if($popular_auctions){?>
    <section class="popular-auction padding-top pos-rel">
        <div class="popular-bg bg_img" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>auction/popular/popular-bg.png"></div>
        <div class="container">
            <div class="section-header cl-white">
                <span class="cate">Closing Within 24 Hours</span>
                <h2 class="title">Popular Auctions</h2>
                <p>Bid and win great deals,Our auction process is simple, efficient, and transparent.</p>
            </div>
            <div class="popular-auction-wrapper">
                <div class="row mb-30-none">
                    <?php foreach($popular_auctions as $popular){?>
                    <div class="col-lg-6">
                        <div class="auction-item-3">
                            <div class="auction-thumb">
                                <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($popular->name) . '-' . $popular->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $popular->image1); ?>" alt="<?php echo $popular->name; ?>"></a>
                                <?php if($popular->is_buy_now == "Yes"){?>
                                        <a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
                                        <?php }?>
                            </div>
                            <div class="auction-content">
                                <h6 class="title">
                                    <a href="<?php echo $this->general->lang_uri('/auctions/' . $this->general->clean_url($popular->name) . '-' . $popular->product_id); ?>"><?php echo character_limiter($popular->name, 50); ?></a>
                                </h6>
                                <div class="bid-amount">
                                    <div class="icon">
                                        <i class="flaticon-auction"></i>
                                    </div>
                                    <div class="amount-content">
                                        <div class="current">Total Bids</div>
                                        <div class="amount"><?php echo $popular->total_bids;?></div>
                                    </div>
                                </div>
                                <div class="bids-area">
                                    MRP : <span class="total-bids"><?php
                                        if ($popular->price == 0)
                                            echo "<span class='priceless'>" . lang('priceless') . '</span>';
                                        else
                                            echo $this->general->formate_price_currency_sign($popular->price, '<span>', '</span>');
                                        ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </section>
    <?php }?>
    <!--============= Popular Auction Section Ends Here =============-->


 


    <!--============= How Section Starts Here =============-->
    <section class="how-section padding-top">
        <div class="container">
            <div class="how-wrapper section-bg">
                <div class="section-header text-lg-left">
                    <h2 class="title">How it works</h2>
                    <p>Easy 3 steps to win</p>
                </div>
                <div class="row justify-content-center mb--40">
                    <div class="col-md-6 col-lg-4">
                        <div class="how-item">
                            <div class="how-thumb">
                                <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>how/how1.png" alt="how">
                            </div>
                            <div class="how-content">
                                <h4 class="title">Sign Up</h4>
                                <p>No Credit Card Required</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="how-item">
                            <div class="how-thumb">
                                <img src="assets/images/how/how2.png" alt="how">
                            </div>
                            <div class="how-content">
                                <h4 class="title">Bid</h4>
                                <p>Bidding is free Only pay if you win</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="how-item">
                            <div class="how-thumb">
                                <img src="assets/images/how/how3.png" alt="how">
                            </div>
                            <div class="how-content">
                                <h4 class="title">Win</h4>
                                <p>Fun - Excitement - Great deals</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= How Section Ends Here =============-->