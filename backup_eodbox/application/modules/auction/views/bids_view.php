<script type="text/javascript">
zfunction doconfirm()
{
	job=confirm("Are you sure to delete permanently?");
	if(job!=true)
	{
		return false;
	}
}
</script>

<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; <a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>/auction/index">Auction  Management </a> &raquo; Bids Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View  Auction's Bids Details </h2>
    <div class="mid_frm">
    
      <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin-bottom:20px;">
	  
      <table width="100%" cellpadding="0" cellspacing="4">
        <tr>
          <td width="10%" valign="top" style="height:20px;"><strong>Product Id </strong></td>
          <td width="20%" valign="top"><?php echo $auc_data->product_id;?></td>
          <td width="15%" valign="top"><strong>Auction Name </strong></td>
          <td valign="top"><?php echo $auc_name;?></td>
        </tr>
        <tr>
          <td valign="bottom"><strong>Bid Fee </strong></td>
          <td valign="bottom"><?php echo $auc_data->bid_fee;?></td>
          <td valign="bottom"><strong>Total Bid Placed </strong></td>
          <td valign="bottom"><?php echo $total_bids;?></td>
        </tr>
      </table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
  <tr> 
  <th align="center"><div align="left">Member Name</div></th>
    <th align="center">Auction Price </th>
    <th align="center">Bid Status </th>
    <th align="center">IP Address </th>
   
    <th colspan="2" align="center">Date</th>
    </tr>
    <div id="ubh">
  <?php 
  
          $current_winner_amt = $this->general->get_winner_amt($auc_data->product_id);
  
      if($bids)
      {
        foreach($bids as $data)
        {
          
  ?>
  <tr> 
    <td align="left">
    
    
  <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/members/edit_member/active/<?php print($data->user_id);?>">
  <?php echo ($data->user_name)?$data->user_name:$data->first_name.' '.$data->last_name;?>
    </a>
    </td> 
    <td align="left"><div align="center"><?php print($data->userbid_bid_amt);?></div></td>
<?php /*?>    <td align="left"><div align="center"><?php print($data->type);?></div></td><?php */?>
  
    <td align="center"><?php 
  //$this->bidstatus=$this->general->getBidStatus1($data->userbid_bid_amt, $data->auc_id);
                  if($data->freq > 1)
          {
          echo "<font color='red'>Not Unique</font>";
          }
          else if($data->freq == 1 && $current_winner_amt == $data->userbid_bid_amt)
          {
          echo "<font color='green'>Lowest Unique Bid</font>";
          }
          else if($data->freq == 1)
          {
          echo "<font color='blue'>Unique But Not Lowest</font>";
          }
  ?></td>
    <td colspan="2" align="left"><div align="center">
      <?php echo  $data->ip_address; ?>

    </div></td>
    
    <td colspan="2" align="left"><div align="center">
      <?php echo  $this->general->convert_local_time($data->bid_date); ?>

    </div></td>
    </tr>
  <?php
          }
        
        //if($this->pagination->create_links())
        //{
  ?>
   <tr> 
    <td colspan="5" align="center" style="border-right:none;" class="paging"><?php echo $this->pagination->create_links();?></td>
    </tr>
  <?php
          //}
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
  </div>
</table>
</div>
    <div class="clear"></div>
</div>
