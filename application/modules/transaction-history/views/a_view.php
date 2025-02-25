<h1></h1><div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Transaction History  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
  <h2>View Transaction History  </h2>
    <div class="mid_frm">
    <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin-bottom:10px;">
        <form id="form1" name="form1" method="get" action="">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="120"><strong>Search by Date: </strong></td>
                    <td width="202"><input name="start_date" value="<?php echo $this->input->get('start_date'); ?>" type="date" id="start_date" style="width:150px;" required="required" /></td>
                    <td>
                        <input type="date" name="end_date" value="<?php echo $this->input->get('end_date'); ?>"  style="width:150px;" required="required" />
                    </td>
                    <td><input type="submit" name="Submit" value="Search Member" /></td>
                </tr>
            </table>
        </form>
    </div>
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
        <tr> 
            <th align="left"><div align="left">User Name</div></th>
            <th align="left"><div align="left">Order ID</div></th>
            <th align="left"><div align="left">Transaction ID</div></th>
            <th align="left"><div align="left">Name / Voucher Code</div></th>
            <th align="left"><div align="left">Payment Method </div></th>            
            <th align="center"><div align="left">Amount/Bids</div></th>
            
            <th align="center"><div align="left">Date </div></th>   

        </tr>
        <?php
        if ($this->uri->segment(4))
            $status = $this->uri->segment(4);
        else
            $status = 'active';

        if ($result_data) {
            foreach ($result_data as $data) {
                ?>
                <tr> 
                    <td align="left"><div align="left"><?php print($data->user_name); ?></div></td>
                    <td align="left"><div align="left"><?php print($data->invoice_id); ?></div></td>
                    <td align="left"><div align="left"><?php print($data->txn_id); ?></div></td>
                    <td align="left"><div align="left"><?php print($data->transaction_name); ?><?php if($data->voucher_code){echo "<div>(".$data->voucher_code.")</div>";}; ?></div></td>
                    <td align="left"><div align="left"><?php print($data->payment_method); ?></div></td>
                    
                    <td align="left"><div align="left"><?php print($data->amount); ?> / <?php echo ($data->credit_get) ? $data->credit_get : $data->credit_used; ?></div></td>
                    
                    <td align="left"><div align="left"><?php print($this->general->convert_local_time($data->transaction_date)); ?></div></td>
                   
                </tr>
                <?php
            }
            //if ($this->pagination->create_links()) {
                ?>
                <tr> 
                    <td colspan="8" align="center" style="border-right:none;" class="paging"><?php echo $this->pagination->create_links(); ?></td>
                </tr>
                <?php
            //}
        } else {
            ?>
            <tr> 
                <td colspan="8" align="center" style="border-right:none;"> (0) Zero Record Found </td>
            </tr>
            <?php
        }
        ?>
    </table>
 </div>
    <div class="clear"></div>
</div>
  