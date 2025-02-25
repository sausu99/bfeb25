<div class="dash-bid-item dashboard-widget mb-40-60">
                        <div class="header">
                            <h4 class="title">My Bids Statement</h4>
                            
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show" style="display:none;" id="errorM">
                            
                            <p id="error_message"></p>
                        </div>
                        <form name="frmBidHistoryGenerate" id="frmBidHistoryGenerate" method="get" action="<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/bid_statement')?>">
                        <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="firstName">Date From</label>
                  <input type="date" class="form-control" name="date_from" id="date_from" placeholder="" value="" required="">
                  
                </div>
                <div class="col-md-4 mb-3">
                  <label for="lastName">Date To</label>
                  <input type="date" class="form-control" name="date_to" id="date_to" placeholder="" value="" required="">
                  
                </div>
                <div class="col-md-4 mb-3">
                  <label for="lastName">&nbsp;</label>
                  <ul class="button-area nav nav-tabs">
                                    <li>
                                        <input type="button" id="generate" name="submit" value="Generate" onclick="submitDetailsForm()" class="custom-button active"  />
                                    </li>
                                </ul>
                  
                </div>
              </div>
              </form>
                        
                    </div>
                    
                    
                    
                					
                    <div class="dash-bid-item dashboard-widget mb-40-60">
                        
                            <h4 class="title">My Bids</h4>
                            
                     
                    </div>
                    
<div class="tab-content">
                        <div class="tab-pane fade show active" id="upcoming">
                            <div class="row mb-30-none">
                            	<?php $current_date_time = $this->general->get_local_time('time'); if($ongoing_auc){?>
                                <?php 
									foreach($ongoing_auc as $featured){
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
                    
<script language="javascript" type="text/javascript">
    
	function MonthDifference(startDay, endDay) {
            var days;
           		
			var millisecondsPerDay = 1000 * 60 * 60 * 24;
     		var millisBetween = endDay.getTime() - startDay.getTime();
     		var days = millisBetween / millisecondsPerDay;
	 		return days <= 0 ? 0 : days;
        }
        function submitDetailsForm() {
			var date_from = $("#date_from").val();
			var date_to = $("#date_to").val();
			
            startDay = new Date(date_from);
            endDay = new Date(date_to);
			difference = MonthDifference(startDay, endDay);
			if(isNaN(difference)) {
				$("#errorM").show();
				$("#error_message").html('Please enter valide from & to date.');
			}
			else if(difference > 365){
				$("#errorM").show();
				$("#error_message").html('Valid bid statement date range is upto 12 month.');
			}
			else{
				$("#errorM").hide();
				window.location.href = "<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/bid_statement')?>?date_from="+date_from+'&date_to='+date_to;
			}
            //alert("The difference between two dates is: " + MonthDifference(startDay, endDay));
        }
</script>
