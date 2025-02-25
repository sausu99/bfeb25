<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Email Settings Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>Update Email Settings </h2>
    <div class="mid_frm">
	


      

     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>
    
<?php
//print_r($site_set);
?>
<form name="sitesetting" method="post" action="" accept-charset="utf-8">


<table align=left cellpadding=2 cellspacing=5 width=99% border="0" class="light">
<?php if(count($lang_details)==1){?>
<input type="hidden" name="lang" value="<?php echo $lang_details[0]->id?>" />
<?php }else{?>
<tr>
      <td width="218"><strong>Language</strong></td>
      <td width="945"><select name="lang" onchange="redirect_lang_cms()" id="lang">
	<option value="">Select Language</option>
	<?php foreach($lang_details as $ln_data){?>
	<option value="<?php echo $ln_data->id;?>" <?php if($ln_data->id == $lang_id){echo 'selected="selected"';}?>><?php echo $ln_data->lang_name;?></option>
	<?php }?>
</select>
<?=form_error('lang')?></td>
</tr>
<?php }?>
<tr>
      <td><strong>Subject</strong></td>
      <td><input size="50" class="inputtext" type="text" id="subject" name="subject" value="<?php echo $email_data['subject'];?>">
<?=form_error('subject')?></td>
</tr>

<tr>
      <td colspan="2"><strong>Email Body</strong></td>
</tr>

<tr>
<td colspan="2">
		<?php
			echo form_fckeditor('content', $email_data['email_body'] );
		?>
		<?=form_error('content')?>		</td>
</tr>

<tr height=25 valign="middle">
  <td><strong>SMS Body</strong></td>
  <td><textarea name="sms_body" cols="77" rows="4"><?php echo $email_data['sms_body'];?></textarea>
  <?=form_error('sms_body')?></td>
</tr>

<tr height=25 valign="middle">
  <td><strong>Push Notification Body</strong></td>
  <td><textarea name="push_message_body" cols="77" rows="4"><?php echo $email_data['push_message_body'];?></textarea>
  <?=form_error('push_message_body')?></td>
</tr>

<tr height=25 valign="middle">
  <td><strong>Send Email Verification</strong></td>
  <td><input name="is_email_notification_send" type="radio" value="1" checked="checked">  Yes
      <input name="is_email_notification_send" type="radio" value="0" <?php if($email_data['is_email_notification_send'] == '0'){ echo 'checked="checked"';}?>>  No    
  <?=form_error('is_email_notification_send')?></td>
</tr>
<tr height=25 valign="middle">
  <td><strong>Send SMS Notification</strong></td>
  <td><input name="is_sms_notification_send" type="radio" value="1" checked="checked">  Yes
      <input name="is_sms_notification_send" type="radio" value="0" <?php if($email_data['is_sms_notification_send'] == '0'){ echo 'checked="checked"';}?>>  No   
  <?=form_error('is_sms_notification_send')?></td>
</tr>
<tr height=25 valign="middle">
  <td><strong>Send Push Notification</strong></td>
  <td><input name="is_push_notification_send" type="radio" value="1" checked="checked">  Yes
      <input name="is_push_notification_send" type="radio" value="0" <?php if($email_data['is_push_notification_send'] == '0'){ echo 'checked="checked"';}?>>  No   
  <?=form_error('is_push_notification_send')?></td>
</tr>
<tr height=25 valign="middle">
  <td colspan="2">&nbsp;</td>
</tr>
<tr height=25 valign="middle">
<td colspan="2" align="center">
<input type="submit" value="Submit" class="bttn"></td>
</tr>

<tr>
  <td colspan="2">
  <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin:20px 0;">
    <table width="100%" border="0" cellspacing="4" cellpadding="0">
      <tr>
        <td colspan="4" style="color:#FF0000"><p><strong>Legends (For the Dyanamic Content) </strong></p>
          <p>&nbsp;</p></td>
        </tr>
      <tr>
        <td width="25%">[SITENAME]</td>
        <td width="25%">Display Website Name </td>
        <td width="25%">[AUCTIONNAME]</td>
        <td width="16%">Auction Name </td>
      </tr>
      <tr>
        <td>[CONFIRM]</td>
        <td width="25%">Registration confirm link </td>
        <td>[AMOUNT]</td>
        <td>Amount/Credit</td>
      </tr>
      <tr>
        <td>[USERNAME]</td>
        <td>User name </td>
        <td>[FULLNAME]</td>
        <td>User Full Name</td>
      </tr>
      <tr>
        <td>[PASSWORD] </td>
        <td>User Password </td>
        <td>[FIRSTNAME]</td>
        <td>User First Name</td>
        </tr>
      <tr>
        <td>[REFERER]</td>
        <td>Referer Name</td>
        <td>[LASTNAME]</td>
        <td>User Last Name</td>
      </tr>
      <tr>
        <td>[DATE]</td>
        <td>Date and Time </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>
  </td>
</tr>
</table>
	 	

</form>

 </div>
    <div class="clear"></div>
</div>
	
<script>
function redirect_lang_cms(val)
{
	if($('#lang').val())
		document.location.href="<?php echo base_url().ADMIN_DASHBOARD_PATH.'/email-settings/edit/'.$this->uri->segment(4).'/';?>"+$('#lang').val();
}
</script>