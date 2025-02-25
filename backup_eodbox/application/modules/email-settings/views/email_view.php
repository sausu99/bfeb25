<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Email Settings Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>Update Email Settings </h2>
    <div class="mid_frm">
	

 <?php if(count($lang_details)!=1){?>
		  <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin:10px 0 10px 0;">
		  <ul id="vList">		   
		   <?php
		  		foreach($lang_details as $lang)
				{
		?>
		  <li>[ <?php if($lang_id != $lang->id){?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/email-settings/index/<?php echo $lang->id;?>"><?php }?><?php echo $lang->lang_name;?></a> ]</li>
		 <?php
		 		}
		?>
		  </ul>
		  <div style="clear:both"></div>
		  </div>
          <?php }?>
      

     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>
    
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
  <tr> 
  	<th width="50%" align="left">Subject</th>
    <?php /*?><th width="40%" align="left"><?php echo lang('email_setting_label_subject');?></th><?php */?>
    <th align="center" width="25%">Last Update</th>
    <th  width="25%" colspan="2" align="center" style="border-right:none;"><div align="center">Option</div></th>
  </tr>
 	
 <?php
if($email_data)
   {
   foreach($email_data as $value)
   { 
 ?>
 
  <tr> 
    <td width="50%" align="left"><?php echo $value['subject'];?></td>
    <?php /*?><td align="left"><?php echo $value['subject'];?></td><?php */?>
    <td width="25%" align="center">

        <?php print($this->general->convert_local_time($value["last_update"]));?>
      </td>
    <td width="25%"  align="center">
	
		<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/email-settings/edit/<?php echo $value['email_code'];?>/<?php echo $value['lang_id'];?>">
      		<img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/edit.gif' title="Edit">
   		</a>   
	</td>
  </tr>
	<?php
	}
	}else{
	?> 
 <tr>
      <td colspan="5" align="center" style="border-right:none;"> (0) Zero Record Found </td>
    </tr>

<?php
    }?>
</table>

</div>
    <div class="clear"></div>
</div>
