<?php
// echo "<pre>";
// print_r($_POST);
// exit;

if ($this->input->post('transaction_type', true) == 'purchase_credit') {
    $price = $this->input->post('package_price', true);
    $transaction_type = $this->input->post('transaction_type', true);
} else if ($this->input->post('transaction_type', true) == 'buy_auction') {
    $price = $this->input->post('amount', true);
    $transaction_type = $this->input->post('transaction_type', true);
} else if ($this->input->post('transaction_type', true) == 'pay_for_won_auction') {
    $price = $this->input->post('amount', true);
    $transaction_type = $this->input->post('transaction_type', true);
}


?>
<script>
    window.onload = function () {
        var d = new Date().getTime();
        document.getElementById("tid").value = d;
        document.getElementById("order_id").value = d;
        $('#display_amount').html("<?php echo DEFAULT_CURRENCY_SIGN . ' '; ?>" + $("#amount_").val());

    };
</script>
<div class="dash-bid-item dashboard-widget mb-40-60">
<div class="header">
    <h4 class="title"><?php echo lang('account_processing_payment'); ?></h4>
 </div>

<?php

if ($this->input->post('transaction_type') == 'purchase_credit') {
	
	$this->package_id = $this->input->post('package',TRUE);
	$this->package_data = $this->account_module->get_bid_package_byid($this->package_id);
	
	$this->item_name = $this->package_data->name . ' @ ' . $this->package_data->credits.' '.lang('account_bidpack_bids');
	$this->total_cost = $this->general->exchange_price($this->package_data->amount);
	
	$total_bids = $this->package_data->credits;
			$extra_bids_per = "";
			if ($this->input->post('voucher')) {
				$voucher_id = $this->general->buy_bids_voucher($this->input->post('voucher'));
				if ($voucher_id > 0) {
						$extra_bids = $this->general->give_extra_bids_voucher($voucher_id, $this->package_data->credits);						
						$total_bids = $this->package_data->credits + $extra_bids;
						
						$query = $this->db->get_where("vouchers", array("id" => $voucher_id));						
						$data = $query->row();						
						$extra_bids_per = $data->extra_bids;
					}
			}
				
$order_details = '<table class="table table-bordered">
                          
                          <tbody>
                            <tr>                              
                              <td>'.$this->item_name.'</td>                              
                              <td>'.$this->general->formate_price_currency_sign($this->total_cost).'</td>
                            </tr>';
                 if($extra_bids_per){           
              $order_details.= '<tr>                              
                              <td>Voucher Code</td>                              
                              <td>Applicable for extra bid of '.$extra_bids_per.'%</td>
                            </tr>';}
				$order_details.= '<tr>                              
                              <td>Total Bids</td>                              
                              <td>'.$total_bids.'</td>
                            </tr>
                            
                            <tr>                              
                              <td>Total Cost</td>                              
                              <td>'.$this->general->formate_price_currency_sign($this->total_cost).'</td>
                            </tr>
                            
                          </tbody>
                        </table>';
						echo $order_details;
}else{
?>                         

<?php }?>                         
<form method="POST" name="customerData" action="<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/ccavenue_order'); ?>">
    <?php echo validation_errors(); ?>

    <div class="pay_container">
        <input type="hidden" name="tid" id="tid" value="<?php echo time();?>"/>
        <input type="hidden" name="merchant_id" value="<?php echo $merchant_id;?>"/>
        <input type="hidden" name="currency" value="<?php echo DEFAULT_CURRENCY_CODE; ?>"/>
        <input type="hidden" name="redirect_url" value="<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/ccavenue_ipn'); ?>"/>
        <input type="hidden" name="cancel_url" value="<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/ccavenue_cancel'); ?>"/>
        <input type="hidden" name="order_id" id="order_id" value=""/>
        <input type="hidden" name="transaction_type" value="<?php if (isset($transaction_type)) echo $transaction_type; ?>">
        <input type="hidden" name="package" value="<?php echo $this->input->post('package', true); ?>">
        <input type="hidden" name="billing_name" value="<?php if ($personal_details->first_name) echo $personal_details->first_name; ?>"/>
        <input type="hidden" name="billing_address" value="<?php if ($personal_details->address) echo $personal_details->address; ?>"/>
        <input type="hidden" name="billing_city" value="<?php if ($personal_details->city) echo $personal_details->city; ?>"/>
        <input type="hidden" name="billing_state" value="<?php if ($personal_details->state) echo $personal_details->state; ?>"/>
        <input type="hidden" name="billing_zip" value="<?php if ($personal_details->post_code) echo $personal_details->post_code; ?>"/>
        <input type="hidden" name="billing_country" value="India"/>
        <!-- <input type="hidden" name="billing_tel" value="9999999999"/> -->
        <input type="hidden" name="billing_email" value="<?php if (isset($personal_details->email)) echo $personal_details->email; ?>"/>
        <input type="hidden" name="language" value="EN"/>
        <input type="hidden" id="amount_" name="amount" value="<?php echo set_value('amount',$price);?>" />
               <?php if ($this->input->post('transaction_type', true) == 'buy_auction' || $this->input->post('transaction_type', true) == 'pay_for_won_auction') { ?>
            <input type="hidden" name="name" value="<?php echo $this->input->post('name', true); ?>">
            <input type="hidden" name="product_id" value="<?php echo $this->input->post('product_id', true); ?>">
            <input type="hidden" name="email" value="<?php echo $this->input->post('email', true); ?>">
            <input type="hidden" name="address" value="<?php echo $this->input->post('address', true); ?>">
            <input type="hidden" name="address2" value="<?php echo $this->input->post('address2', true); ?>">
            <input type="hidden" name="country" value="<?php echo $this->input->post('country', true); ?>">
            <input type="hidden" name="city" value="<?php echo $this->input->post('city', true); ?>">
            <input type="hidden" name="post_code" value="<?php echo $this->input->post('post_code', true); ?>">
            <input type="hidden" name="phone" value="<?php echo $this->input->post('phone', true); ?>">
            <input type="hidden" name="ship_name" value="<?php echo $this->input->post('ship_name', true); ?>">
            <input type="hidden" name="ship_address" value="<?php echo $this->input->post('ship_address', true); ?>">
            <input type="hidden" name="ship_address2" value="<?php echo $this->input->post('ship_address2', true); ?>">
            <input type="hidden" name="ship_country" value="<?php echo $this->input->post('ship_country', true); ?>">
            <input type="hidden" name="ship_city" value="<?php echo $this->input->post('ship_city', true); ?>">
            <input type="hidden" name="ship_post_code" value="<?php echo $this->input->post('ship_post_code', true); ?>">
            <input type="hidden" name="ship_phone" value="<?php echo $this->input->post('ship_phone', true); ?>">


        <?php } ?>
		
       <?php /*?> <div class="form-group">
            <label class="col-sm-3">Amount : </label>
            <div id="display_amount" class="col-sm-9"></div>

            <div class="clearfix"></div>
        </div><?php */?>
        <?php if ($ccavenue_pay_mode == '2') { ?>
            <div class="form-group">
                <label class="col-sm-3">Payment Option: </label> 
                <div class="col-sm-8">
                    <div class="row">
                        <span class="col-sm-6">
                            <input class="payOption" type="radio" name="payment_option" value="OPTCRDC" checked> Credit Card
                        </span>
                        <span class="col-sm-6">
                            <input class="payOption" type="radio" name="payment_option" value="OPTDBCRD"> Debit Card
                        </span>
                        <span class="col-sm-6">
                            <input class="payOption" type="radio" name="payment_option" value="OPTNBK"> Net Banking 
                        </span>

                        <span class="col-sm-6">
                            <input class="payOption" type="radio" name="payment_option" value="OPTCASHC"> Cash Card
                        </span>

                                  
                        <span class="col-sm-6">
                            <input class="payOption" type="radio" name="payment_option" value="OPTWLT"> Wallet
                        </span>
                        <span class="col-sm-6">
                            <input class="payOption" type="radio" name="payment_option" value="OPTMOBP"> Mobile Payments
                        </span>
                    </div>
                    <?php echo form_error('payOption'); ?>
                </div>

                <div class="clearfix"></div>
            </div>
        <?php } ?>

       
        
        <div class="form-group">
            <label class="col-sm-3"></label>
            <div class="col-sm-12">
                <INPUT TYPE="submit" class="btn_bid" value="<?php echo lang('account_bttn_click_here');?>">
            </div>
            <div class="clearfix"></div>
        </div>


    </div>
</form>

</div>
<script language="javascript" type="text/javascript" src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'json.js'); ?>"></script>

<script>

    $(function () {

        /* json object contains
         1) payOptType - Will contain payment options allocated to the merchant. Options may include Credit Card, Net Banking, Debit Card, Cash Cards or Mobile Payments.
         2) cardType - Will contain card type allocated to the merchant. Options may include Credit Card, Net Banking, Debit Card, Cash Cards or Mobile Payments.
         3) cardName - Will contain name of card. E.g. Visa, MasterCard, American Express or and bank name in case of Net banking. 
         4) status - Will help in identifying the status of the payment mode. Options may include Active or Down.
         5) dataAcceptedAt - It tell data accept at CCAvenue or Service provider
         6)error -  This parameter will enable you to troubleshoot any configuration related issues. It will provide error description.
         */
        var jsonData;
        var access_code = "<?php echo $access_code;?>" // shared by CCAVENUE 
        var amount = "<?php echo $this->input->post('package_price', true); ?>";
        var currency = "INR";

        $.ajax({
            url: '<?php echo $payment_url; ?>' + access_code + '&currency=' + currency + '&amount=' + amount,
            dataType: 'jsonp',
            jsonp: false,
            jsonpCallback: 'processData',
            success: function (data) {
                jsonData = data;
                // processData method for reference
                processData(data);
                // get Promotion details
                $.each(jsonData, function (index, value) {
                    if (value.Promotions != undefined && value.Promotions != null) {
                        var promotionsArray = $.parseJSON(value.Promotions);
                        $.each(promotionsArray, function () {
                            console.log(this['promoId'] + " " + this['promoCardName']);
                            var promotions = "<option value=" + this['promoId'] + ">"
                                    + this['promoName'] + " - " + this['promoPayOptTypeDesc'] + "-" + this['promoCardName'] + " - " + currency + " " + this['discountValue'] + "  " + this['promoType'] + "</option>";
                            $("#promo_code").find("option:last").after(promotions);
                        });
                    }
                });
            },
            error: function (xhr, textStatus, errorThrown) {
                // alert('An error occurred! ' + ( errorThrown ? errorThrown :xhr.status ));
                //console.log("Error occured");
            }
        });

        $(".payOption").click(function(){
   			var paymentOption="";
   			var cardArray="";
   			var payThrough,emiPlanTr;
		    var emiBanksArray,emiPlansArray;
   			
           	paymentOption = $(this).val();
           	$("#card_type").val(paymentOption.replace("OPT",""));
           	$("#card_name").children().remove(); // remove old card names from old one
            $("#card_name").append("<option value=''>Select</option>");
           	$("#emi_div").hide();
           	
           	//console.log(jsonData);
           	$.each(jsonData, function(index,value) {
           		//console.log(value);
            	  if(paymentOption !="OPTEMI"){
	            	 if(value.payOpt==paymentOption){
	            		cardArray = $.parseJSON(value[paymentOption]);
	                	$.each(cardArray, function() {
	    	            	$("#card_name").find("option:last").after("<option class='"+this['dataAcceptedAt']+" "+this['status']+"'  value='"+this['cardName']+"'>"+this['cardName']+"</option>");
	                	});
	                 }
	              }
	              
	              if(paymentOption =="OPTEMI"){
		              if(value.payOpt=="OPTEMI"){
		              	$("#emi_div").show();
		              	$("#card_type").val("CRDC");
		              	$("#data_accept").val("Y");
		              	$("#emi_plan_id").val("");
						$("#emi_tenure_id").val("");
						$("span.emi_fees").hide();
		              	$("#emi_banks").children().remove();
		              	$("#emi_banks").append("<option value=''>Select your Bank</option>");
		              	$("#emi_tbl").children().remove();
		              	
	                    emiBanksArray = $.parseJSON(value.EmiBanks);
	                    emiPlansArray = $.parseJSON(value.EmiPlans);
	                	$.each(emiBanksArray, function() {
	    	            	payThrough = "<option value='"+this['planId']+"' class='"+this['BINs']+"' id='"+this['subventionPaidBy']+"' label='"+this['midProcesses']+"'>"+this['gtwName']+"</option>";
	    	            	$("#emi_banks").append(payThrough);
	                	});
	                	
	                	emiPlanTr="<tr><td>&nbsp;</td><td>EMI Plan</td><td>Monthly Installments</td><td>Total Cost</td></tr>";
							
	                	$.each(emiPlansArray, function() {
		                	emiPlanTr=emiPlanTr+
							"<tr class='tenuremonth "+this['planId']+"' id='"+this['tenureId']+"' style='display: none'>"+
								"<td> <input type='radio' name='emi_plan_radio' id='"+this['tenureMonths']+"' value='"+this['tenureId']+"' class='emi_plan_radio' > </td>"+
								"<td>"+this['tenureMonths']+ "EMIs. <label class='merchant_subvention'>@ <label class='emi_processing_fee_percent'>"+this['processingFeePercent']+"</label>&nbsp;%p.a</label>"+
								"</td>"+
								"<td>"+this['currency']+"&nbsp;"+this['emiAmount'].toFixed(2)+
								"</td>"+
								"<td><label class='currency'>"+this['currency']+"</label>&nbsp;"+ 
									"<label class='emiTotal'>"+this['total'].toFixed(2)+"</label>"+
									"<label class='emi_processing_fee_plan' style='display: none;'>"+this['emiProcessingFee'].toFixed(2)+"</label>"+
									"<label class='planId' style='display: none;'>"+this['planId']+"</label>"+
								"</td>"+
							"</tr>";
						});
						$("#emi_tbl").append(emiPlanTr);
	                 } 
                  }
           	});
           	
         });
   
	  
      $("#card_name").click(function(){
      	if($(this).find(":selected").hasClass("DOWN")){
      		alert("Selected option is currently unavailable. Select another payment option or try again later.");
      	}
      	if($(this).find(":selected").hasClass("CCAvenue")){
      		$("#data_accept").val("Y");
      	}else{
      		$("#data_accept").val("N");
      	}
      });

        


        $("#card_number").focusout(function(){
			/*
			 emi_banks(select box) option class attribute contains two fields either allcards or bin no supported by that emi 
			*/ 
			if($('input[name="payment_option"]:checked').val() == "OPTEMI"){
				if(!($("#emi_banks option:selected").hasClass("allcards"))){
				  if(!$('#emi_banks option:selected').hasClass($(this).val().substring(0,6))){
					  alert("Selected EMI is not available for entered credit card.");
				  }
			   }
		   }
		  
		});


        // Emi section end    


        // below code for reference 

        function processData(data){
         var paymentOptions = [];
         var creditCards = [];
         var debitCards = [];
         var netBanks = [];
         var cashCards = [];
         var mobilePayments=[];
         $.each(data, function() {
         	 // this.error shows if any error   	
             console.log(this.error);
              paymentOptions.push(this.payOpt);
              switch(this.payOpt){
                case 'OPTCRDC':
                	var jsonData = this.OPTCRDC;
                 	var obj = $.parseJSON(jsonData);
                 	$.each(obj, function() {
                 		creditCards.push(this['cardName']);
                	});
                break;
                case 'OPTDBCRD':
                	var jsonData = this.OPTDBCRD;
                 	var obj = $.parseJSON(jsonData);
                 	$.each(obj, function() {
                 		debitCards.push(this['cardName']);
                	});
                break;
              	case 'OPTNBK':
	              	var jsonData = this.OPTNBK;
	                var obj = $.parseJSON(jsonData);
	                $.each(obj, function() {
	                 	netBanks.push(this['cardName']);
	                });
                break;
                
                case 'OPTCASHC':
                  var jsonData = this.OPTCASHC;
                  var obj =  $.parseJSON(jsonData);
                  $.each(obj, function() {
                  	cashCards.push(this['cardName']);
                  });
                 break;
                   
                  case 'OPTMOBP':
                  var jsonData = this.OPTMOBP;
                  var obj =  $.parseJSON(jsonData);
                  $.each(obj, function() {
                  	mobilePayments.push(this['cardName']);
                  });
              }
              
            });
           
           //console.log(creditCards);
          // console.log(debitCards);
          // console.log(netBanks);
          // console.log(cashCards);
         //  console.log(mobilePayments);
            
      }
    });
</script>
