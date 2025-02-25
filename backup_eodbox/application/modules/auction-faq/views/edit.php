<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Auction Faq's Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Auction Faq's Category </h2>
    <div class="mid_frm">


<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">
<input name="id" type="hidden" class="inputtext" id="id" value="<?php echo $data_help->id;?>" size="15" />
<table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">



<tr>
  <td class="hmenu_font">Choose Auction</td>
  <td>
  <?php if($this->input->post('help_category'))$help_category_id=$this->input->post('help_category'); else $help_category_id = $data_help->auc_id; ?>
  <select name="help_category">
  <option value="" selected="selected">Choose Auction</option>
  <?php foreach($this->admin_faq->get_auction_bylangid($lang_id) as $help_cat){?>
  <option value="<?php echo $help_cat->id;?>" <?php if($help_category_id == $help_cat->id ) {echo 'selected="selected"';} ?>><?php echo $help_cat->name;?></option>
  <?php }?>
  </select>  </td>
</tr>
<tr>
  <td width="229" class="hmenu_font">Title </td>
  <td width="429"><input name="name" type="text" class="inputtext" id="name" value="<?php echo set_value('name',$data_help->title);?>" size="25" />
      <?=form_error('name')?></td>
  </tr>


<tr>
  <td class="hmenu_font">Is Display?</td>
  <td>
  <input name="is_display" type="radio" value="Yes" checked="checked" />
    Yes
      <input name="is_display" type="radio" value="No" <?php if($data_help->is_display == 'No'){ echo 'checked="checked"';}?> />
      No  </td>
</tr>

<tr height="30">
  <td colspan="2"><strong>Description</strong></td>
</tr>
<tr height="30">
  <td colspan="2">
  <?php
			 if($this->input->post('description')){$content_data=$this->input->post('description');}else{$content_data=$data_help->description;}
			echo form_fckeditor('description',$content_data);			
		?>
<?=form_error('description')?>

  </td>
</tr>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Update" />  </td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>
