  
            <div class="col-md-9 col-sm-8 content_sec">
              <div class="sec_title relative"><div class="skew_bg"></div><?php echo lang('make_your_wish'); ?> </div>
              <div class="row">
              <?php
              if($vote_auc):
                foreach ($vote_auc as  $auc_data) {
                  $vote=  $this->general->get_vote_check($this->session->userdata(SESSION.'user_id'),$auc_data->product_id);
                    // echo "<pre>";
                    // print_r($vote);
                    // exit;
                    $voteclass_po='';
                    $voteclass_neg='';
                    
                    if($this->session->userdata(SESSION.'user_id'))
                     {
                        if($vote)
                        {
                          if($vote->positive_rating=='1' && $vote->negative_rating=='0')
                          {
                          // echo "abc";
                            $voteclass_po='voteselect';
                            $voteclass_neg='';
                          }
                        
                        else if($vote->positive_rating=='0' && $vote->negative_rating=='1')
                          {
                          // echo "abcsss";
                            $voteclass_po='';
                            $voteclass_neg='voteselect_neg';
                          }
                        }
                        
                        
                        else
                        {
                            $voteclass_po='';
                            $voteclass_neg='';

                        }
                     }

                ?>
                 <div class="col-md-4 col-sm-6 col-xs-6 draggable-element">
                  <div class="product_container">
                    <figure class="drag-area"><a href="<?php echo $this->general->lang_uri('/vote/details/'.$this->general->clean_url($auc_data->name).'-'.$auc_data->product_id);?>"> <img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.'thumb_'.$auc_data->image1);?>" alt="<?php echo $auc_data->name;?>"></a></figure>
                    <div class="timer timer1">
                      <ul>
                        <li class="relative">
                          <div class="t_box">
                          <span id="votestatus_<?php echo $auc_data->product_id;  ?>" class="ppup vt" style="display:none"></span>
                            <div class="c_ttl"><span id="votepos_<?php echo $auc_data->product_id;  ?>">  <?php echo $auc_data->positive_rating; ?></span> <?php echo lang('likes'); ?></div>
                            <a href="javascript:void(0)" id="vote_pos_<?php echo $auc_data->product_id;?>" class="counter btn_vote <?php echo $voteclass_po; ?>" data-votetype='positive' data-productid='<?php echo $auc_data->product_id; ?>'><i class="fa fa-thumbs-up"></i></a> </div>
                        </li>
                        <li>
                          <div class="t_box">
                            <div class="c_ttl"><span id="voteneg_<?php echo $auc_data->product_id;  ?>">   <?php echo $auc_data->negative_rating; ?></span> <?php echo lang('dislikes'); ?></div>
                            <a href="javascript:void(0)" id="vote_neg_<?php echo $auc_data->product_id;?>"  class="counter btn_vote <?php echo $voteclass_neg; ?>" data-votetype='negative' data-productid='<?php echo $auc_data->product_id; ?>' ><i class="fa fa-thumbs-down"></i></a> </div>
                        </li>
                      </ul>
                    </div>
                    <div class="content">
                      <ul>
                        <li class="content_ttl"><a href="<?php echo $this->general->lang_uri('/vote/details/'.$this->general->clean_url($auc_data->name).'-'.$auc_data->product_id);?>"><?php echo character_limiter($auc_data->name,20);?></a></li>
                        <li><?php echo lang('retail_price'); ?>: <span><?php if($auc_data->price==0)echo "<span class='priceless'>".lang('priceless').'</span>';else echo $this->general->formate_price_currency_sign($auc_data->price, '<span>', '</span>'); ?></span></li>
                        <li><?php echo lang('total_votes'); ?>: <span id="total_vote_<?php echo $auc_data->product_id; ?>" > <?php $total_vote= $auc_data->positive_rating+$auc_data->negative_rating; echo ($total_vote)?$total_vote:0; ?></span></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <?php 
                }
                endif; 
               ?>
               
              </div>
            </div>
            
       