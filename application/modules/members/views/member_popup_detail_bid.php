<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Untitled Document</title>
        <style type="text/css">
            .theclass {
                background-color:#AC1C74;
            }
        </style>
    </head>

    <body>

        <?php
        if ($bid_info) {
            ?>
            <table  width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                <tr>
                    <td><h2 class="product_info"><?= $auction_info->name; ?>
                            - <?= $auction_info->product_id; ?></h2></td></tr>
            </table>
            <table width="100%" border="0" align="center" cellpadding="4" cellspacing="2" class="green_border">
                <tr>
                    <td width="40%" class="theclass"><strong>Date & Time</strong></td>
                    <td width="25%" align="left" class="theclass"><strong>My Bids </strong></td>
                    <td width="35%" align="right" class="theclass"><strong>status</strong></td>
                </tr>
                <?php
                $current_winner_amt = $this->general->get_winner_amt($auction_info->product_id);

                $count = 0;
                foreach ($bid_info as $data) {
                    if ($count % 2 == 0) {
                        $bg = "#FFFFFG";
                    } else {
                        $bg = "#CCCCCC";
                    }
                    ?>
                    <tr  bgcolor="<?php echo $bg; ?>">
                        <td   width="40%"><?= $this->general->convert_local_time($data->bid_date); ?></td>
                        <td width="25%" align="left"><?= $data->userbid_bid_amt; ?></td>
                        <td width="35%" align="right">				  
                            <?php
                            //$this->bidstatus=$this->general->getBidStatus1($data->userbid_bid_amt,$auction_info->product_id);

                            if ($data->freq > 1) {
                                echo "<font color='red'>Not Unique</font>";
                            } else if ($data->freq == 1 && $current_winner_amt == $data->userbid_bid_amt) {
                                echo "<font color='green'>Lowest Unique Bid</font>";
                            } else if ($data->freq == 1) {
                                echo "<font color='blue'>Unique But Not Lowest</font>";
                            }
                            ?>

                </td>
            </tr>
            <? $count++; }  ?>
        </table>
        <? } ?>


    </body>
</html>

