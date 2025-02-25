<script type="text/javascript">
function doconfirm()
{
	job=confirm("Are you sure to delete permanently?");
	if(job!=true)
	{
		return false;
	}
}

function cancel()
{
	job=confirm("Are you sure to cancel this auction?");
	if(job!=true)
	{
		return false;
	}
}
</script>

<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Auction  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View <?php $check = $this->uri->segment(4); if($check) echo $this->uri->segment(4); else echo 'Live';?> Auction Details </h2>
    <div class="mid_frm">
    <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin:10px 0 20px 0;">
      <table width="100%">
        <tr>
          <td><strong>[ <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/add_auction">Add Auction </a> ]</strong></td>
          <td><strong>[ <?php if($this->uri->segment(4) != 'Live'){?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/index/Live"> <?php }?>Live Auction</a> ]</strong></td>
          <td><strong>[ <?php if($this->uri->segment(4) != 'Closed'){?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/index/Closed"><?php }?>Closed Auction</a> ]</strong></td>
		  <td><strong>[ <?php if($this->uri->segment(4) != 'Cancel'){?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/index/Cancel"><?php }?>Cancel Auction</a> ]</strong></td>
          <td>&nbsp;</td>
          
          <td width="300">&nbsp;</td>
          
        </tr>
      </table>
	  </div>
    <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin-bottom:10px;">
  <form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="120"><strong>Search by Category</strong></td>
    <td width="100">
        <select name="cat_id">
        <option value="">Select Category</option>
               <?php  if($this->general->get_all_categories_display(DEFAULT_LANG_ID)){
         foreach($this->general->get_all_categories_display(DEFAULT_LANG_ID) as $pcat){?>

       <option value="<?php echo $pcat->parent_id;?>" <?php echo set_select('cat_id',$pcat->parent_id);?>><?php echo $pcat->name;?></option>
 
  <?php }
         }?>
      </select>
<?=form_error('cat_id')?>
    </td>
    <td width="150">&nbsp;&nbsp;<strong>Auction Name: </strong></td>
    <td width="150"><input name="srch" type="text" id="srch" size="30" /></td>
    <td><input type="submit" name="Submit" value="Search Auction" /></td>
  </tr>
</table>
  </form>
</div>
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>

<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
  <tr> 
  <th align="center" width="10">Id</th>
    <th align="left">Name</th>
    <th align="center" width="115">Start Date </th>
    <th align="center" width="125">End Date </th>
   
    <th align="center" width="60">Bid Placed </th>
    
    <th align="center" width="60">Is Display?</th>
	 <th align="center" style="border-right:none;" width="100"><div align="center">Options</div></th>
    </tr>
	<?php 
			if($result_data)
			{
				foreach($result_data as $data)
				{
				
				$lang_inf = $this->general->get_lang_info($data->lang_id);				
	?>
  <tr> 
    <td align="left"><div align="center"><?php print($data->id);?></div></td>
    <td align="left">
	<?php // if($this->uri->segment(4) == 'Live'){?>
	<!--<a href="<?php //echo $this->general->change_lang_uri($lang_inf['short_code'],'auctions/preview/index/'.$data->product_id);?>" target="_blank">-->
	<?php // }else if($this->uri->segment(4) == 'Closed'){?>
	<!--<a href="<?php //echo $this->general->change_lang_uri($lang_inf['short_code'],'auctions/closed/'.$this->general->clean_url($data->name).'-'.$data->product_id);?>" target="_blank">-->
	<?php //}?>
	<?php print($data->name);?>
        <!--</a>-->
    </td>
    <td align="left">
    <div align="center">
      <?php echo $this->general->convert_local_time($data->start_date); ?>
    </div>
      
    </td>
    <td align="left"><div align="center">
      <?php echo $this->general->convert_local_time($data->end_date); ?>
    </div></td>
    <td align="left"><div align="center"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/bids/<?php print($data->id);?>"><?php print($this->admin_auction->get_bid_palce_byid($data->product_id));?></a></div></td>
    <td align="center"><?php print($data->is_display);?></td>
	 <td align="center" style="border-right:none;">
	 <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/copy_auction/<?php print($data->id);?>" style="margin-right:5px;">
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/copy.png' title="Copy Auction" alt="Copy Auction" width="16" height="16"></a>
	  
	 <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/edit_auction/<?php print($data->id);?>" style="margin-right:5px;">
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/edit.gif' title="Edit Auction" alt="Edit Auction" width="16" height="16"></a>   
	  
	  <a  style="margin-left:5px;" href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/delete_auction/<?php print($data->id);?>">
      <img  src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif' title="Delete  Auction" alt="Delete  Auction" onClick="return doconfirm();" width="16" height="16" ></a>    
	  <?php if($data->status == 'Live'){?>
	  
	  <a  style="margin-left:5px;" href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/cancel_auction/<?php print($data->id);?>/<?php print($data->product_id);?>">
      <img  src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/cancel.png' title="Cancel Auction" alt="Cancel Auction" onClick="return cancel();" width="50" height="16" ></a>
	  
	  <?php }?>
	  </td>
    </tr>
  <?php
  				}
				//if($this->pagination->create_links())
				//{
  ?>
   <tr> 
    <td colspan="7" align="center" style="border-right:none;" class="paging"><?php echo $this->pagination->create_links();?></td>
    </tr>
  <?php
  				//}
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
