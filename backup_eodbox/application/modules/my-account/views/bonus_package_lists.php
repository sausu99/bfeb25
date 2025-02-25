<div class="dash-bid-item dashboard-widget mb-40-60">
                        <div class="header">
                            <h4 class="title"><?php echo $this->lang->line('account_bonus_package_lists');?></h4>
                            <p><?php echo lang('bonus'); ?>:  <?php echo $this->general->get_user_bonus($this->session->userdata(SESSION.'user_id'));?> <?php echo lang('points'); ?></p>
                         </div>   
                            <?php
		
    if($this->session->flashdata('error_message'))
      { ?>
          <div role="alert" class="alert alert-danger">
            <span aria-label="Close" data-dismiss="alert" class="close">×</span>
            <i class="fa fa-warning">&nbsp;</i><?php echo $this->session->flashdata('error_message') ?></div>
          <?php
      }
    ?>
          <?php
      if($this->session->flashdata('success_message'))
      { ?>
          <div role="alert" class="alert alert-success">
            <span aria-label="Close" data-dismiss="alert" class="close">×</span>
            <i class="fa fa-check">&nbsp;</i> <?php echo $this->session->flashdata('success_message') ?></div>
          <?php
      }
       ?>
     
	  <?php
		
    if(form_error('package'))
      { ?>
          <div role="alert" class="alert alert-danger">
            <span aria-label="Close" data-dismiss="alert" class="close">×</span>
            <i class="fa fa-warning">&nbsp;</i><?php echo form_error('package'); ?></div>
          <?php
      }
    ?>
    
        <div class="scroll">
         
      
      <form name="payment" method="post" action="">
<table class="table footable footable-loaded default text-center" width="100%">
			<thead>
			<tr>
								<th data-hide="phone,tablet"><?php echo $this->lang->line('account_bidpack_choice');?></th>
								<th data-class="expand" ><?php echo $this->lang->line('account_bonus_points');?></th>
								<th data-hide="phone"><?php echo $this->lang->line('account_bidpack_bids');?></th>
                                
								</tr>
								</thead>
<tbody>


<?php
if($bonus_packages)
{
	foreach($bonus_packages as $package)
	{
?>


			 <tr>
				  <td align="center"><input name="package" type="radio" value="<?php echo $package->id;?>" <?php echo set_radio('package', $package->id); ?> ></td>
				  
				  <td align="center"><?php echo $package->bonus_points;?></td>
                  <td align="center"><?php echo $package->credits;?></td>
				  
				</tr>
	
	
<?php
	}?>
    
    </tbody>	  
			</table>
 <?php
}

?>

<button class="btn btn-danger main_btn" id="submit" type="submit"><?php echo $this->lang->line('account_bttn_buy_bids');?></button>

</form>   
        </div>     
		
                    </div>
                    

