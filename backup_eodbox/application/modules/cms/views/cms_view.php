<h1></h1><div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; CMS  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View Content Management System  </h2>
    <div class="mid_frm">
 <?php if(count($lang_details)!=1){?>
		  <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin:10px 0 10px 0;">
		  <ul id="vList">		   
           <li>[ <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/cms/add/<?php echo $lang_id;?>">Add CMS</a> ]</li>
           
		   <?php
		  		foreach($lang_details as $lang)
				{
		?>
		  <li>[ <?php if($lang_id != $lang->id){?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/cms/index/<?php echo $lang->id;?>"><?php }?><?php echo $lang->lang_name;?></a> ]</li>
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
  	<th align="left">Page</th>
    <th align="left">Slug</th>
    <th width="20%" align="center">Last Update</th>
    <th width="10%" colspan="2" align="center" style="border-right:none;"><div align="center">Options</div></th>
  </tr>
 	
 <?php
if($cms_data)
   {
   foreach($cms_data as $value)
   { 
 ?>
 
  <tr> 
    <td align="left"><?php echo $value['heading'];?></td>
    <td align="left"><?php echo $value['cms_slug'];?></td>
    <td align="center">

        <?php print($this->general->convert_local_time($value["last_update"]));?>
      </td>
    <td  align="center">	
		<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/cms/edit/<?php echo $value['parent_id'];?>/<?php echo $value['lang_id'];?>">
      		<img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/edit.gif' title="Edit">
   		</a>        
        <?php if($value['added_by']=='admin'){?>
        <a style="margin-left:5px;" href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/cms/delete/<?php echo $value['id'];?>/<?php echo $value['lang_id'];?>">
      <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif" title="Delete" onclick="return doconfirm();"></a>
      <?php }?>
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
	