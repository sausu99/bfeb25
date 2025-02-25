<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Banner  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Banner </h2>
    <div class="mid_frm">

    
<?php
//print_r($site_set);
?>
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">

<table width=90% align=center border=0 cellspacing=4 cellpadding=2 class="light">
<?php if(count($lang_details)==1){?>
<input type="hidden" name="lang" value="<?php echo $lang_details[0]->id?>" />
<?php }else{?>
<tr>
  <td class="hmenu_font">Language<span class="error">*</span></td>
  <td>
  <select name="lang" onchange="redirect_lang_cms()" id="lang" style="width:142px; padding:2px 0px 2px 5px;">
	<option value="">Select Language</option>
	<?php foreach($lang_details as $ln_data){?>
	<option value="<?php echo $ln_data->id;?>" <?php echo set_select('lang', $ln_data->id); ?> ><?php echo $ln_data->lang_name;?></option>
	<?php }?>
</select>
<?=form_error('lang')?>

  </td>
</tr>
<?php }?>
<tr>
<td class="hmenu_font">Banner Location<span class="error">*</span></td>
<td>
 <select name="type" style="width:142px; padding:2px 0px 2px 5px;">
 
  
  <option value="home">Home</option>
  <option value="tv_show">Tv show</option>

</select>
</td>
</tr>
<tr>
  <td class="hmenu_font">URL</td>
  <td><input type="text" name="url1" id="url1" />(Ex.: http://www.example.com)</td>
</tr>
<tr>
<td class="hmenu_font">Banner Image1<span class="error">*</span> </td>
<td width="429"><input name="img1" type="file" id="img1" />
(Size 1920px X 590px)
  <div class="error"><?=$this->session->userdata('error_img1');?></div>
</td>
</tr>
<tr>
  <td class="hmenu_font">URL</td>
  <td><input type="text" name="url2" id="url2" />(Ex.: http://www.example.com)</td>
</tr>
<tr>
<td class="hmenu_font">Banner Image2</td>
<td width="429"><input name="img2" type="file" id="img2" />
(Size 1920px X 590px)
  <div class="error"><?=$this->session->userdata('error_img2');?></div>
 </td>
</tr>
<tr>
  <td class="hmenu_font">URL</td>
  <td><input type="text" name="url3" id="url3" />(Ex.: http://www.example.com)</td>
</tr>
<tr>
<td class="hmenu_font">Banner Image3</td>
<td width="429"><input name="img3" type="file" id="img3" />
(Size 1920px X 590px)
  <div class="error"><?=$this->session->userdata('error_img3');?></div>
</td>
</tr>
<tr>
  <td class="hmenu_font">URL</td>
  <td><input type="text" name="url4" id="url4" />(Ex.: http://www.example.com)</td>
</tr>
<tr>
<td class="hmenu_font">Banner Image4</td>
<td width="429"><input name="img4" type="file" id="img4" />
(Size 1920px X 590px)
  <div class="error"><?=$this->session->userdata('error_img4');?></div>
</td>
</tr>

<?php /*?><tr>
  <td class="hmenu_font">Is Display?</td>
  <td>
  <input name="is_display" type="radio" value="Yes" checked="checked" />
    Yes
      <input name="is_display" type="radio" value="No" <?php echo set_radio('is_display', 'No'); ?> />
      No  </td>
</tr>
<?php */?>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Submit" /></td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>


