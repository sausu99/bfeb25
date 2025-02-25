<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Banner  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Banner </h2>
    <div class="mid_frm">

    
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">
<table width=90% align=center border=0 cellspacing=4 cellpadding=2 class="light">
<?php if(count($lang_details)==1){?>
<input type="hidden" name="lang" value="<?php echo $lang_details[0]->id;?>" />
<?php }else {?>
<tr>
  <td class="hmenu_font">Language<span class="error">*</span></td>
  <td>
  <select name="lang" onchange="redirect_lang_cms()" id="lang" style="width:142px; padding:2px 0px 2px 5px;">
	<option value="">Select Language</option>
	<?php foreach($lang_details as $ln_data){?>
	<option value="<?php echo $ln_data->id;?>" <?php if($ln_data->id == $this->uri->segment(5)){echo 'selected="selected"';}?>><?php echo $ln_data->lang_name;?></option>
	<?php }?>
</select>
<?=form_error('lang')?>

  </td>
</tr>
<?php }?>
<tr>
  <td class="hmenu_font">&nbsp;</td>
  <td> <img src="<?php print(site_url($result_data->banner));?>" width="300" height="100" /></td>
</tr>
<tr>
<td width=229 class="hmenu_font">Banner<span class="error">*</span></td>
<td width="429"><input name="banner" type="file" id="banner" />(Size 900px X 370px)
  <?=$this->upload->display_errors('<div class="error">', '</div>');?></td>
</tr>
<tr>
  <td class="hmenu_font">Is Display?</td>
  <td>
 <input name="is_display" type="radio" value="Yes" checked="checked" />
    Yes
      <input name="is_display" type="radio" value="No" <?php if($result_data->is_display == 'No'){ echo 'checked="checked"';}?> />
      No </td>
</tr>

<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2">
  <input type="hidden" name="old_file" value="<?php echo $result_data->banner;?>" />
  <input class="bttn" type="submit" name="Submit" value="Submit" /></td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>


