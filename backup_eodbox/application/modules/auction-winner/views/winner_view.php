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

<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Penny Winner Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View Penny Winner Details </h2>
<div class="mid_frm">
      <?php if($this->uri->segment(4)) $status = $this->uri->segment(4); else $status = '1'; 	?>
      <table width="100%">
        <tr>
          <td>
		  <strong style="margin-right:10px;">[ <?php if($status!='1'){ echo anchor(ADMIN_DASHBOARD_PATH.'/auction-winner/index', 'Current Winner', 'title="Current Winner"');} else { echo "Current Winner";}?> ]</strong>
		  
		  <strong style="margin-right:10px;">[ <?php if($status!='2'){ echo anchor(ADMIN_DASHBOARD_PATH.'/auction-winner/index/2', 'Shipped  Item', 'title="Shipped Item"');} else { echo "Shipped Item";}?> ]</strong></td>
        </tr>
      </table>
    
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>

<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
  <tr> 
  <th align="left">Auction Name</th>
    <th align="left">Winner</th>
    <th align="center">Sold At </th>
    <th align="center">End Date </th>
   
    <th align="center">Payment Status</th>
	 <th colspan="2" align="center" style="border-right:none;">Option</th>
    </tr>
	<?php 

			if($result_data)
			{
				foreach($result_data as $data)
				{
	?>
  <tr> 
    <td align="left"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/index/Closed"><?php print($data->name);?></a></td>
    <td align="left"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/members/edit_member/active/<?php print($data->user_id);?>">
	<?php echo ($data->current_winner_name)?$data->current_winner_name:$data->first_name.' '.$data->last_name;?></a></td>
    <td align="center"><?php echo $this->general->default_formate_price_currency_sign_admin($data->country,$data->current_winner_amount);?></td>
    <td align="left"><div align="center"><?php print($this->general->date_time_formate($data->end_date));?></div></td>
    <td align="center"><?php print($data->payment_status);?></td>
	 <td colspan="2" align="center" style="border-right:none;"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction-winner/details/<?php print($data->auction_winner_id);?>" title="View Winner Details">Details</a></td>
    </tr>
  <?php
  				}
				//if($this->pagination->create_links())
				{
  ?>
   <tr> 
    <td colspan="7" align="center" style="border-right:none;" class="paging"><?php echo $this->pagination->create_links();?></td>
    </tr>
  <?php
  				}
			}
			else
			{
  ?>
   <tr> 
    <td colspan="7" align="center" style="border-right:none;"> (0) Zero Record Found </td>
    </tr>
  <?php
  			}
  ?>
</table>
</div>
    <div class="clear"></div>
</div>
