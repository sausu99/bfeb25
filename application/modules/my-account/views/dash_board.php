<div class="dashboard-widget mb-40">
                        <div class="dashboard-title mb-30">
                            <h5 class="title"><?php echo lang('account_dashboard');?></h5>
                        </div>
                        <div class="row justify-content-center mb-30-none">
                            <div class="col-md-4 col-sm-6">
                                <div class="dashboard-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>dashboard/01.png" alt="dashboard">
                                    </div>
                                    <div class="content">
                                        <h2 class="title"><span class="counter"><?php echo $total_live_auc;?></span></h2>
                                        <h6 class="info"><?php echo lang('label_active_bid');?></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="dashboard-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>dashboard/02.png" alt="dashboard">
                                    </div>
                                    <div class="content">
                                        <h2 class="title"><span class="counter"><?php echo $total_won_auc;?></span></h2>
                                        <h6 class="info"><?php echo lang('label_item_won');?></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="dashboard-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>dashboard/03.png" alt="dashboard">
                                    </div>
                                    <div class="content">
                                        <h2 class="title"><span class="counter"><?php echo ($user_watchlist==false)?'0':count($user_watchlist);?></span></h2>
                                        <h6 class="info"><?php echo lang("label_favorites");?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>