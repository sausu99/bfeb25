<script>
var share_url="<?php echo current_url();?>";
</script>
<div class="row">
    <div class="product_container p_detail">
    <div class="row">
       <div class="col-md-6">
    			<div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Wrapper for slides -->
      <div class="carousel-inner">
      <div class="item active"><img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.$auc_data->image1);?>"></div>
          <?php if($auc_data->image2){?>
      <div class="item"><img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.$auc_data->image2);?>"></div>
      <?php }if($auc_data->image3){?>
      <div class="item"><img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.$auc_data->image3);?>"></div>
      <?php }if($auc_data->image4){?>
      <div class="item"><img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.$auc_data->image4);?>"></div>                
      <?php }?>
      </div><!-- End Carousel Inner -->
      
      <!-- Controls -->
	  <?php if($auc_data->image2){?>  
      <!-- Controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next"><i class="fa fa-chevron-right"></i></a>
    <?php }?>
    <?php if($auc_data->image2){?>
      <ul class="nav nav-pills nav-justified">
          <li data-target="#myCarousel" data-slide-to="0" class="active"><a href="#"><img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.'thumb_'.$auc_data->image1);?>"></a></li>
          <?php if($auc_data->image2){?>
          <li data-target="#myCarousel" data-slide-to="1"><a href="#"><img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.'thumb_'.$auc_data->image2);?>"></a></li>
          <?php }if($auc_data->image3){?>
          <li data-target="#myCarousel" data-slide-to="2"><a href="#"><img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.'thumb_'.$auc_data->image3);?>"></a></li>
          <?php }if($auc_data->image4){?>
          <li data-target="#myCarousel" data-slide-to="3"><a href="#"><img src="<?php echo base_url(ROOT_SITE_PATH.AUCTION_IMG_PATH.'thumb_'.$auc_data->image4);?>"></a></li>
          <?php }?>
        </ul>
          <?php }?>
        <div class="clearfix"></div>
        </div><!-- End Carousel -->
                </div>
            <div class="col-md-6 p-info vote_info"><h2><?php echo $auc_data->name; ?></h2>
               
                <div class="row bid_a form-group">
                <span class="col-xs-6"><i class="fa fa-tag"></i> <?php echo lang('retail_price'); ?>: <?php if($auc_data->price==0)echo "<span class='priceless'>".lang('priceless').'</span>';else echo $this->general->formate_price_currency_sign($auc_data->price, '<span>', '</span>'); ?></span>
               
                              
                
                <span class="col-xs-6"><i class="fa fa-thumbs-up"></i> <?php echo lang('total_votes'); ?> : <b id="total_vote_<?php echo $auc_data->product_id; ?>"> <?php $total_vote= $auc_data->positive_rating+$auc_data->negative_rating; echo ($total_vote)?$total_vote:0; ?></b></span>
                </div>
                <?php 
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
                <div class="timer timer1 relative">
                 <span id="votestatus_<?php echo $auc_data->product_id;  ?>" class="ppup vt" style="display:none"></span>
                      <ul>
                        <li>
                          <div class="t_box">

                            <div class="c_ttl"><span id="votepos_<?php echo $auc_data->product_id;  ?>">  <?php echo $auc_data->positive_rating; ?></span> <?php echo lang('likes'); ?></div>
                            <a href="javascript:void(0)" id="vote_pos_<?php echo $auc_data->product_id;?>"  class="counter btn_vote <?php echo $voteclass_po; ?> " data-votetype='positive' data-productid='<?php echo $auc_data->product_id; ?>' ><i class="fa fa-thumbs-up"></i></a> </div>
                        </li>
                        <li>
                          <div class="t_box">
                            <div class="c_ttl"><span id="voteneg_<?php echo $auc_data->product_id;  ?>">   <?php echo $auc_data->negative_rating; ?></span> <?php echo lang('dislikes'); ?></div>
                            <a href="javascript:void(0)" id="vote_neg_<?php echo $auc_data->product_id;?>"  class="counter btn_vote <?php echo $voteclass_neg; ?>" data-votetype='negative' data-productid='<?php echo $auc_data->product_id; ?>' ><i class="fa fa-thumbs-down"></i></a> </div>
                        </li>
                      </ul>
                    </div>
                  
                <div class="row social_share"><h3 class="col-lg-12"><?php echo lang('share_on_social_media'); ?></h3>

                <div class="col-xs-6"><a href="javascript:void(0)" onclick="share_on_fb()" class="btn btn-primary btn-block text-left"><i class="fa fa-facebook-f"></i> <span><?php echo lang('facebook'); ?></span></a></div>
                <div class="col-xs-6"><a href="javascript:void(0)" class="btn btn-danger btn-block text-left" onclick="share_on_gl()"><i class="fa fa-google-plus"></i> <span><?php echo lang('googleplus'); ?></span></a></div>

                </div>
                </div>

            </div>
        <div class="p_n_sec">
        <div class="col-xs-12">
        <h3><?php echo lang('item_description'); ?></h3>
         <?php echo $auc_data->description; ?>
        </div>
        <div class="clearfix"></div>
        </div>
        </div>
        </div>
        <?php echo $this->load->view('common/more_auction');?>