<div class="dash-bid-item dashboard-widget mb-40-60">
                        
                            <h4 class="title"><?php echo lang('favourite_auctions'); ?></h4>
                            
                     
                    </div>
                    
<div class="tab-content">
                        <div class="tab-pane fade show active" id="upcoming">
                            <div class="row mb-30-none">
                            	<?php $current_date_time = $this->general->get_local_time('time'); if($watchlist){?>
                                <?php 
									foreach($watchlist as $featured){
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
							<div class="col-sm-10 col-md-6">
								<div class="auction-item-2">
									<div class="auction-thumb">
										<a href="<?php echo $this->general->lang_uri('/auctions/' . str_replace(' ','-',$featured->name) . '-' . $featured->product_id); ?>"><img src="<?php echo base_url(AUCTION_IMG_PATH . 'thumb_' . $featured->image1); ?>" alt="<?php echo $featured->name; ?>"></a>
										<a href="javascript:void(0);" data-productid="<?php echo $featured->product_id; ?>" class="rating  <?php echo $watchclass; ?>"><i class="far fa-star with_<?php echo $featured->product_id; ?> <?php echo $watchclass; ?>"></i></a>
										<?php if($featured->is_buy_now == "Yes"){?>
										<a href="#0" class="bid"><i class="flaticon-shopping-basket"></i></a>
										<?php }?>
									</div>
									<div class="auction-content">
										<h6 class="title">
											<a href="<?php echo $this->general->lang_uri('/auctions/' . str_replace(' ','-',$featured->name) . '-' . $featured->product_id); ?>"><?php echo character_limiter($featured->name, 20); ?></a>
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
											<a href="<?php echo $this->general->lang_uri('/auctions/' . str_replace(' ','-',$featured->name). '-' . $featured->product_id); ?>" class="custom-button"><?php echo lang('bid_now'); ?></a>
										</div>
									</div>
								</div>
							</div>
							<?php }?>
                                 <?php }?>
                            </div>
                        </div>
                        
                    </div>
