<script src="<?php echo base_url(ADMIN_JS_DIR_FULL_PATH);?>/tabcontent/tabcontent.js" type="text/javascript"></script>
<link href="<?php echo base_url(ADMIN_JS_DIR_FULL_PATH);?>/tabcontent/tabcontent.css" rel="stylesheet" type="text/css" />
<script>var FilesFolderPath = "<?php echo ASSETS_CALENDER;?>";</script>
<script src="<?php echo base_url(ASSETS_CALENDER);?>Scripts/DateTimePicker.js" type="text/javascript"></script>
<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Help Category Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Help Category </h2>
    <div class="mid_frm">

    
<?php
//print_r($error);
?>
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">

<table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">
<?php if(count($lang_details)==1){?>
<input type="hidden" name="lang_id[]" value="<?php echo $lang_details[0]->id;?>" />
<?php }else {?>
<tr>
  <td width="200" class="hmenu_font">Choose Language befor Post In </td>
  <td width="429">
  <?php for($i=0; $i<count($lang_details); $i++){?>
  <?php $language_id = ''; $lang = $this->input->post('lang_id'); if(!empty($lang[$i])){ $language_id = $lang[$i]; };?>
  <?php /*?><input type="checkbox" name="lang[<?php echo 'lang_id_'.$lang_details[$i-1]->id;?>]" value="<?php echo $lang_details[$i-1]->id;?>" <?php if($language_id == $lang_details[$i-1]->id) echo 'checked="checked"'; ?> /><?php echo $lang_details[$i-1]->lang_name;?> <?php */?>
  
   <input type="checkbox" name="lang_id[]" value="<?php echo $lang_details[$i]->id;?>" <?php if($language_id == $lang_details[$i]->id) echo 'checked="checked"'; ?> /><?php echo $lang_details[$i]->lang_name;?> 
   
  <?php }?>
  <?php echo form_error('lang_id[]'); ?>  </td>
</tr>
<?php }?>
<tr height="30">
  <td colspan="2">
  
  <div class="" >
<ul id="mytabs" class="shadetabs" <?php if(count($lang_details)==1){ echo 'style="display:none"';}?>>
					  <?php for($i=1; $i<=count($lang_details); $i++){?>
                      <li><a href="JavaScript:void(0);" rel="tab<?php echo $i;?>"><span><?php echo $lang_details[$i-1]->lang_name;?></span></a></li>
					  <?php }?>
                      <li><a href="JavaScript:void(0);" style="background:none;border-bottom: #CCC 1px solid;"><span style="background:none;width:69px;">&nbsp;</span></a></li>
                    </ul> 	
<div class="clearboth"></div>

<?php for($i=1; $i<=count($lang_details); $i++){?>
<div id="tab<?php echo $i;?>" class="tabcontent">
<table align=left cellpadding=2 cellspacing=4 width=99% border="0" class="light">





<tr>
  <td valign="top"><strong>Choose Help Category</strong></td>
  <td>
  <?php
  		$hlep_category = $this->input->post('hlep_category');
  ?>
  <select name="hlep_category[<?php echo $lang_details[$i-1]->id;?>]">
  <option value="" selected="selected">Choose Help Category</option>
  <?php foreach($this->admin_help->get_help_category_byid($lang_details[$i-1]->id) as $help_cat){?>
  <option value="<?php echo $help_cat->id;?>" <?php if(isset($hlep_category) && $hlep_category[$lang_details[$i-1]->id] == $help_cat->id ) {echo 'selected="selected"';} ?>><?php echo $help_cat->help_category_name;?></option>
  <?php }?>
  </select>
  <?php echo '<div class="error">'.$this->session->userdata('hlep_category_'.$lang_details[$i-1]->id)."</div>"; ?>
  </td>
</tr>
<tr>
      <td width="288" valign="top"><strong>Help Title</strong></td>
      <td width="838">
	  <?php $auc_name = ''; $name = $this->input->post('name'); if(!empty($name[$lang_details[$i-1]->id])){ $auc_name = $name[$lang_details[$i-1]->id]; };?>
<input size="50" class="inputtext" type="text" id="name[<?php echo $lang_details[$i-1]->id;?>]" name="name[<?php echo $lang_details[$i-1]->id;?>]" value="<?php echo $auc_name; ?>">
<?php echo '<div class="error">'.$this->session->userdata('name_'.$lang_details[$i-1]->id)."</div>"; ?>	  </td>
</tr>

<tr>
<td valign="top"><strong>Is Display?</strong></td>
<td><input name="is_display[<?php echo $lang_details[$i-1]->id;?>]" type="radio" value="Yes" checked="checked" />
    Yes
      <input name="is_display[<?php echo $lang_details[$i-1]->id;?>]" type="radio" value="No" <?php if(isset($_POST['is_display'][$lang_details[$i-1]->id]) && $_POST['is_display'][$lang_details[$i-1]->id] == 'No'){ echo 'checked="checked"';}?> />
      No  </td>
</tr>






<tr height=25 valign="middle">
  <td colspan="2"><strong>Description</strong></td>
</tr>
<tr height=25 valign="middle">
  <td colspan="2">
		<?php $description = ''; $description2 = $this->input->post('description'); if(!empty($description2[$lang_details[$i-1]->id])){ $description = $description2[$lang_details[$i-1]->id]; };?>
		<?php echo form_fckeditor('description['.$lang_details[$i-1]->id.']', $description );	?>
		<?php echo '<div class="error">'.$this->session->userdata('description_'.$lang_details[$i-1]->id)."</div>"; ?>
		
		
		</td>
		
</tr>
</table>
<div class="clearboth"></div>
</div>

 <?php }?>
</div>  </td>
  </tr>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Update" /></td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>

<script>
					var mytabs_obj=new ddtabcontent("mytabs")
					mytabs_obj.setpersist(true)
					mytabs_obj.setselectedClassTarget("link") //"link" or "linkparent"
					mytabs_obj.init()
					</script> 	