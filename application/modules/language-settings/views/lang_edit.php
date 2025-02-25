<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Language  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Language Settings </h2>
    <div class="mid_frm">

    
<?php
//print_r($site_set);
?>
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">

<table width=90% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
  <td class="hmenu_font">Language Name </td>
  <td><input name="lang_name" type="text" class="inputtext" id="lang_name" value="<?php echo set_value('lang_name',$data_lang->lang_name);?>" size="20" />
      <?=form_error('lang_name')?></td>
  </tr>
<?php /*?><tr>
  <td class="hmenu_font">Language Short Code </td>
  <td><input name="short_code" type="text" class="inputtext" id="short_code" value="<?php echo set_value('short_code',$data_lang->short_code);?>" size="10" />
    <?=form_error('short_code')?></td>
</tr>
<tr>
  <td class="hmenu_font">Currency Code </td>
  <td><input name="currency_code" type="text" class="inputtext" id="currency_code" value="<?php echo set_value('currency_code',$data_lang->currency_code);?>" size="10" />
    <?=form_error('currency_code')?></td>
</tr>
<tr>
  <td class="hmenu_font">Currency Sign </td>
  <td><input name="currency_sign" type="text" class="inputtext" id="currency_sign" value="<?php echo set_value('currency_sign',$data_lang->currency_sign);?>" size="10" />
    <?=form_error('currency_sign')?></td>
</tr>
<tr>
  <td class="hmenu_font">Exchange Rate </td>
  <td>
  <input name="exchange_rate" type="text" class="inputtext" id="exchange_rate" value="<?php echo set_value('exchange_rate',$data_lang->exchange_rate);?>" size="10" />
    <?=form_error('exchange_rate')?>
	</td>
</tr>
<tr>
<td width=229 class="hmenu_font">Language Flag </td>
<td width="429">
<input name="flag" type="file" id="flag" />(Size 16px X 11px)
  <?=$this->upload->display_errors('<div class="error">', '</div>');?>
<input type="hidden" name="flag_old" value="<?php echo $data_lang->lang_flag;?>" />
  </td>
</tr><?php */?>
<?php if($data_lang->default_lang == 'No'){?>
<tr>
  <td class="hmenu_font">Is Display?</td>
  <td>
  <input name="is_display" type="radio" value="Yes" checked="checked" />
    Yes
      <input name="is_display" type="radio" value="No" <?php if($data_lang->is_display == 'No'){ echo 'checked="checked"';}?> />
      No
  </td>
</tr>
<?php }else{?>
<input type="hidden" name="is_display" value="Yes" />
<?php }?>
<?php /*?><tr>
  <td class="hmenu_font">Display In</td>
  <td>
  <input name="display_in" type="radio" value="Left" checked="checked" />
    Left Side
      <input name="display_in" type="radio" value="Right" <?php if($data_lang->display_in == 'Right'){ echo 'checked="checked"';}?> />
      Right Side
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


