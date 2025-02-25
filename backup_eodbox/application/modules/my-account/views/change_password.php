<form name="profile_update" action="" method="post" class="login-form" enctype="multipart/form-data">
 <div class="row">
                        
                        
                        
                        
                        <div class="col-12">
                        	<?php
							if ($this->session->flashdata('error_message')) {
								?>
                                <div class="alert alert-danger alert-dismissible fade show">
                            <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
                            <p><?php echo $this->session->flashdata('error_message') ?></p>
                        </div>
								
								<?php
							}
							?>
							<?php
							if ($this->session->flashdata('success_message')) {
								?>
                                <div class="alert alert-success alert-dismissible fade show">
                            <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
                            <p><?php echo $this->session->flashdata('success_message') ?></p>
                        </div>
								
								<?php
							}
							?>
                            <div class="dash-pro-item mb-30 dashboard-widget">
                                <div class="header">
                                    <h4 class="title"><?php echo lang('change_password'); ?></h4>
                                    
                                </div>
                                
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                   <label><?php echo lang('old_password'); ?></label>
                            <input type="password" name="old_password" class="form-control">
                            <?= form_error('old_password') ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><?php echo lang('change_pass_new'); ?></label>
                            <input type="password" name="new_password" class="form-control">
                            <?= form_error('new_password') ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                
                                <div class="col-md-6 mb-3">
                                    <label><?php echo lang('confirm_new_password'); ?></label>
                            <input type="password" name="re_password" class="form-control">
                            <?= form_error('re_password') ?>
                                </div>
                            </div>
            				<div class="row">
                            <div class="col-md-12 mt-4 mb-3 text-center">
                            <button type="submit" class="custom-button">SUBMIT</button>
                            </div>
                        </div>
                        	<small><strong>Note: </strong><?php echo $this->lang->line('change_pass_guide_txt');?> </small>
                            </div>
                        </div>
                        
                        
                       
                    </div>
</form>