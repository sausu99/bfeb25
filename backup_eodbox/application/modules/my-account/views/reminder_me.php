<h1><?php echo $this->lang->line('reminder_footer_title');?></h1>
<?php
$user_balance = $this->general->get_user_balance();
$user_sms_balance = $user_balance['sms_balance'];
if($this->session->flashdata('message')){?>
<div class="errmsg"><?php echo $this->session->flashdata('message');?></div>
<?php }?>
<div class="acc-lt">
  <div class="bidhis-area">
    <section id="smsbal">
     <div class="ttl_balance">
       <b>
         <?php echo lang('sms_balance'); ?> : <span><?php echo $user_sms_balance;?></span>
       </b><span> &nbsp;<?php echo lang('bids'); ?></span>
       <ol class="adremlink">
         <li id="add"><a href="javascript:void();"><?php echo lang('add'); ?></a></li>
         <li id="del"><a href="javascript:void();"><?php echo lang('delete'); ?></a></li>
       </ol>	
       <div class="clear"></div>
     </div>
     <div class="add_del" id="addbal">
      <form name="add" method="post" action="<?php echo $this->general->lang_uri('/auctions/reminder/add_sms_balance');?>">
       <ol class="adremlist">
        <li> + </li>
        <li> <input name="add_sms" value="<?php echo $this->session->flashdata('sms_add_val');?>" type="text" /></li>
        <li> <input name="submit" type="submit" value="<?php echo lang('add'); ?>" /> </li>
        <li class="equal"> = </li>
        <li class="ttlbal"> <i> <?php echo $user_sms_balance;?> <span><?php echo lang('remains'); ?></span></i></li>
      </ol>
      <div class="error"><?php echo $this->session->flashdata('sms_add_error');?></div>
    </form>
    <div class="clear"></div>
  </div>

  <div class="add_del" id="delbal">
   <form name="del" method="post" action="<?php echo $this->general->lang_uri('/auctions/reminder/del_sms_balance');?>">
     <ol class="adremlist">
      <li> - </li>
      <li> <input name="delete_sms" type="text" value="<?php echo $this->session->flashdata('sms_del_val');?>" /></li>
      <li> <input name="submit" type="submit" value="<?php echo lang('del'); ?>" /> </li>
      <li class="equal"> = </li>
      <li class="ttlbal"> <i> <?php echo $user_sms_balance;?> <span><?php echo lang('remains'); ?></span></i></li>
    </ol>
    <div class="error"><?php echo $this->session->flashdata('sms_del_error');?></div>
  </form>
  <div class="clear"></div>
</div>

</section>

<?php
if($reminder_me_lists)
{
  ?>

  <table class="footable">
    <thead>
      <tr>
        <th data-class="expand"  ><?php echo lang('auctions_name'); ?></th>
        <th data-hide="phone,tablet"><?php echo lang('date'); ?></th>
        <th data-hide="phone"><?php echo lang('otification') ?></th>
        <th><?php echo lang('operation'); ?></th>
      </tr>
    </thead>
    <?php

    foreach($reminder_me_lists as $trans)
    {
      ?>

      <tbody>
       <tr>
        <td><?php echo $trans->name;?></td>
        <td><?php echo $this->general->date_formate($trans->date)?>	</td>
        <td><?php if($trans->notify_type == 'email')echo ucfirst($trans->notify_type); else echo strtoupper($trans->notify_type);?></td>
        <td><a href="<?php echo $this->general->lang_uri('/auctions/reminder/del_reminder/'.$trans->id);?>" class="smbtn"><?php echo lang('delete'); ?></a></td>
      </tr>

      <?php
    }
  }
  else{
    ?>
    <p align="center"><?php echo $this->lang->line('account_purchase_history_no_tranx_txt');?></p>
    <?php
  }
  ?>

</tbody>	  
</table>
</div>
<div class="acc-shadow"></div>
</div>

<?php $this->session->unset_userdata('message');?>

<script>
  <?php if($this->session->flashdata('sms_add_error')){?>
    $('#add').addClass('current');
    $('#addbal').show();
    <?php }?>

    <?php if($this->session->flashdata('sms_del_error')){?>
      $('#del').addClass('current');
      $('#delbal').show();
      <?php }?>

      $('#add a').click(function() {
       $('#add').addClass('current');
       $('#del').removeClass('current');

       $('#addbal').show();
       $('#delbal').hide();
     });

      $('#del a').click(function() {
       $('#add').removeClass('current');
       $('#del').addClass('current');

       $('#addbal').hide();
       $('#delbal').show();
     });
   </script>