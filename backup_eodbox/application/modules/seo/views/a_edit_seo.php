
<div class="content">

  <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  SEO  Management </span></div>
  <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
      <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
    <h2><?php echo $jobs;?> SEO </h2>
    <div class="mid_frm">
  <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
    echo "<div class='message'>".$this->session->flashdata('message')."</div>";
    }
  ?></div>
  
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">

<table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">
<tr>
  <td class="hmenu_font">SEO Page:<label><?php echo $this->general->get_page_name_by_id($page_id); ?></label></td>
  <td>
  <input type="hidden" name="seo_page" value="<?php echo !empty($page_id)?$page_id:''; ?>">
  </td>
  </td>
</tr>

<?php if(count($lang_details)!=1){?>
<tr>
      <td width="660"><strong>Language</strong></td>
</tr>

<tr>
<td>
<select name="lang" id="lang" onchange="redirect_lang_cms()">
  <option value="">Select Language</option>
  <?php foreach($lang_details as $ln_data){?>
  <option value="<?php echo $ln_data->id;?>" <?php if($ln_data->id == $lang_id){echo 'selected="selected"';}?>><?php echo $ln_data->lang_name;?></option>
  <?php }?>
</select>
<?=form_error('lang')?>
</td>
</tr>
<?php }else{?>
<input type="hidden" name="lang" value="<?php echo $lang_details[0]->id;?>" />
<?php }?>

<div class="clearboth"></div>
<table align=left cellpadding=2 cellspacing=4 width=99% border="0" class="light">
<tr>
      <td width="328"><strong>Page Title </strong></td>
</tr>
<tr>
<td>
<?php $page_title_db=!empty($seo_data->page_title)?$seo_data->page_title:'' ?>
   <textarea cols="60" rows="1" id="page_title" name="page_title"><?php echo set_value('page_title')?set_value('page_title'):$page_title_db;?></textarea>
    <?=form_error('page_title')?>
</td>
</tr>
<tr>
<td class="hmenu_font"><strong>Meta Keys</strong></td>
<tr>
<td>
<?php $meta_key_db=!empty($seo_data->meta_key)?$seo_data->meta_key:'' ?>
      <textarea cols="60" rows="2" id="meta_key" name="meta_key"><?php echo set_value('meta_key')?set_value('meta_key'):$meta_key_db;?></textarea>
            <?=form_error('meta_key')?>
               </td>
</tr>

<td class="hmenu_font"><strong>Meta Description</strong></td>
<tr>
<td>
<?php $meta_desc_db=!empty($seo_data->meta_description)?$seo_data->meta_description:'' ?>

      <textarea cols="60" rows="2" id="meta_desc" name="meta_desc"><?php echo set_value('meta_desc')?set_value('meta_desc'):$meta_desc_db;?></textarea>
        <?=form_error('meta_desc')?>
 </td>

 <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>">
</tr>


<tr height="30">
  <td><input class="bttn" type="submit" name="Submit" value="Update" /></td>
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
    document.location.href="<?php echo base_url().ADMIN_DASHBOARD_PATH.'/seo/edit/'.$this->uri->segment(4).'/';?>"+$('#lang').val();
}
</script>
