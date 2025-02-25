<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; CMS  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>Add Content Management System  </h2>
    <div class="mid_frm">
	


     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>
    
<?php
//print_r($cms_data);
?>
<form name="sitesetting" method="post" action="" enctype="multipart/form-data" method="post" accept-charset="utf-8">


<table align=left cellpadding=2 cellspacing=5 width=99% border="0" class="light">


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

<tr>
      <td width="660"><strong>Heading</strong></td>
</tr>

<tr>
<td><input size="50" class="inputtext" type="text" id="headtext" name="headtext" value="<?php echo set_value('headtext');?>">
<?=form_error('headtext')?></td>
</tr>

<tr>
      <td width="660"><strong>CMS Slug</strong></td>
</tr>

<tr>
<td><input size="50" class="inputtext" type="text" id="cms_slug" name="cms_slug" value="<?php echo set_value('cms_slug');?>">(Ex.:about-us <strong>or</strong> how-it-works)
<?=form_error('cms_slug')?></td>
</tr>


<tr>
      <td><strong>Content</strong></td>
</tr>

<tr>
<td>
		<?php
			 if($this->input->post('content')){$content_data=$this->input->post('content');}else{$content_data='';}
			echo form_fckeditor('content',$content_data);
		?>
		<?=form_error('content')?>
		</td>
</tr>
                 <tr>
                           <td> <label>Select Video File</label></td>
                           </tr>
<tr>
                              <td>  <input type="file" name="video_file" id="video_file">
                              <br>
                              <?php if(isset($vid_error)){
                                ?>
                                <div class="error">

                                <?php echo $vid_error; ?></div>
                                <?php 
                              }
                                ?>

                                </td>
                   
</tr>



<tr>
      <td width="660"><strong>Page Title</strong></td>
</tr>

<tr>
<td><textarea cols="60" rows="1" id="page_title" name="page_title"><?php echo set_value('page_title');?></textarea>
<?=form_error('page_title')?>
</td>
</tr>
<tr>
      <td width="660"><strong>Meta Key</strong></td>
</tr>

<tr>
<td>
<textarea cols="60" rows="2" id="meta_key" name="meta_key"><?php echo set_value('meta_key');?></textarea>
<?=form_error('meta_key')?>
</td>
</tr>



<tr>
      <td width="660"><strong>Meta Description</strong></td>
</tr>

<tr>
<td>
<textarea cols="60" rows="2" id="meta_description" name="meta_description"><?php echo set_value('meta_description');?></textarea>
<?=form_error('meta_description')?>
</td>
</tr>





<tr>
      <td ><strong>Display:</strong>
        <input name="status" type="radio" value="Yes" checked="checked" /> Yes
        <input name="status" type="radio" value="No" <?php if($this->input->post('is_display')=='No')echo 'checked';?> /> No
      <br />      
	  <?=form_error('status')?>
	  </td>
</tr>

<tr height=25 valign="middle">
  <td>&nbsp;</td>
</tr>
<tr height=25 valign="middle">
<td align="center">
<input type="submit" value="Submit" class="bttn">

</td>
</tr>
</table>
	 	

</form>

 </div>
    <div class="clear"></div>
</div>
