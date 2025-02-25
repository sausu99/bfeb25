<script type="text/javascript">
function doconfirm()
{
	job=confirm("This Language is used in different place like products,.. Are you still want to delete permanently?");
	if(job!=true)
	{
		return false;
	}
}
</script>
<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Language  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View Language Details </h2>
    <div class="mid_frm">
    
    <table width="100%">
        <tr>
          <td>
		  <ul id="vList">
		   <li>[ <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/language-settings/add_lang">Add Language</a> ]</li>
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
 <?php /*?> <th align="center">Flag</th><?php */?>
    <th width="80%" align="left">Name</th>
   <?php /*?> <th align="center">Short Code </th>
    <th align="center">Currency Code </th>
   
    <th align="center">Currency Sign </th>
    
    <th align="center">Exchange Rate </th><?php */?>
	 <th align="center">Is Display? </th>
    <th width="10" align="center"><div align="center">Edit</div></th>
    <th width="10" align="center" style="border-right:none;"><div align="center">Delete</div></th>
	</tr>
	<?php 
			if($lang_details)
			{
				foreach($lang_details as $lang_val)
				{
	?>
  <tr> 
  <?php /*?>  <td align="left"><div align="center"><img src='<?php echo base_url().$lang_val->lang_flag;?>' title="<?php print($lang_val->lang_name);?>"></div></td><?php */?>
    <td align="left"><?php print($lang_val->lang_name);?></td>
    <?php /*?><td align="left"><div align="center"><?php print($lang_val->short_code);?></div></td>
    <td align="left"><div align="center"><?php print($lang_val->currency_code);?></div></td>
    <td align="left"><div align="center"><?php print($lang_val->currency_sign);?></div></td>
    <td align="center"><?php print($lang_val->exchange_rate);?></td><?php */?>
	 <td align="center"><?php print($lang_val->is_display);?></td>
    <td align="center"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/language-settings/edit_lang/<?php print($lang_val->id);?>">
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/edit.gif' title="Edit"></a> </td>
    <td align="center" style="border-right:none;"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/language-settings/delete_lang/<?php print($lang_val->id);?>"> 
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif' title="Delete" onClick="return doconfirm();" ></a>    </td>
  </tr>
  <?php
  				}
			}
			else
			{
  ?>
   <tr> 
    <td colspan="8" align="center" style="border-right:none;"> (0) Zero Record Found </td>
    </tr>
  <?php
  			}
  ?>
</table>
</div>
    <div class="clear"></div>
</div>
