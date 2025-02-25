<?php error_reporting(0);?>
<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  <a href="<?=site_url(ADMIN_DASHBOARD_PATH).'/members/index'?>">Member  Management </a></span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2> Member Add Balance </h2>
	<div align="center"><?php $this->load->view('menu');?></div>
	
    <div class="mid_frm">

    
<form name="member" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">
<input name="user_id" type="hidden" class="inputtext" id="user_id" value="<?php echo $profile->id;?>" size="15" />
<table width=100% align=center border=0 cellspacing=0 cellpadding=8 class="light">

<?php
//print_r($profile);
?>
<tr style=" background-color:#FFFFFF;">
  <td height="30" bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;">Add Balance to <strong><?php echo $profile->first_name.' '.$profile->last_name;?></strong></div></td>
  <td bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;">Current Balance:  <strong><?php echo $profile->balance;?> Credits </strong></div></td>
</tr>
<tr>
  <td width="229" height="30" class="hmenu_font">Transaction Type:</td>
  <td width="429">
  <input type="hidden" name="currency_code" value="<?php echo $profile->currency_code;?>" />
  <select name="payment_method">
			<option value="">---Payment Type---</option>
			<option value="paypal" <?php echo set_select('payment_method', 'paypal'); ?>>Paypal</option>			
			<option value="direct" <?php echo set_select('payment_method', 'direct'); ?>>Direct Deposit</option>            
			<option value="cheque" <?php echo set_select('payment_method', 'cheque'); ?>>Cheque</option>
			<option value="money_order" <?php echo set_select('payment_method', 'money_order'); ?>>Money Order</option>
			<option value="refund" <?php echo set_select('payment_method', 'refund'); ?>>Refund</option>			
			<option value="deduct" <?php echo set_select('payment_method', 'deduct'); ?>>Deduction</option>
			</select>
			<?php echo form_error('payment_method'); ?>  </td>
  </tr>
<tr>
  <td height="30" class="hmenu_font">Amount(<?=$profile->currency_sign?>):</td>
  <td>
  
  <input name="amount" type="text" id="amount" value="<?php echo set_value('amount');?>" >
			<?php echo form_error('amount'); ?>			</td>
</tr>
<tr>
  <td height="30" class="hmenu_font">Balance:</td>
  <td><input name="credit_get" type="text" id="credit_get"  value="<?php echo set_value('credit_get');?>" >
    <?=form_error('credit_get')?></td>
</tr>
<tr>
  <td height="30" class="hmenu_font">Transaction Name(Description):</td>
  <td><textarea name="transaction_name"><?php echo set_value('transaction_name');?></textarea>
    <?=form_error('transaction_name')?></td>
</tr>
<tr>
  <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Add Balance" />  </td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>
	