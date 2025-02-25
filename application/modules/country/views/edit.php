    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Country List </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
  <h2><?php echo $jobs;?> Country </h2>
    <div class="mid_frm">

 <?php if($data_country){  ?>  

<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">

<table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">

<tr>
  <td><strong>Choose Language</strong></td>
  <td>

  <select name="language">

  <option value="" selected="selected">Choose Language</option>

  <?php foreach($languages as $lang){?>

  <option value="<?php echo $lang->id;?>" <?php if($lang->id == $data_country->lang_id){ echo 'selected = "selected"'; }?> ><?php echo $lang->lang_name;?></option>
 
  <?php }?>
 
  </select>
  <?=form_error('language')?>
  </td>
</tr>

<tr>
      <td width="288"><strong>Country Name</strong></td>
      
    <td width="838">
    <input size="30" class="inputtext" type="text" id="country" name="country" value="<?php echo set_value('country', $data_country->country);?>">
       <?=form_error('country')?>
    </td>
</tr>
<tr>
      <td width="288"><strong>Country Code</strong></td>
      
    <td width="838">
    <input size="30" class="inputtext" type="text" id="country" name="country_code" value="<?php echo set_value('country_code', $data_country->country_code);?>">
       <?=form_error('country_code')?>
    </td>
</tr>

<tr>
      <td width="288"><strong>Country Timezone</strong></td>
      
    <td width="838"><?php
              $country_timezone = set_value('country_timezone',$data_country->country_timezone);             
              echo $this->general->timezone_list('country_timezone', $country_timezone);
              echo form_error('country_timezone');
            ?>
    </td>
</tr>
<tr>
      <td width="288"><strong>Country Shortcode</strong></td>
      
    <td width="838">
    <input size="10" class="inputtext" type="text" id="short_code" name="short_code" value="<?php echo set_value('short_code', $data_country->short_code);?>">
       <?=form_error('short_code')?>
    </td>
</tr>
<tr>
      <td width="288"><strong>Currency Sign</strong></td>
      
    <td width="838">
    <input size="10" class="inputtext" type="text" id="currency_sign" name="currency_sign" value="<?php echo set_value('currency_sign', $data_country->currency_sign);?>">
       <?=form_error('currency_sign')?>
    </td>
</tr>
<tr>
      <td width="288"><strong>Currency Code</strong></td>
      
    <td width="838">
    <input size="10" class="inputtext" type="text" id="currency_code" name="currency_code" value="<?php echo set_value('currency_code', $data_country->currency_code);?>">
       <?=form_error('currency_code')?>
    </td>
</tr>
<tr>
  <td class="hmenu_font">Exchange Rate </td>
  <td>
  <input name="exchange_rate" type="text" class="inputtext" id="exchange_rate" value="<?php echo set_value('exchange_rate',$data_country->exchange_rate);?>" size="10" />
    <?=form_error('exchange_rate')?>
	</td>
</tr>

<tr>
<td><strong>Currency display Style</strong></td>
<?php if( $this->input->post('currency_display_in') ){
     $currency_display = $this->input->post('currency_display_in');
      }else{
        $currency_display = $data_country->currency_display_in;
      }
?>
<td><input name="currency_display_in" type="radio" value="Left" <?php if($currency_display =="Left"){echo 'checked = "checked"'; } ?> />
    Left
      <input name="currency_display_in" type="radio" value="Right" <?php if($currency_display =="Right"){echo 'checked = "checked"'; } ?> />
      Right  <?=form_error('currency_display_in')?>
      </td>
</tr>

<tr>
      <td width="288"><strong>Default Country</strong></td>
      <?php if( $this->input->post('default_country') ){
       $default_country = $this->input->post('default_country');
        }else{
          $default_country = $data_country->default_country;
        }
      ?>
      
    <td><input name="default_country" type="radio" value="Yes" <?php if($default_country =="Yes"){ echo 'checked = "checked"'; } ?> />
    Yes
      <input name="default_country" type="radio" value="No" <?php if($default_country =="No"){echo 'checked = "checked"'; } ?>/>
      No  <?=form_error('default_country')?>
      </td>
</tr>

<tr>
<td><strong>Is Display?</strong></td>
 <?php if( $this->input->post('is_display') ){
       $is_display = $this->input->post('is_display');
        }else{
          $is_display = $data_country->is_display;
        }
      ?>
<td><input name="is_display" type="radio" value="Yes" <?php if($is_display =="Yes"){echo 'checked = "checked"'; } ?> />
    Yes
      <input name="is_display" type="radio" value="No" <?php if($is_display =="No"){echo 'checked = "checked"'; } ?>/>
      No  <?=form_error('is_display')?>
      </td>
</tr>
<tr>
<td width=229 class="hmenu_font">Language Flag </td>
<td width="429">
<input name="flag" type="file" id="flag" />(Size 16px X 11px)
  <?php echo $this->upload->display_errors('<div class="error">', '</div>');?>
<input type="hidden" name="flag_old" value="<?php echo $data_country->country_flag;?>" />
  </td>
</tr>

<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Update" /></td>
</tr>
</table>
</form>
<?php } ?>

 </div>
    <div class="clear"></div>
</div>
