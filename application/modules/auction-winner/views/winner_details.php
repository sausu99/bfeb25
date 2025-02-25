<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Winner Details Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View  Winner Details </h2>
<div class="mid_frm">
          
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>

<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
  <tr> 
  <th width="50%" align="left">Auction Details </th>
    <th align="left">Winner Details</th>
    </tr>

  <tr> 
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Auction Id</td>
        <td style="border-right:none;"><?php print($won_details->auc_id)?></td>
      </tr>
      <tr>
        <td>Name</td>
        <td width="524" style="border-right:none;"><?php echo $auc_name->name?></td>
      </tr>
      <tr>
        <td>End on </td>
        <td style="border-right:none;"><?php echo $won_details->auction_close_date?></td>
      </tr>
      <tr>
        <td>Price</td>
        <td align="left" style="border-right:none;"><?php echo $this->general->formate_price_currency_sign_admin($won_details->country,$won_details->price);?></td>
      </tr>
      <tr>
        <td>Sold at </td>
        <td align="left" style="border-right:none;"><?php echo $this->general->default_formate_price_currency_sign_admin($won_details->country,$won_details->won_amt);?></td>
      </tr>
      <tr>
        <td width="118">Shipping Cost </td>
        <td align="left" style="border-right:none;"><?php echo $this->general->default_formate_price_currency_sign_admin($won_details->country,$won_details->shipping_cost);?></td>
      </tr>
      <tr>
        <td><strong>Total Cost </strong></td>
        <td align="left" style="border-right:none;"><strong><?php echo $this->general->default_formate_price_currency_sign_admin($won_details->country,$won_details->won_amt+$won_details->shipping_cost);?></strong></td>
      </tr>
    </table></td>
    <td align="left" valign="top" style="border-right:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Name</td>
        <td style="border-right:none;"><?php echo $won_details->first_name.' '.$won_details->last_name?></td>
      </tr>
      <tr>
        <td>User Name </td>
        <td style="border-right:none;"><?php echo $won_details->user_name?></td>
      </tr>
      <tr>
        <td>Email</td>
        <td style="border-right:none;"><?php echo $won_details->email?></td>
      </tr>
      <tr>
        <td width="100"><strong>Payment Method </strong></td>
        <td style="border-right:none;"><strong>
          <?php if(isset($won_txn_details->payment_method)) echo $won_txn_details->payment_method; else echo 'NA';?>
        </strong></td>
      </tr>
      <tr>
        <td><strong>Payment Status </strong></td>
        <td style="border-right:none;"><strong>
          <?php echo $won_details->payment_status;?>
        </strong></td>
      </tr>
      <tr>
        <td><strong>Payment Date </strong></td>
        <td style="border-right:none;"><strong>
          <?php if(isset($won_txn_details->transaction_date)) echo $won_txn_details->transaction_date; else echo 'NA';?>
        </strong></td>
      </tr>
      <tr>
        <td><strong>Shipping Status  </strong></td>
        <td style="border-right:none;"><strong>
          <?php if($won_details->shipping_status == 2) echo 'Shipped'; else echo 'Not Shipped';?>
        </strong></td>
      </tr>
    </table></td>
    </tr>
</table>


  <p>
    <?php if(isset($winner_address->name)){?>
  </p>
  <p>&nbsp;
  </p>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
  <tr> 
  <th width="50%" align="left">Billing Details </th>
    <th align="center">Shipping Details </th>
    </tr>

  <tr> 
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
        <td>Name</td>
        <td style="border-right:none;"><?php echo $winner_address->name;?></td>
      </tr>
      <tr>
        <td width="70">Email</td>
        <td style="border-right:none;"><?php echo $winner_address->email;?></td>
      </tr>
      
      <tr>
        <td>Address1</td>
        <td style="border-right:none;"><?php echo $winner_address->address;?></td>
      </tr>
      <tr>
        <td>Address2</td>
        <td style="border-right:none;"><?php echo $winner_address->address2;?></td>
      </tr>
      <tr>
        <td>Country</td>
        <td style="border-right:none;"><?php echo $winner_address->country;?></td>
      </tr>
      <tr>
        <td>City</td>
        <td style="border-right:none;"><?php echo $winner_address->city;?></td>
      </tr>
      <tr>
        <td>Postal Code </td>
        <td style="border-right:none;"><?php echo $winner_address->post_code;?></td>
      </tr>
      <tr>
        <td>Phone</td>
        <td style="border-right:none;"><?php echo $winner_address->phone;?></td>
      </tr>
    </table></td>
    <td align="center" valign="top" style="border-right:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
        <td>Name</td>
        <td style="border-right:none;"><?php echo $winner_address->ship_name;?></td>
      </tr>
      
      <tr>
        <td>Address1</td>
        <td style="border-right:none;"><?php echo $winner_address->ship_address;?></td>
      </tr>
      <tr>
        <td>Address2</td>
        <td style="border-right:none;"><?php echo $winner_address->ship_address2;?></td>
      </tr>
      <tr>
        <td>Country</td>
        <td style="border-right:none;"><?php echo $winner_address->ship_country;?></td>
      </tr>
      <tr>
        <td>City</td>
        <td style="border-right:none;"><?php echo $winner_address->ship_city;?></td>
      </tr>
      <tr>
        <td>Postal Code </td>
        <td style="border-right:none;"><?php echo $winner_address->ship_post_code;?></td>
      </tr>
      <tr>
        <td>Phone</td>
        <td style="border-right:none;"><?php echo $winner_address->ship_phone;?></td>
      </tr>
    </table></td>
    </tr>
</table>
<?php } else{?>
<div class="clear"></div>
<div align="center" style=" margin:50px 0;"><strong>No Shipping &amp; Billing Address filled by winner yet.</strong></div>
<?php }?>
<div class="clear"></div>
<div style="border:1px #999999 solid; margin:20px 0px; padding:20px 100px;" align="center">

  <form id="ship" name="ship" method="post" action="">
    <table border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="100">Shipping Status </td>
        <td>
		<select name="shipping">
			<option value="">Select Shipping Status</option>
			<option value="1" <?php if($won_details->shipping_status ==1){echo 'selected="selected"';}?>>Not Shipped</option>
			<option value="2" <?php if($won_details->shipping_status ==2){echo 'selected="selected"';}?>>Shipped</option>
		</select>
		
		</td>
      </tr>
      <tr>
        <td>&nbsp;<input type="hidden" name="auction_winner_id" value="<?php echo $won_details->auction_winner_id;?>" /></td>
        <td><?php echo form_error('shipping'); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Submit" />&nbsp;</td>
      </tr>
    </table>
  </form>
</div>
</div>
    <div class="clear"></div>
</div>
