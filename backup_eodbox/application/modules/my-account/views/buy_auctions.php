<div class="dash-bid-item dashboard-widget mb-40-60">
                        <div class="header">
                            <h4 class="title"><?php echo lang('account_buy_auctions'); ?></h4>
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
          <table class="table footable footable-loaded default text-center" width="100%">
                            
            <thead>
              <tr>
                        <th data-hide="phone,tablet"><?php echo $this->lang->line('won_auction_image');?></th>
                        <th data-class="expand" ><?php echo $this->lang->line('won_auction_name');?></th>
                        <th data-hide="phone,tablet"><?php echo $this->lang->line('auc_date_time');?></th>
                        <th data-hide="phone"><?php echo lang('total_cost'); ?></th>
                        <th><?php echo $this->lang->line('won_status');?></th>
                    </tr>
            </thead>
                              
          <tbody>
          <?php
          if($buy_auc)
          {
            $timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION.'country_id'));
            foreach($buy_auc as $auc_lists)
            {
          ?>
        
          <tr>
                                <td><img src="<?php print base_url(AUCTION_IMG_PATH.'thumb_'.$auc_lists->image1);?>" ></td>
                                <td><a href="<?php echo $this->general->lang_uri('/auctions/closed/'.$this->general->clean_url($auc_lists->name).'-'.$auc_lists->product_id);?>"><?php echo $auc_lists->name;?></a></td>
                                <td>
                            
                             <?php 
                                        
                                        echo $this->general->convert_local_time($auc_lists->transaction_date,$timeZone);
                                ?>
                                  </td>
                                <td><?php echo $this->general->formate_price_currency_sign($auc_lists->amount,'<span>','</span>');?></td>
                                <td><?php echo $auc_lists->transaction_status;?></td>
                            </tr>
          <?php
            }
          }
          else{
          ?>
          <tr>
          <td colspan="5">
          <div align="center"><?php echo $this->lang->line('all_0record_found');?></div>
          </td></tr>
          <?php
            }
          ?>
        
          </tbody>	  
          </table>  
        </div>     
		<?php if($pagination_links){ echo $pagination_links; } ?>
                    </div>