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
<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Vouchers Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View vouchers Details </h2>
    <div class="mid_frm">
    
      <table width="100%">
        <tr>
          <td><strong>[ <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/vouchers/add">Add Vouchers </a> ]</strong></td>
        </tr>
      </table>
    
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>

<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
  <tr> 
    <th align="left">Vouchers Code</th>
    <th align="left">Limit</th>
    <th align="left">Limit per User</th>
    <?php /*?><th align="left">Free Bids</th><?php */?>
    <th align="left">Extra Bids</th>
    <th align="left">Start Date</th>
    <th align="left">End Date</th>
    <th width="10" align="center"><div align="center">Edit</div></th>
    <th width="10" align="center" style="border-right:none;"><div align="center">Delete</div></th>
	</tr>
	<?php 		
		
			if($result_data)
			{
				foreach($result_data as $val)
				{
	?>
  <tr> 
    <td align="left"><?php print($val->code);?></td>
    <td align="left"><?php print($val->limit_number);?></td>
    <td align="left"><?php print($val->limit_per_user);?></td>
    <?php /*?><td align="left"><?php echo ($val->free_bids)?$val->free_bids:'---';?></td><?php */?>
    <td align="left"><?php echo ($val->extra_bids)?$val->extra_bids.'%':'---';?></td>
    <td align="left"><?php print($val->start_date);?></td>
    <td align="left"><?php print($val->end_date);?></td>
    <td align="center"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/vouchers/edit/<?php print($val->id);?>">
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/edit.gif' title="Edit"></a> </td>
    <td align="center" style="border-right:none;"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/vouchers/delete/<?php print($val->id);?>"> 
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif' title="Delete" onClick="return doconfirm();" ></a>    </td>
  </tr>
  <?php
  				}
			}
			else
			{
  ?>
   <tr> 
    <td colspan="9" align="center" style="border-right:none;"> (0) Zero Record Found </td>
    </tr>
  <?php
  			}
  ?>
</table>
</div>
    <div class="clear"></div>
</div>
