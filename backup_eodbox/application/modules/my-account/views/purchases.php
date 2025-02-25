<div class="dashboard-widget  mb-40">
                    <h5 class="title mb-10"><?php echo $this->lang->line('account_purchase_history'); ?></h5>
                    <div class="dashboard-purchasing-tabs">
                        <ul class="nav-tabs nav">
                            
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active fade" id="current">
                            
                            <?php
							if ($this->session->flashdata('error_message')) {
								?>
								<div role="alert" class="alert alert-danger">
									
									<i class="fa fa-warning">&nbsp;</i><?php echo $this->session->flashdata('error_message') ?></div>
								<?php
							}
							?>
							<?php
							if ($this->session->flashdata('success_message')) {
								?>
								<div role="alert" class="alert alert-success">
									
									<i class="fa fa-check">&nbsp;</i> <?php echo $this->session->flashdata('success_message') ?></div>
								<?php
							}
							?>
                                <table class="purchasing-table">
                                    <thead style="font-size: 12px;">
                                        <tr>
                                            <th><?php echo $this->lang->line('account_invoice_id'); ?></th>
                                            <th><?php echo $this->lang->line('account_purchase_description'); ?></th>
                                            <th><?php echo $this->lang->line('account_bidpack_bids'); ?></th>
                                            <th><?php echo $this->lang->line('account_bonus_points'); ?></th>
                                            <th><?php echo $this->lang->line('account_purchase_dt'); ?></th>                                    
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px;">
                                    <?php if($get_trans){ foreach($get_trans as $trans){?>
                                        <tr style="font-size: 12px;">
                                        <td><?php echo $trans->invoice_id ?></td>
                                        <td><?php
                                            echo "<div class='error'>$trans->transaction_name</div>";
											
                                            if ($trans->payment_method == 'paypal') {
                                                echo "(Paypal Transaction: " . $trans->txn_id.")";
                                            }
                                            
                                            ?>
                                        </td>
                                        <td><?php echo isset($trans->credit_get) ? $trans->credit_get : "---"; ?></td>
                                        <td><?php echo isset($trans->bonus_points) ? $trans->bonus_points : "---"; ?></td>
                                        <td>
                                            <?php
                                            $timeZone = $this->general->get_user_timezone_by_country($this->session->userdata(SESSION . 'country_id'));
                                            echo $this->general->convert_local_time($trans->transaction_date, $timeZone);
                                            ?>
                                        </td>                                       
                                    </tr>
                                     <?php }}else{?>
                                        <tr style="font-size: 12px;">
                                            <td align="center" colspan="6"><?php echo $this->lang->line('account_purchase_history_no_tranx_txt'); ?></td>                                            
                                        </tr>
                                     <?php }?>
                                        
                                    </tbody>
                                </table>
                                
                                <?=$this->pagination->create_links()?>
                            </div>
                        </div>
                    </div>
                </div>
                

