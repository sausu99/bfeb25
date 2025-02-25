<script type="text/javascript">
    function doconfirm()
    {
        job = confirm("Are you sure to delete permanently?");
        if (job != true)
        {
            return false;
        }
    }
</script>
<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?= site_url(ADMIN_DASHBOARD_PATH) ?>">ADMIN</a> &raquo;  <a href="<?= site_url(ADMIN_DASHBOARD_PATH) . '/members/index' ?>">Member  Management </a></span></div>
<div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
        <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
<h2>View Members Transactions </h2>
<div class="mid_frm">

    <div align="center"><?php $this->load->view('menu'); ?></div>
    <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
        <?php
        if ($this->session->flashdata('message')) {
            echo "<div class='message'>" . $this->session->flashdata('message') . "</div>";
        }
        ?></div>
    <table width=100% align=center border=0 cellspacing=0 cellpadding=8 class="light">


        <tr style=" background-color:#FFFFFF;">
            <td height="30" bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;">Member: <strong><?php echo $profile->first_name . ' ' . $profile->last_name; ?></strong></div></td>
            <td bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;">Current Balance:  <strong><?php echo $profile->balance; ?> Credits </strong></div></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
        <tr> 
            <th align="left"><div align="left">Date & Time</div></th>
            <th align="left"><div align="left">Ip Address</div></th>
        </tr>
        <?php
        
        if ($result_data) {
            foreach ($result_data as $data) {
                ?>
                <tr> 
                    <td align="left"><div align="left"><?php print($this->general->convert_local_time($data->date)); ?></div></td>
                    <td align="left"><div align="left"><?php print($data->ip_address); ?></div></td>
                </tr>
                <?php
            }
            
                ?>
                <tr> 
                    <td colspan="8" align="center" style="border-right:none;" class="paging"><?php echo $this->pagination->create_links(); ?></td>
                </tr>
                <?php
            
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
