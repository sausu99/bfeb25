<h1><?php echo $this->lang->line('account_watch_list');?></h1>
<div class="acc-lt">
<div class="bidhis-area">
<table class="footable">
<thead>
<tr>
      				<th data-hide="phone,tablet"><?php echo $this->lang->line('won_auction_image');?></th>
      				<th data-class="expand" ><?php echo $this->lang->line('watch_auction');?></th>
      				<th data-hide="phone,tablet"><?php echo $this->lang->line('watch_bids_left');?></th>
      				<th><?php echo $this->lang->line('watch_winner');?></th>
    				</tr>
                    </thead>



<?php
if($live_auc)
{
	foreach($live_auc as $auc_lists)
	{
?>

 <tr>
      <td><img src="<?php print(ROOT_SITE_PATH.AUCTION_IMG_PATH.'thumb_'.$auc_lists->image1);?>" ></td>
      <td><a href="<?php echo $this->general->lang_uri('/auctions/'.$this->general->clean_url($auc_lists->name).'-'.$auc_lists->product_id);?>"><?php echo $auc_lists->name;?></a></td>
      <td><?php echo $auc_lists->no_bids;?></td>
         <td><?php echo $auc_lists->current_winner_name;?></td>
    </tr>


<?php
	}
}
else{
?>
<p align="center"><?php echo $this->lang->line('watch_no_item');?></p>
<?php
	}
?>

</tbody>	  
</table>
</div>

<div class="acc-shadow"></div>
</div>


