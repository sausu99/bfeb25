  <div class="main-content">  
    <div class="main_body">
      <div class="container">
            <div class="row">
            	<div class="card hovercard">
                 <?php echo $this->load->view('common/hover_card');?> 
            </div>
    </div>
            <div class="board">
             <?php echo $this->load->view('common/profile_navbar');?>
            <div class="login-page">
            <h3><?php echo lang('order_auctions'); ?></h3>
             <?php if($shoppings){ ?>
            <table class="table footable footable-loaded default" width="100%">
	          <thead>
                      <tr>
                        <th width="18%" data-hide="phone"><?php echo lang('image'); ?></th>
                        <th width="18%" data-class="expand"><?php echo lang('won_auction_name'); ?></th>
                        <th width="15%" data-hide="phone"><?php echo lang('price'); ?></th>
                        <th width="16%" data-hide="phone"><?php echo lang('shipping_cost'); ?></th>
                        <th width="16%" data-hide="phone,tablet"><?php echo lang('total_cost'); ?></th>
                        <th width="17%" data-hide="phone,tablet"><?php echo lang('view_document'); ?></th>
                    </tr>
                </thead>
              <tbody>
          <?php  
            foreach($shoppings as $data)
            { 
            ?>
          <tr>
            <td class="table-order-img"><img src="<?php print(ROOT_SITE_PATH.AUCTION_IMG_PATH.'thumbnail_'.$data->image1);?>" width="55"></td>
            <td><?php echo character_limiter($data->name,20);?></td>
            <td><?php echo DEFAULT_CURRENCY_SIGN.$data->unit_price;?></td>
            <td><?php echo DEFAULT_CURRENCY_SIGN.$data->shipping_charge;?></td>
            <td><?php echo DEFAULT_CURRENCY_SIGN.$data->total_cost;?></td>
            <td><?php if($data->delivery_url){?> <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal_<?=$data->id;?>"><?php echo lang('view'); ?></a> <?php } else { echo lang('not_added'); }?></td>
            
            <div id="myModal_<?=$data->id; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog"> 
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="modal-title modal_head">
                <h3><?php echo lang('shipping_doc'); ?></h3>
                <p><?php echo lang('auction_name'); ?>: <span><?php echo $data->name; ?></span></p>
              </div>
            </div>
            <div class="modal-body">
              <div class="panel-default tbl_bdr tbl_popup">
                <div class="panel-body">
                   <p><img src="<?php print(site_url(DELIVERY_IMG_PATH.$data->delivery_url));?>" align="absmiddle"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
          </tr>
          <?php }?>
        </tbody>
        </table>
         <?php }else{?>
      <h4><?php echo lang('record_not_found'); ?></h4>
      <?php } ?>
        	</div>
		    </div>
      	</div>
	</div>
  </div> <!--end-->