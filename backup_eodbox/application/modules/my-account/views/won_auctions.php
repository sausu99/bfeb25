<div class="dash-bid-item dashboard-widget mb-40-60">
                        <div class="header">
                            <h4 class="title"><?php echo lang('won_auctions'); ?></h4>
                         </div>   
                            <table class="table footable footable-loaded default text-center" width="100%">
                                    <thead>
                                      <tr>
                                        <th width="15%" data-class="expand"><?php echo lang('image'); ?></th>
                                        <th width="25%" data-hide="phone,tablet"><?php echo lang('auctions_name'); ?></th>
                                        <th width="20%" data-hide=""><i class="fa fa-trophy">&nbsp;</i> <?php echo lang('winning_bid'); ?></th>
                                        <th width="20%" data-hide="phone"><?php echo lang('date_and_time'); ?></th>
                                        <th width="25%" data-hide="phone,tablet"><?php echo lang('auction'); ?></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      if($won_auc)
                                      {
                                       $timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION.'country_id'));
                                       foreach($won_auc as $auc_lists)
                                       {
                                        ?>
                        
                                        <tr>
                                          <td><img src="<?php echo base_url(AUCTION_IMG_PATH.'thumb_'.$auc_lists->image1);?>" style="width: 150px;" ></td>
                                          <td><a href="<?php echo $this->general->lang_uri('/auctions/closed/'.$this->general->clean_url($auc_lists->name).'-'.$auc_lists->product_id);?>"><?php echo $auc_lists->name;?></a></td>
                                          <td><?php echo $this->general->default_formate_price_currency_sign($auc_lists->won_amt,'<span>','</span>');?></td>
                                          <td>				
                                            <?php 
                                            
                                            echo $this->general->convert_local_time($auc_lists->end_date,$timeZone);
                                            ?></td>
                                          
                                          <td><?php 
                                            if($auc_lists->payment_status == 'Completed')
                                            { 
                                              ?>
                        
                                              <?php 
                                              /* only display testimonial button when user paid for auction cost and admin sent items*/ 
                                              /*if($this->account_module->check_valide_testimonial_user($auc_lists->product_id) > 0 && $this->account_module->check_testimonial_added($auc_lists->product_id) == false)
                                              {
                                                ?>
                                                <a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/testimonial/'.$auc_lists->product_id);?>" ><?php echo $this->lang->line('all_testimonial');?></a>
                                                <?php } else {*/?>
                                                <?php echo $this->lang->line('won_completed');?>
                                                <?php /*}*/?>
                                                <?php
                                              }else{?>
                                              <a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/wonauctionsconfirm/'.$auc_lists->product_id);?>" class="btn btn-primary btn-sm"><?php echo $this->lang->line('account_bttn_name');?></a>
                                              <?php }?>
                                            </td>
                                          </tr>
                                          <?php
                                        }
                                      }
                                      else{
                                        ?>
                                        <tr><td colspan="5"><?php echo $this->lang->line('won_no_item_txt');?></td></tr>
                                        <?php
                                      }
                                      ?>
                        
                                    </tbody>
                                  </table>
                                  <?php if($pagination_links){ echo $pagination_links; } ?>
                     
                    </div>


                                          
                    