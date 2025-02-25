<script type="text/javascript">
function doconfirm()
{
	job=confirm("Are you sure to delete permanently?");
	if(job!=true)
	{
		return false;
	}
}
</script>

<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; FAQ's  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View FAQ's  Details </h2>
    <div class="mid_frm">
    
      <table width="100%">
        <tr>
          <td>
		  <ul id="vList">
		   <li>[ <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction-faq/add">Add FAQ's</a> ]</li>
		   <?php
		   if(count($lang_details) !=1){
		  		foreach($lang_details as $lang)
				{
		?>
		  <li>[ <?php if($lang_id != $lang->id){?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/help/index/<?php echo $lang->id;?>"><?php }?><?php echo $lang->lang_name;?></a> ]</li>
		 <?php
		 		}}
		?>
		  </ul>
		  </td>
        </tr>
      </table>
    
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>

<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
  <tr> 
  <th width="40%" align="left"> Title </th>
    <th align="center">Last Update </th>
    <th align="center">Is Display?</th>
    
    <th colspan="2" align="center" style="border-right:none;"><div align="center">Options</div></th>
    </tr>
	<?php 
			if($result_data)
			{
				foreach($result_data as $data)
				{
	?>
  <tr> 
    <td align="left"><?php print($data->title);?></td>
    <td align="left"><div align="center"><?php print($this->general->date_time_formate($data->last_update));?></div></td>
    <td align="center"><?php print($data->is_display);?></td>
    <td colspan="2" align="center" style="border-right:none;">
	<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction-faq/edit/<?php print($lang_id);?>/<?php print($data->id);?>" style="margin-right:5px;">
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/edit.gif' title="Edit"></a>   <a  style="margin-left:5px;" href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction-faq/delete/<?php print($lang_id);?>/<?php print($data->id);?>">
      <img  src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif' title="Delete" onClick="return doconfirm();" ></a>    </td>
    </tr>
  <?php
  				}
				
			}
			else
			{
  ?>
   <tr> 
    <td colspan="5" align="center" style="border-right:none;"> (0) Zero Record Found </td>
    </tr>
  <?php
  			}
  ?>
</table>
</div>
    <div class="clear"></div>
</div>
