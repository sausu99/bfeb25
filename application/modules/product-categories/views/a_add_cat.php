<script src="<?php print(ADMIN_JS_DIR_FULL_PATH);?>tabcontent/tabcontent.js" type="text/javascript"></script>
<link href="<?php print(ADMIN_JS_DIR_FULL_PATH);?>tabcontent/tabcontent.css" rel="stylesheet" type="text/css" />
<script>var FilesFolderPath = "<?php echo ASSETS_CALENDER;?>";</script>
<script src="<?php print(ASSETS_CALENDER);?>Scripts/DateTimePicker.js" type="text/javascript"></script>


<div class="content">

  <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Categories  Management </span></div>
  <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
      <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
    <h2><?php echo $jobs;?> Category </h2>
    <div class="mid_frm">
  <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
    echo "<div class='message'>".$this->session->flashdata('message')."</div>";
    }
  ?></div>
  
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">

<table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">
<?php if(count($lang_details)!=1){?>
<tr>
      <td width="660"><strong>Language</strong></td>
</tr>

<tr>
<td>
<select name="lang" id="lang">
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
      <td width="328"><strong>Category Name </strong></td>
</tr>
<tr>
<td>
      <input type="text" size="50" cols="60" rows="2" name="name" value="<?php echo set_value('name'); ?>">
      <?=form_error('name')?>
     </td>
</tr>
<tr>
<td class="hmenu_font"><strong>Short Description</strong></td>
<tr>
<td>
              <textarea name="short_desc" cols="50" rows="2" id="short_desc"><?php echo set_value('short_desc');?></textarea>
                    <?=form_error('short_desc')?>
               </td>
</tr>

<tr>
<td><strong>Image</strong><small> (Recommended Size 120px X 80px)</small></td>
</tr>
<tr>
<td><input type="file" name="img">
<div class="error"><?=$this->session->userdata('error_img');?></div>
</td>
</tr>


<tr>
<td><strong>Is Display?</strong></td>
</tr>
<tr>
<td><input name="is_display" type="radio" value="Y" checked="checked" />
    Yes
      <input name="is_display" type="radio" value="N"  />
      No 
      <?=form_error('is_display') ?>
    </td>
</tr>

<tr>
<td><strong>Is Home?</strong></td>
</tr>
<tr>
<td><input name="is_home" type="radio" value="1" checked="checked" />
    Yes
      <input name="is_home" type="radio" value="0"  />
      No 
      <?=form_error('is_home') ?>
    </td>
</tr>

<tr height="30">
  <td><input class="bttn" type="submit" name="Submit" value="Add" /></td>
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

