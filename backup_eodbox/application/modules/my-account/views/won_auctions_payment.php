<div class="dashboard-widget mb-40">
                        <div class="dashboard-title mb-10">
                            <h5 class="title"><?php echo lang('checkout'); ?></h5>                            
                        </div>
<p class="mt-2">Please complete the below mentioned checkout form accurately for the speedy and timely delivey of your products.</p>
        
        <?php

    if($this->session->flashdata('error_message'))
      { ?>
      <div role="alert" class="alert alert-danger alert-dismissible fade show">
					
           <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
           <p><strong>Error</strong> - <span id="bid_error"><?php echo $this->session->flashdata('error_message') ?></span></p>
                            
       </div>
      <?php
    }
    ?>
    <?php
    if($this->session->flashdata('success_message'))
      { ?>
      <div role="alert" class="alert alert-success alert-dismissible fade show">
					
                    <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
                            <p><strong>Error</strong> - <span id="bid_error"><?php echo $this->session->flashdata('success_message') ?></span></p>
                            
      </div>
    
      <?php
    }
    ?>
    
        <div class="row">
          <div class="col-md-12 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Payment Details</span>
              
            </h4>
            <ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                  <h6 class="my-0"><?php echo $this->lang->line('won_auction_name');?></h6>
                  <small class="text-muted"><a href="<?php echo $this->general->lang_uri('/auctions/closed/'.$this->general->clean_url($auc_lists->name).'-'.$auc_lists->product_id);?>" target="_blank"><?php echo $auc_lists->name;?></a></small>
                </div>
                <span class="text-muted"><?php echo $this->general->default_formate_price_currency_sign($auc_lists->won_amt,'<span>','</span>');?></span>
              </li>
              
              
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                  <h6 class="my-0"><?php echo $this->lang->line('won_ship_cost');?></h6>
                  <small class="text-muted">&nbsp; <?php /*?>The charges are inclusive of insurance.<?php */?></small>
                </div>
                <span class="text-muted"><?php if($auc_lists->shipping_cost <= 0){echo 'Free Shipping';}else {echo $this->general->default_formate_price_currency_sign($auc_lists->shipping_cost,'','');}?></span>
              </li>
              
              <li class="list-group-item d-flex justify-content-between">
                <span><?php echo lang('total'); ?></span>
                <strong><?php echo $this->general->default_formate_price_currency_sign($auc_lists->won_amt+$auc_lists->shipping_cost,'','');?></strong>
              </li>
            </ul>
            
          </div>
          <div class="col-md-12">
          
          <form id="shipping_info" class="login-form" name="shipping_info" method="post" action="">
        <input type="hidden" name="transaction_type" value="pay_for_won_auction" />
        <input type="hidden" name="auc_win_id" value="<?php echo $auc_lists->auc_win_id;?>" />
        <input type="hidden" name="product_id" value="<?php echo $auc_lists->product_id;?>" />
        <input type="hidden" name="amount" value="<?php echo $auc_lists->won_amt;?>" />
        <input type="hidden" name="ship_cost" value="<?php echo $auc_lists->shipping_cost;?>" />
        <input type="hidden" name="auc_name" value="<?php echo $auc_lists->name;?>" />

        <?php $payment_type = (isset($payment_lists) && count($payment_lists)==1)? $payment_lists[0]->id :'';?>
        <input name="payment_type" id="payment_type" type="hidden" value="<?php echo $payment_type;?>" />
		
				<h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted"><?php echo $this->lang->line('won_payment_billing_ship_addr');?></span>
              
            </h4>
             <hr class="mb-4">
              <div class="row">
                <div class="col-sm-6 col-xs-6">
                  <h5 class="mb-2  margingB65"><?php echo $this->lang->line('profile_billing_addr');?> </h5>
                  
                  <div class="row">
                  
                  	<div class="col-md-12 mb-2">
                      <label><?php echo $this->lang->line('profile_name');?> <span class="text-danger">*</span></label>
                      <input name="name" type="text" class="np form-control" id="name" value="<?php echo set_value('name',$profile->first_name.' '.$profile->last_name);?>">
                      <div class="pay_error"><?=form_error('name')?></div>
                      </div>
                    <div class="col-md-12 mb-2">
                      <label><?php echo $this->lang->line('register_email');?> <span class="text-danger">*</span></label>
                      <input name="email" type="text" class="np form-control" id="email" value="<?php echo set_value('email',$profile->email);?>">
                      <div class="pay_error"><?=form_error('email')?></div>
                      
                    </div>
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('label_phone');?> <span class="text-danger">*</span> </label>
                      <input name="phone" type="text" class="np form-control" id="phone" value="<?php echo set_value('phone',$profile->mobile);?>">
                      <div class="pay_error"><?=form_error('phone')?></div>
                      </div>
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_country');?> <span class="text-danger">*</span></label>
                      <select name="country" class="np st form-control" id="country">

                        <option value=""><?php echo $this->lang->line('register_sel_country');?></option>
                        <?php foreach($countries as $country){?>
                        <option value="<?php echo $country->country;?>" <?php if($country->id == $profile->country)echo 'selected="selected"'; ?>  ><?php echo $country->country;?></option>
                        <?php } ?>

                      </select>
                      <div class="pay_error"><?=form_error('country')?></div>
                      </div>
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_city');?> <span class="text-danger">*</span> </label>
                      <input name="city" type="text" class="np form-control" id="city" value="<?php echo set_value('city',$profile->city);?>">
                      <div class="pay_error"><?=form_error('city')?></div>
                      </div>

                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_address');?> <span class="text-danger">*</span></label>
                      <input name="address" type="text" class="np form-control" id="address" value="<?php echo set_value('address',$profile->address);?>">
                      <div class="pay_error"><?=form_error('address')?></div>
                      </div>
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_address2');?></label>
                      <input name="address2" type="text" class="np form-control" id="address2" value="<?php echo set_value('address2',$profile->address2);?>">
                      <div class="clear"></div>
                    </div>
                    
                    
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_post_code');?> <span class="text-danger">*</span> </label>
                      <input name="post_code" type="text" class="np form-control" id="post_code" value="<?php echo set_value('post_code',$profile->post_code);?>">
                      <div class="pay_error"><?=form_error('post_code')?></div>
                      </div>
                    

                    </div>
                  
                </div>

                <div class="col-sm-6 col-xs-6">
                  <h5 class="mb-3"><?php echo $this->lang->line('won_payment_ship_addr');?></h5>
                  <div class="form-group checkgroup mb-30">
                            <input type="checkbox" name="toshipping_checkbox" id="toshipping_checkbox">
                            <label for="check">
                                <small><em>Check this box if Shipping Address and Billing Address are the same.</em></small>
                            </label>
                        </div>
                  
                  <div class="row">

                    <div class="col-md-12 mb-2">
                      <label><?php echo $this->lang->line('profile_name');?> <span class="text-danger">*</span></label>
                      <input name="ship_name" type="text" class="np form-control" id="ship_name" value="<?php echo set_value('ship_name');?>">
                      <div class="pay_error"><?=form_error('ship_name')?></div>
                      </div>
                      <div class="col-md-12 mb-2">
                      <label><?php echo $this->lang->line('register_email');?> <span class="text-danger">*</span></label>
                      <input name="ship_email" type="text" class="np form-control" id="ship_email" value="<?php echo set_value('ship_email');?>">
                      <div class="pay_error"><?=form_error('ship_email')?></div>
                      
                    </div>
                      <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('label_phone');?> <span class="text-danger">*</span> </label>
                      <input name="ship_phone" type="text" class="np form-control" id="ship_phone" value="<?php echo set_value('profile');?>">
                      <div class="pay_error"><?=form_error('ship_phone')?></div>
                    </div>
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_country');?> <span class="text-danger">*</span></label>
                      <select name="ship_country"  class="np st form-control" id="ship_country">

                        <option value=""><?php echo $this->lang->line('register_sel_country');?></option>
                        <?php foreach($countries as $country){?>
                        <option value="<?php echo $country->country;?>" <?php if($country->country == $this->input->post('ship_country'))echo 'selected="selected"'; ?>  ><?php echo $country->country;?></option>
                        <?php } ?>
                      </select>
                      <div class="pay_error"><?=form_error('ship_country')?></div>
                     </div>
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_city');?> <span class="text-danger">*</span></label>
                      <input name="ship_city" type="text" class="np form-control" id="ship_city" value="<?php echo set_value('ship_city');?>">
                      <div class="pay_error"><?=form_error('ship_city')?></div>
                     </div>
                    <div class="col-md-12 mb-2">
                      <label><?php echo $this->lang->line('profile_address');?> <span class="text-danger">*</span></label>
                      <input name="ship_address" type="text" class="np form-control" id="ship_address" value="<?php echo set_value('ship_address');?>">
                      <div class="pay_error"><?=form_error('ship_address')?></div>
                      </div>
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_address2');?></label>
                      <input name="ship_address2" type="text" class="np form-control" id="ship_address2" value="<?php echo set_value('ship_address2');?>">
                      </div>
                    
                    <div class="col-md-12 mb-2">
                      <label> <?php echo $this->lang->line('profile_post_code');?> <span class="text-danger">*</span> </label>
                      <input name="ship_post_code" type="text" class="np form-control" id="ship_post_code" value="<?php echo set_value('ship_post_code');?>">
                      <div class="pay_error"><?=form_error('ship_post_code')?></div>
                     </div>

                    
                    
                  </div>
                  
                </div>
                
              </div>
           		
                <?php if($payment_lists){ if(count($payment_lists)>1){?>
                <input type="hidden" name="payment_type" value="<?php echo $payment_lists[0]->id; ?>" />
                  <?php /*?><select  name="payment_type" class="form-control mb-3">
                      <option value=""><?php echo lang('choose_payment_gateway'); ?></option>                     
                      <?php foreach ($payment_lists as $payment) { ?>

                          <option value="<?php echo $payment->id; ?>" data-content='<img src="<?php echo base_url() . $payment->payment_logo; ?>" alt="<?php echo $payment->payment_gateway; ?>">'><?php echo $payment->payment_gateway; ?></option>
                      <?php } ?>
                  </select><?php */?>
                  <?php }else{?>
                  <input type="hidden" name="payment_type" value="<?php echo $payment_lists[0]->id; ?>" />
                  <?php }}else{?>
                  <input type="hidden" name="payment_type" value="" />
                <?php }?>
                                        
        	  <hr>
              <button  class="btn btn-primary btn-sm">Continue to checkout</button>
              
          </form>  
            
              
          </div>
        </div>

</div>
<script>
$(function(){
 $('li.payment_s').click(function(){
   $('li.payment_s').removeClass('selected');
   
   $('#payment_type').val($(this).attr('id'));
   //alert($(this).attr('id'));
   $(this).addClass('selected');  
  });
 })
</script>
<script>
$("#toshipping_checkbox").on("click",function(){
	
    var ship = $(this).is(":checked");
    $("[id^='ship_']").each(function(){
      var tmpID = this.id.split('ship_')[1];
	  console.log(tmpID);
      $(this).val(ship?$("#"+tmpID).val():"");
    });
});
</script>