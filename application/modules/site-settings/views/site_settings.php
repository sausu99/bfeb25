<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Sitesetting  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>Web Site Configuration </h2>
    <div class="mid_frm">
    

    
    
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>
    
<?php
//print_r($site_set);
?>
<form name="sitesetting" method="post" action=""   enctype="multipart/form-data" accept-charset="utf-8">

<table width=90% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
<td width=229 class="hmenu_font">Website Name</td>
<td width="429">
<input type=text name="site_name" class="inputtext" size=45 value="<?php echo set_value('site_name',$site_set['site_name']);?>">
<?=form_error('site_name')?></td>
</tr>
<tr>
<td width=229 class="hmenu_font">Site Logo </td>
<td width="450"><input type="file" name="site_logo">(Recommended Size 245px X 47px)
<div class="error"><?=$this->session->userdata('error_img1');?></div>
<?php if($site_set['site_logo']): ?>
<div>
  <img src="<?php echo site_url(SITE_LOGO_PATH).$site_set['site_logo']; ?>">
</div>
<?php endif; ?>
</td>
</tr>


<tr>
<td width=229 class="hmenu_font">System Email</td>
<td width="429"><input type=text name="system_email" class="inputtext" size=45 value="<?php echo set_value('system_email',$site_set['system_email']);?>">
<?=form_error('system_email')?>
</td>
</tr>

<tr>
<td width=229 class="hmenu_font">Contact Email </td>
<td width="429"><input name="contact_email" type=text class="inputtext" id="contact_email" value="<?php echo set_value('contact_email',$site_set['contact_email']);?>" size=45><?=form_error('contact_email')?></td>
</tr>
<tr>
  <td class="hmenu_font">Subscription Email</td>
  <td>
  <input type=text name="subscription_email" class="inputtext" size=45 value="<?php echo set_value('subscription_email',$site_set['subscription_email']);?>">
<?=form_error('subscription_email')?>
  </td>
</tr>
<tr>
<td width=229 class="hmenu_font">Contact Phone </td>
<td width="429"><input name="contact_phone" type=text class="inputtext" id="contact_phone" value="<?php echo set_value('contact_phone',$site_set['contact_phone']);?>" size=45><?=form_error('contact_phone')?></td>
</tr>
<tr>
<td width=229 class="hmenu_font">Contact Address </td>
<td width="429"><input name="contact_address" type=text class="inputtext" id="contact_address" value="<?php echo set_value('contact_address',$site_set['contact_address']);?>" size=45><?=form_error('contact_address')?></td>
</tr>
<tr><td colspan="2"><h3>System Bonus Settings</h3></td></tr>
<tr>
<td width=229 class="hmenu_font">Signup Bonus</td>
<td width="429"><input name="signup_bonus" type=text class="inputtext" id="signup_bonus" value="<?php echo set_value('signup_bonus',$site_set['signup_bonus']);?>" size=45><?=form_error('signup_bonus')?></td>
</tr>

<tr>
<td width=229 class="hmenu_font">Signup Free Credits</td>
<td width="429"><input name="signup_credit" type=text class="inputtext" id="signup_credit" value="<?php echo set_value('signup_credit',$site_set['signup_credit']);?>" size=45><?=form_error('signup_credit')?></td>
</tr>

<tr>
<td width=229 class="hmenu_font">Refer Bonus</td>
<td width="429"><input name="refer_bonus" type=text class="inputtext" id="refer_bonus" value="<?php echo set_value('refer_bonus',$site_set['refer_bonus']);?>" size=45><?=form_error('refer_bonus')?></td>
</tr>
<tr><td colspan="2"><h3>Product Settings</h3></td></tr>
<tr>
<td width=229 class="hmenu_font">Buy Now Discount(Min. Bids Placed)</td>
<td width="429"><input name="min_bid_4buy_now" type=text class="inputtext" id="min_bid_4buy_now" value="<?php echo set_value('min_bid_4buy_now',$site_set['min_bid_4buy_now']);?>" size=45><?=form_error('min_bid_4buy_now')?></td>
</tr>

<tr>
<td width=229 class="hmenu_font">Buy Now Bid Reward Times(Bonus)</td>
<td width="429"><input name="buy_now_bid_reward_times" type=text class="inputtext" id="buy_now_bid_reward_times" value="<?php echo set_value('buy_now_bid_reward_times',$site_set['buy_now_bid_reward_times']);?>" size=45><?=form_error('buy_now_bid_reward_times')?></td>
</tr>

<tr>
<td width=229 class="hmenu_font">Buy Now Discount Item(Per week)</td>
<td width="429">
<input name="buy_now_product" type=text class="inputtext" id="buy_now_product" value="<?php echo set_value('buy_now_product',$site_set['buy_now_product']);?>" size=45><?=form_error('buy_now_product')?></td>
</tr>

<?php /*?><tr><td colspan="2"><h3>App Store link</h3></td></tr>
<tr height="30">
  <td class="hmenu_font">Android </td>
  <td colspan="2"><input type="text" name="android_app" class="inputtext" size="45" value="<?php echo set_value('android_app',$site_set['android_app']);?>" />
  <?=form_error('android_app')?></td>
</tr>
<tr height="30">
  <td class="hmenu_font">IOS </td>
  <td colspan="2"><input type="text" name="ios_app" class="inputtext" size="45" value="<?php echo set_value('ios_app',$site_set['ios_app']);?>" />
  <?=form_error('ios_app')?>
</td>
</tr>
<?php */?>



<tr><td colspan="2"><h3>Social Media Settings</h3></td></tr>
<tr height="30">
  <td class="hmenu_font">Facebook Page URL </td>
  <td colspan="2"><input type="text" name="facebook_url" class="inputtext" size="45" value="<?php echo set_value('facebook_url',$site_set['facebook_url']);?>" />
  <?=form_error('facebook_url')?>
    (Ex.:http://www.facebook.com)</td>
</tr>
<tr height="30">
  <td class="hmenu_font">Facebook Appid </td>
  <td colspan="2"><input type="text" name="facebook_app_id" class="inputtext" size="45" value="<?php echo set_value('facebook_app_id',$site_set['facebook_app_id']);?>" />
  <?=form_error('facebook_app_id')?>
</td>
</tr>


<tr height="30">
  <td class="hmenu_font">Twitter Page URL </td>
  <td colspan="2"><input type="text" name="twitter_url" class="inputtext" size="45" value="<?php echo set_value('twitter_url',$site_set['twitter_url']);?>" />
   <?=form_error('twitter_url')?>
    (Ex.:http://www.twitter.com)</td>
</tr>
<?php /*?><tr height="30">
  <td class="hmenu_font">Twitter app key </td>
  <td colspan="2"><input type="text" name="twitter_app_key" class="inputtext" size="45" value="<?php echo set_value('twitter_url',$site_set['twitter_app_key']);?>" />
   <?=form_error('twitter_app_key')?>
  </td>
</tr>
<tr height="30">
  <td class="hmenu_font">Twitter app secret key </td>
  <td colspan="2"><input type="text" name="twitter_app_secret" class="inputtext" size="45" value="<?php echo set_value('twitter_url',$site_set['twitter_app_secret']);?>" />
   <?=form_error('twitter_app_secret')?>
    </td>
</tr><?php */?>
<tr height="30">
  <td class="hmenu_font">Linkdin Page URL </td>
  <td colspan="2"><input type="text" name="google_url" class="inputtext" size="45" value="<?php echo set_value('google_url',$site_set['google_url']);?>" />
  <?=form_error('google_url')?>
    (Ex.:http://www.google.com)</td>
</tr>
<tr height="30">
  <td class="hmenu_font">Google api key </td>
  <td colspan="2"><input type="text" name="google_api_key" class="inputtext" size="45" value="<?php echo set_value('google_api_key',$site_set['google_api_key']);?>" />
  <?=form_error('google_api_key')?>
   </td>
</tr>
<tr height="30">
  <td class="hmenu_font">Google client id </td>
  <td colspan="2"><input type="text" name="google_client_id" class="inputtext" size="45" value="<?php echo set_value('google_client_id',$site_set['google_client_id']);?>" />
  <?=form_error('google_client_id')?>
   </td>
</tr>

<tr height="30">
  <td class="hmenu_font">Instagram  Page URL </td>
  <td colspan="2"><input type="text" name="instagram_url" class="inputtext" size="45" value="<?php echo set_value('instagram_url',$site_set['instagram_url']);?>" />
  <?= form_error('instagram_url') ?>
    (Ex.:http://www.google.com)</td>
</tr>
<?php /*?><tr><td colspan="2"><h3>Mailchim Settings</h3></td></tr>
<tr height="30">
  <td class="hmenu_font">Mailchimp API Key </td>
  <td colspan="2"><input type="text" name="mailchimp_api_key" class="inputtext" size="45" value="<?php echo set_value('mailchimp_api_key',$site_set['mailchimp_api_key']);?>" />
  <?=form_error('mailchimp_api_key')?>
   </td>
</tr>
<tr height="30">
  <td class="hmenu_font">Mailchimp List ID</td>
  <td colspan="2"><input type="text" name="mailchimp_list_id" class="inputtext" size="45" value="<?php echo set_value('mailchimp_list_id',$site_set['mailchimp_list_id']);?>" />
  <?=form_error('mailchimp_list_id')?>
   </td>
</tr>

<tr><td colspan="2"><h3>Checkmobi SMS API Settings</h3></td></tr>
<tr height="30">
  <td class="hmenu_font">Secret API Key </td>
  <td colspan="2"><input type="text" name="checkmobi_sms_api_key" class="inputtext" size="45" value="<?php echo set_value('checkmobi_sms_api_key',$site_set['checkmobi_sms_api_key']);?>" />
  <?=form_error('checkmobi_sms_api_key')?>
   </td>
</tr>
<?php */?>
<tr><td colspan="2"><h3>Website Settings</h3></td></tr>
<tr height="30">
  <td class="hmenu_font">Website Status </td>
  <td colspan="2"><input name="site_status"  type="radio" value="online" checked="checked" />
    Online
      <input name="site_status" type="radio" value="offline" <?php if($site_set['site_status'] == 'offline'){ echo 'checked="checked"';}?> />
      Offline
      <input name="site_status" type="radio" value="maintanance" <?php if($site_set['site_status'] == 'maintanance'){ echo 'checked="checked"';}?> />
     Maintanance
  <?= form_error('site_status') ?>
      </td>
</tr>
<tr height="30" id="maintance_key_rw">
  <td class="hmenu_font">Maintance Key</td>
      <td><input type="text" name="maintainance_key" value="<?php echo set_value('maintainance_key',$site_set['maintainance_key']);?>">
      <?=form_error('maintainance_key')?>
      <td>
</tr>
<tr height="30">
  <td class="hmenu_font">Default Timezone</td>
  <td colspan="2"><?php
              $default_timezone = set_value('default_timezone',$site_set['default_timezone']);             
              echo $this->general->timezone_list('default_timezone', $default_timezone);
              echo form_error('default_timezone');
            ?></td>
</tr>

<tr><td colspan="2"><h3>Website Tracking Settings</h3></td></tr>

<tr height="30">
  <td class="hmenu_font">Google Analytic Codes</td>
  <td colspan="2"><textarea name="google_analytical_code" cols="34" rows="3" id="google_analytical_code"><?php echo set_value('google_analytical_code',$site_set['google_analytical_code']);?>
    <?= form_error('google_analytical_code') ?>
  </textarea></td>
</tr>

<tr height="30">
  <td class="hmenu_font">HTML Tracking Codes</td>
  <td colspan="2"><textarea name="html_tracking_code" cols="34" rows="3" id="html_tracking_code"><?php echo set_value('html_tracking_code',$site_set['html_tracking_code']);?></textarea>
<?= form_error('html_tracking_code') ?>
  </td>
</tr>

<tr height="30">
  <td class="hmenu_font">&nbsp;</td>
  <td colspan="2">&nbsp;</td>
</tr> 
<?php if($site_set['site_logo']): ?>
<input type="hidden" name="logo_old" value="<?php echo $site_set['site_logo']; ?>">
<?php endif; ?>

<?php /*?><tr><td colspan="2"><h3>Node Server Settings</h3></td></tr>
<tr height="30">
  <td class="hmenu_font">Node Server </td>
  <td colspan="2"><input type="text" name="node_server" class="inputtext" size="45" value="<?php echo set_value('node_server',$site_set['node_server']);?>" />
  <?=form_error('node_server')?>
   </td>
</tr>
<tr height="30">
  <td class="hmenu_font">Node Port </td>
  <td colspan="2"><input type="text" name="node_port" class="inputtext" size="45" value="<?php echo set_value('node_port',$site_set['node_port']);?>" />
  <?=form_error('node_port')?>
   </td>
</tr><?php */?>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Update" /></td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    var site_status= $("input[name='site_status']:checked").val();
    // alert(site_status);
    if(site_status=='maintanance')
            {
              $('#maintance_key_rw').show(500);
            }
            else
            {
              $('#maintance_key_rw').hide(500);
            }
            
    $("input[name='site_status']").on("click", function() {
            // alert($(this).val());
            var status=$(this).val();
            if(status=='maintanance')
            {
              $('#maintance_key_rw').show(500);
            }
            else
            {
              $('#maintance_key_rw').hide(500);
            }
        });
  })
</script>