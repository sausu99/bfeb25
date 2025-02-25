
<div class="dashboard-widget mb-40">
                        <div class="dashboard-title mb-30">
                            <h5 class="title"><?php echo lang('label_buy_credit_title');?></h5>
                        </div>
                        <?php
                if ($this->session->flashdata('message')) {
                    ?>
                    <div role="alert" class="alert alert-danger">
                        <button aria-label="Close" data-dismiss="alert" class="close" type="button">×</button>
                        <i class="fa fa-warning">&nbsp;</i><?php echo $this->session->flashdata('message') ?></div>
                    <?php
                }
                if (validation_errors()) {
                    ?>
                    <div role="alert" class="alert alert-danger">
                        <span aria-label="Close" data-dismiss="alert" class="close">×</span>
                        <i class="fa fa-warning">&nbsp;</i>
                        <?php echo validation_errors(); ?></div>

                    <?php    }?>
                    
                    <div class="row justify-content-center mb-30-none">
                           
                    <?php
               
                if ($bid_packages) {
                    $i = 1;
                    foreach ($bid_packages as $package) {
                                                  
                        ?>
                        
                        <div class="col-md-4 col-sm-6 mb-3">
                        <div class="dashboard-item">
                                    
                                    <div class="content">
                                        <h2 class="title"><span ><?php echo $this->general->formate_price_currency_sign($package->amount, '', ''); ?></span></h2>
                                        <h6 class="info"><?php echo lang('account_my_bid_credit');?>: <?php echo $package->credits; ?> | <?php echo lang('bonus');?>: <?php echo $package->bonus_points; ?> | <?php echo lang('label_validity');?>: <?php echo lang('label_unlimited');?></h6>
                                    </div>
                                </div>
                            <form method="post" name="payment<?php echo $i; ?>" id="payment<?php echo $i; ?>" action="">
                                <input type="hidden" name="transaction_type" value="purchase_credit" />                         
                                <input name="package" type="hidden" value="<?php echo $package->id; ?>" />
                                <input type="hidden" name="payment_type" id="payment_type" class="payment_type" value=""  />
                                <input type="hidden" name="package_price" value="<?php echo $package->amount; ?>">
                                <div class="credit_inner text-center">
                                    

                                    <div class="credit_dtl">
                                        
                                        <p><input name="voucher" type="text" size="20" id="voucher<?php echo $i; ?>"  class="form-control" placeholder="<?php echo $this->lang->line('register_voucher_code'); ?>"  onchange="checkpromo(this.value, '<?php echo $package->id; ?>')" />
                                            <span id="voucher_msg<?php echo $package->id; ?>" class="text-danger"></span>
                                        </p>
                                        <?php if($payment_lists){ if(count($payment_lists)>1){?>
                                        <select  name="payment_type" class="form-control mb-3">
                                            <option value=""><?php echo lang('choose_payment_gateway'); ?></option>                     
                                            <?php foreach ($payment_lists as $payment) { ?>

                                                <option value="<?php echo $payment->id; ?>" data-content='<img src="<?php echo base_url() . $payment->payment_logo; ?>" alt="<?php echo $payment->payment_gateway; ?>">'><?php echo $payment->payment_gateway; ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php }else{?>
                                        <input type="hidden" name="payment_type" value="<?php echo $payment_lists[0]->id; ?>" />
										<?php }}else{?>
                                        <input type="hidden" name="payment_type" value="" />
                                        <?php }?>
                                        <?php //echo form_error('payment_type'); ?>
                                    </div>
                                    <div class="btn_sec"><button  class="btn btn-primary btn-sm"><?php echo lang('buy_now'); ?></button></div>

                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>

                        <?php
                       
                    }
                }
                ?>

                        
                                
                            
                            
                            
                        </div>
                      
                    </div>
                    
                    <div class="dashboard-widget">
                        <h5 class="title mb-10"><?php echo lang('label_ur_bid_statement');?></h5>
                        <div class="dashboard-purchasing-tabs">
                            
                            <div class="tab-content">
                                <div class="tab-pane show active fade" id="current">
                                    <table class="purchasing-table">
                                        <thead style="font-size: 12px;">
                                            <th><?php echo lang('date');?></th>
                                            <th><?php echo lang('label_package');?></th>
                                            <th><?php echo lang('price');?></th>
                                            <th><?php echo lang('account_my_bid_credit');?></th>
                                            
                                            <th><? echo lang('account_bonus_points');?></th>
                                            <th><?php echo lang('label_txn_id');?></th>
                                        </thead>
                                        <tbody>
                                        <?php if($get_trans){?>
                                        	<?php foreach ($get_trans as $trans) { ?>
                                            <tr style="font-size: 12px;">
                                            	
                                                <td data-purchase="date of purchase"><?php
                                            $timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION . 'country_id'));
                                            echo $this->general->convert_local_time($trans->transaction_date, $timeZone, 'date');
                                            ?></td>
                                                <td data-purchase="package"><?php echo $trans->name;?></td>
                                                <td data-purchase="bid price"><?php echo $this->general->formate_price_currency_sign($trans->amount, '', ''); ?></td>
                                                <td data-purchase="bid credits"><?php echo isset($trans->credit_get) ? $trans->credit_get : "---"; ?></td>
                                                <td data-purchase="bonus points"><?php echo isset($trans->bonus_points) ? $trans->bonus_points : "---"; ?></td>
                                                
                                                <td data-purchase="transaction id"><?php echo $trans->txn_id;?></td>
                                            </tr>
                                            <?php
                                }}else{
                            
                            
                                ?>
                                <tr align="center"><td colspan="6"><?php echo lang('all_0record_found');?></td></tr>
                                <?php }?>
                                            
                                        </tbody>
                                    </table>
                                    <?php //if(count($get_trans)>10){?>
                                    <div style="font-style: italic; font-size: 11px; font-weight: bold; color: #9a7ef2;">
                                    <?php echo sprintf(lang('label_view_bid_credit_history'),'<a href="'.$this->general->lang_uri('/'.MY_ACCOUNT.'/user/purchases').'">','</a>');?></div>
                                    <?php //}?>
                                </div>
                            </div>
                        </div>
                    </div>
					
<script type="text/javascript">
    function checkpromo(promo_code, bid_package)
    {
        $("#voucher_msg" + bid_package).html('<i class="fa fa fa-spinner fa-spin"></i>');
        //console.log($("#voucher").val());
        $.ajax({
            type: "POST",
            url: "<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/voucher_ajax/index'); ?>",
            data: "voucher_code=" + promo_code,
            success: function (msg) {
                $("#voucher_msg" + bid_package).html(msg);
            }});
    }
</script>