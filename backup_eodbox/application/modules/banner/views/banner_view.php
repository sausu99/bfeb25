<script type="text/javascript">
function doconfirm()
{
	job=confirm("Are you still want to delete permanently?");
	if(job!=true)
	{
		return false;
	}
}
</script>
<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Banner  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View Banner Details </h2>
    <div class="mid_frm">
    
     
		  <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin:10px 0 10px 0;">
		  <strong>[ <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/banner/add_banner">Add Banner </a> ]</strong>
		  </div>
          
          <?php if(count($lang_details)!=1){?>
		  <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin:10px 0 10px 0;">
		  <ul id="vList">		   
		   <?php
		  		foreach($lang_details as $lang)
				{
		?>
		  <li>[ <?php if($lang_id != $lang->id){?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/banner/index/<?php echo $lang->id;?>"><?php }?><?php echo $lang->lang_name;?></a> ]</li>
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
  <th align="center">Banner</th>
    <th align="center">Is Display? </th>
    <th align="center">Last Update </th>
    <th width="10" align="center"><div align="center">Edit</div></th>
    <th width="10" align="center" style="border-right:none;"><div align="center">Delete</div></th>
	</tr>
	<?php 
			if($result_data)
			{
				foreach($result_data as $data)
				{
	?>
  <tr> 
    <td align="left"><div align="center"><img src="<?php echo base_url(BANNER_PATH).$data->banner;?>" width="400" height="100"></div></td>
    <td align="center"><?php print($data->is_display);?></td>
    <td align="center">
     <?php echo $this->general->convert_local_time($data->last_update); ?>
    </td>
    <td align="center"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/banner/edit_banner/<?php print($data->id);?>/<?php print($data->lang_id);?>">
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/edit.gif' title="Edit"></a> </td>
    <td align="center" style="border-right:none;"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/banner/delete_banner/<?php print($data->id);?>"> 
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif' title="Delete" onClick="return doconfirm();" ></a>    </td>
  </tr>
  <?php
  				}
			}
			else
			{
  ?>
   <tr> 
    <td colspan="4" align="center" style="border-right:none;"> (0) Zero Record Found </td>
    </tr>
  <?php
  			}
  ?>
</table>
</div>
    <div class="clear"></div>
</div>
