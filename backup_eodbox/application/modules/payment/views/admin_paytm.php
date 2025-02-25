<div class="content">

    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?= site_url(ADMIN_DASHBOARD_PATH) ?>">ADMIN</a> &raquo; Payment System Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
        <a href="javascript:history.go(-1)" style="text-decoration:none;">
            <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
        </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
    <h2>Update ccavenue Gateway API </h2>
    <div class="mid_frm">

        <?php $this->load->view('admin_payment_menu'); ?>

        <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
            <?php
            if ($this->session->flashdata('message')) {
                echo "<div class='message'>" . $this->session->flashdata('message') . "</div>";
            }
            ?></div>

        <?php

        ?>
        <form name="sitesetting" method="post" action=""  enctype="multipart/form-data">
            <input size="50" class="inputtext" type="hidden" id="auth_token" name="auth_token" value="<?php echo $payment['secret_token']; ?>">
            <input size="50" class="inputtext" type="hidden" id="api_key" name="api_key" value="<?php echo $payment['api_key']; ?>">
            <table align=left cellpadding=2 cellspacing=5 width=99% border="0" class="light">

                <tr>
                    <td class="hmenu_font">&nbsp;</td>
                    <td><img src='<?php echo base_url() . $payment['payment_logo']; ?>' ></td>
                </tr>
                <tr>
                    <td width=229 class="hmenu_font"> Logo:</td>
                    <td width="429">
                        <input name="logo" type="file" id="logo" />
                        (Max Size 300px X 50px)
                        <?= $this->upload->display_errors('<div class="error">', '</div>'); ?>
                        <input type="hidden" name="logo_old" value="<?php echo $payment['payment_logo']; ?>" />  </td>
                </tr>

                <tr>
                    <td ><strong>Payment Mode:</strong>        </td>
                    <td ><input name="status" type="radio" value="2" checked="checked" /> 
                        Live
                        <input name="status" type="radio" value="1" <?php if ($payment['status'] == '1') echo 'checked'; ?> /> 
                        Test
                        <br />      
                        <?= form_error('status') ?>	  </td>
                </tr>

                <tr height=25 valign="middle">
                    <td><strong>Is Display :</strong></td>
                    <td><input name="is_display" type="radio" value="Yes" checked="checked" /> Yes
                        <input name="is_display" type="radio" value="No" <?php if ($payment['is_display'] == 'No') echo 'checked'; ?> /> No
                        <br />      
                        <?= form_error('is_display') ?>	  </td>

                </tr>
                <tr>
                    <td><strong>Merchant id:</strong></td><td>
                        <input type="text" name="merchant_id" value="<?php echo $payment['merchant_id'] ?>">
                        <br /> <?= form_error('merchant_id'); ?>
                    </td></tr>
                <tr> 
                    <td>
                        <strong>Merchant key:</strong></td><td>
                        <input type="text" name="merchant_key" value="<?php echo $payment['merchant_key'] ?>">
                        <br />  <?= form_error('merchant_key'); ?>
                    </td>
                </tr>
                <tr> <td><strong> Merchant website:</strong></td><td>
                        <input type="text" name="merchant_website" value="<?php echo $payment['merchant_website'] ?>">
                        <br />  <?= form_error('merchant_website'); ?>
                    </td></tr>
                <tr height=25 valign="middle">
                    <td align="center">&nbsp;</td>
                    <td height="40" align="left"><input name="submit" type="submit" class="bttn" value="Submit" /></td>
                </tr>
            </table>


        </form>

    </div>
    <div class="clear"></div>
</div>