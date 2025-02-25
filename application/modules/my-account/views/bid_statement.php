<div class="dash-bid-item dashboard-widget mb-40-60">
                        <div class="header">
                            <h4 class="title"><?php echo lang('label_my_bids_statement');?></h4>
                            
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show" style="display:none;" id="errorM">
                            
                            <p id="error_message"></p>
                        </div>
                        <form name="frmBidHistoryGenerate" id="frmBidHistoryGenerate" method="get" action="<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/bid_statement')?>">
                        <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="firstName"><?php echo lang('label_dt_from');?></label>
                  <input type="date" class="form-control" name="date_from" id="date_from" placeholder="" value="" required="">
                  
                </div>
                <div class="col-md-4 mb-3">
                  <label for="lastName"><?php echo lang('label_dt_to');?></label>
                  <input type="date" class="form-control" name="date_to" id="date_to" placeholder="" value="" required="">
                  
                </div>
                <div class="col-md-4 mb-3">
                  <label for="lastName">&nbsp;</label>
                  <ul class="button-area nav nav-tabs">
                                    <li>
                                        <input type="button" id="generate" name="submit" value="<?php echo lang('register_bttn_submit');?>" onclick="submitDetailsForm()" class="custom-button active"  />
                                    </li>
                                </ul>
                  
                </div>
              </div>
              </form>
                        
                    </div>
                    
                    <div class="dashboard-widget  mb-40">
                    <h5 class="title mb-10"><?php echo lang('label_ur_bid_statement');?></h5>
                    <div class="dashboard-purchasing-tabs">
                        <ul class="nav-tabs nav">
                            
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active fade" id="current">
                                <table class="purchasing-table">
                                    <thead style="font-size: 12px;">
                                        <th><?php echo lang('date');?></th>
                                        <th><?php echo lang('label_listing_id');?></th>
                                        <th><?php echo lang('label_credit_used');?></th>
                                        <th><?php echo lang('LUB_home_Live_page_bid_amount');?></th>
                                        <th><?php echo lang('label_rem_credit');?></th>
                                        <th><?php echo lang('account_bonus_points');?></th>
                                       
                                    </thead>
                                    <tbody>
                                    <?php if($statement){ foreach($statement as $data){?>
                                        <tr style="font-size: 12px;">
                                            <td data-purchase="date"><?php
                                            $timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION . 'country_id'));
                                            echo $this->general->convert_local_time($data->bid_date, $timeZone, 'date');
                                            ?></td>
                                            <td data-purchase="auction id"><a href="<?php echo $this->general->lang_uri('/auctions/' . str_replace(' ','-',$data->name) . '-' . $data->product_id); ?>" target="_blank"><?php echo $data->auc_id;?> </a></td>
                                            <td data-purchase="Credit used"><?php echo $data->click_cost;?></td>
                                            <td data-purchase="bid value"><?php echo $data->userbid_bid_amt;?></td>
                                            <td data-purchase="Remaining Credits"><?php echo $data->remaining_bids;?></td>
                                            <td data-purchase="Bonus Points"><?php echo $data->remaining_bonus;?></td>
                                        </tr>
                                     <?php }}else{?>
                                        <tr style="font-size: 12px;">
                                            <td align="center" colspan="6"><?php echo lang('label_no_record_found');?></td>                                            
                                        </tr>
                                     <?php }?>
                                        
                                    </tbody>
                                </table>
                                
                                <?=$this->pagination->create_links()?>
                            </div>
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
