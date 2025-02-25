<div class="content">

    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?= site_url(ADMIN_DASHBOARD_PATH) ?>">ADMIN</a> &raquo; Push Notification Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
        <a href="javascript:history.go(-1)" style="text-decoration:none;">
            <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
        </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
    <h2>Send Push Notification </h2>
    <div class="mid_frm">





        <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
            <?php
            if ($this->session->flashdata('message')) {
                echo "<div class='message'>" . $this->session->flashdata('message') . "</div>";
            }
            ?></div>

        <?php
//print_r($site_set);
        ?>
        <form name="sitesetting" method="post" action="" accept-charset="utf-8">


            <table align=left cellpadding=2 cellspacing=5 width=99% border="0" class="light">
				<tr>
                    <td><strong>Send To</strong></td>
                    <td>
                    	<?php $post_sent_to = set_value('sent_to');?>
                    	<input type="radio" name="sent_to" value="1" class="sendTo" checked="checked" />Mass Notification    &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="sent_to" value="2" class="sendTo" <?php if($post_sent_to==2){ echo 'checked="checked"';}?> />Only who have low balance
                        </td>
                </tr>
                <tr id="min_balance"  <?php if($post_sent_to!=2){ echo 'style="display:none;"';}?>>
                    <td><strong>User Balance</strong></td>
                    <td><input size="10" class="inputtext" type="text" id="user_balance" name="user_balance" value="<?php echo set_value('user_balance');?>">
                        <?= form_error('user_balance') ?></td>
                </tr>
                <tr>
                    <td><strong>Notification Title</strong> <br /><small>(Max 65 Char.)</small></td>
                    <td><input size="79" class="inputtext" type="text" id="subject" name="subject" value="<?php echo set_value('subject');?>">
                        <?= form_error('subject') ?></td>
                </tr>

                <tr><td>Notification Text <br /><small>(Max 240 Char.)</small></td>
                    <td><textarea name="message_body" cols="77" rows="4"><?php echo set_value('message_body');?></textarea>
                        <?= form_error('message_body') ?></td>
                </tr>
                <tr>
                    <td></td><td><button type='submit' class="bttn">Send</button></td>
                </tr>


            </table>



        </form>

    </div>
    <div class="clear"></div>
</div>

<script>
$(".sendTo") // select the radio by its id
    .change(function(){ // bind a function to the change event
        if( $(this).is(":checked") ){ // check if the radio is checked
            var val = $(this).val(); // retrieve the value
			if(val==2)
				$("#min_balance").show();
			else
				$("#min_balance").hide();
        }
    });
</script>