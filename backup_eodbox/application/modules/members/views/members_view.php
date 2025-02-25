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

<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?= site_url(ADMIN_DASHBOARD_PATH) ?>">ADMIN</a> &raquo; Members  Management </span></div>
<div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
        <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
<h2>View Members Details </h2>
<div class="mid_frm">

    <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin:10px 0 20px 0;">
        <?php
        if ($this->uri->segment(3))
            $status = $this->uri->segment(4);
        else
            $status = 'active';
        ?>
        <ul id="vList">
            <li>[ <?php
                if ($this->uri->segment(3) != 'add_member') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/add_member', 'Add Members', 'title="Add Members"');
                } else {
                    echo "Add Members";
                }
                ?> ]</li>
                
            <li>[ <?php
                if ($status != 'all') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/index/all', 'Total Members', 'title="Total Members"');
                } else {
                    echo "Total Members";
                }
                ?> ]</li>

            <li>[ <?php
                if ($status != 'active') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/index/active', 'Active Members', 'title="Active Members"');
                } else {
                    echo "Active Members";
                }
                ?> ]</li>

            <li>[ <?php
                if ($status != 'inactive') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/index/inactive', 'Inactive Members', 'title="Inactive Members"');
                } else {
                    echo "Inactive Members";
                }
                ?> ]</li>

            <li>[ <?php
                if ($status != 'close') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/index/close', 'Closed Members', 'title="Closed Members"');
                } else {
                    echo "Closed Members";
                }
                ?> ]</li>

            <li>[ <?php
                if ($status != 'suspended') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/index/suspended', 'Suspended Members', 'title="Suspended Members"');
                } else {
                    echo "Suspended Members";
                }
                ?> ]</li>
            <li>[ <?php
                if ($status != 'today_join') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/index/today_join', 'Today Join Members', 'title="Today Join Members"');
                } else {
                    echo "Today Join Members";
                }
                ?> ]</li>
            <li>[ <?php
                if ($status != 'online') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/index/online', 'Online Members', 'title="Online Members"');
                } else {
                    echo "Online Members";
                }
                ?> ]</li>
            <li>[ <?php
                if ($status != 'obscene') {
                    echo anchor(ADMIN_DASHBOARD_PATH . '/members/index/obscene', 'Obscene Members', 'title="obscene user"');
                } else {
                    echo "obscene Members";
                }
                ?> ]</li>
        </ul>	  
        <div style="clear:both"></div>
    </div>
    <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin-bottom:10px;">
        <form id="form1" name="form1" method="post" action="">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="220"><strong>Search by Name/Username/Email: </strong></td>
                    <td width="202"><input name="srch" type="text" id="srch" size="30" /></td>
                    <td>
                        <?php
                        $country = $this->input->post('country');
                        if ($country_list):
                            ?>
                            <select name="country">
                                <option value="">---country---</option>
                                <?php
                                foreach ($country_list as $kc => $cntry):
                                    ?>
                                    <option value="<?php echo $cntry->id; ?>" <?php if ($country == $cntry->id) echo 'selected=selected'; ?>><?php echo $cntry->country; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </td>
                    <td><input type="submit" name="Submit" value="Search Member" /></td>
                </tr>
            </table>
        </form>
    </div>    

    <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
        <?php
        if ($this->session->flashdata('message')) {
            echo "<div class='message'>" . $this->session->flashdata('message') . "</div>";
        }
        ?></div>

    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
        <tr> 
            <th align="left"><div align="left">Name</div></th>
            <th align="left"><div align="left">Profile Image</div></th>
            <th align="left"><div align="left">Username</div></th>
            <th align="center"><div align="left">Email</div></th>


            <th align="center"><div align="center">Balance</div></th>
            <th align="center"><div align="center">Bonus</div></th>
            <th align="center"><div align="left">Mark Obscensive </div></th>
            <th colspan="2" align="center" style="border-right:none;"><div align="center">Options</div></th>
        </tr>
        <?php
        if ($this->uri->segment(4))
            $status = $this->uri->segment(4);
        else
            $status = 'active';

        if ($result_data) {
            foreach ($result_data as $data) {
                if ($data->mem_login_state == 1)
                    $bg_color = 'class="online"';
                else
                    $bg_color = '';
                ?>
                <tr <?php echo $bg_color; ?>> 
                    <td <?php echo $bg_color; ?> align="left">
                        <?php if ($data->country_flag): ?><img src="<?php print(base_url($data->country_flag)); ?>" /><?php endif; ?>
                        <?php print($data->first_name . ' ' . $data->last_name); ?>
                    </td>
                    <td>




                        <?php
                        if ($data->gender == 'F') {

                            $profile_image = base_url('assets/images/FEMALE.jpg');
                        } else {
                            $profile_image = base_url('assets/images/MALE.jpg');
                        }
                        ?>

                        <?php if ($data->image) { ?>
                            <img  src="<?php echo base_url(USER_PROFILE_PATH . $data->image); ?>" height="50">
                        <?php } else { ?>
                            <img  src="<?php echo $profile_image; ?>" height="50">

                        <?php } ?>



                    </td>

                    <td align="left"><div align="left"><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH); ?>/members/edit_member/<?php print($status); ?>/<?php print($data->id); ?>">

                                <?php if ($data->reg_type == 'facebook') { ?>
                                    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/facebook-icon.png" />
                                <?php } else if ($data->reg_type == 'google') { ?>
                                    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/google-plus-icon.png" />
                                <?php } else if ($data->reg_type == 'twitter') { ?>
                                    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/twitter-icon.png" />
                                <?php } ?>



                                <?php print($data->user_name); ?></a></div></td>
                    <td align="left"><div align="left"><?php print($data->email); ?></div></td>



                    <td align="left"><div align="center"><?php print($data->balance); ?></div></td>
                    <td align="left"><div align="center"><?php print($data->bonus_points); ?></div></td>
              <!-- <td align="left"><div align="left"><?php //print($this->general->convert_local_time($data->reg_date));   ?></div></td> -->
                    <td>  

                        <?php if ($data->obsence_flag == 'yes') { ?>
                            <a  style="margin-left:5px;" href="<?php echo site_url(ADMIN_DASHBOARD_PATH); ?>/members/mark_safe/<?php print($status); ?>/<?php print($data->id); ?>"><button type="button">Mark safe</button></a>
                        <?php } else { ?>
                            <a  style="margin-left:5px;" href="<?php echo site_url(ADMIN_DASHBOARD_PATH); ?>/members/mark_obscene/<?php print($status); ?>/<?php print($data->id); ?>"><button type="button">Mark Obsene</button></a>
                        <?php } ?>



                    <td colspan="2" align="center" style="border-right:none;">
                        <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH); ?>/members/edit_member/<?php print($status); ?>/<?php print($data->id); ?>" style="margin-right:5px;">
                            <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/edit.gif' title="Edit"></a>   <a  style="margin-left:5px;" href="<?php echo site_url(ADMIN_DASHBOARD_PATH); ?>/members/delete_member/<?php print($status); ?>/<?php print($data->id); ?>">
                            <img  src='<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/delete.gif' title="Delete" onClick="return doconfirm();" ></a>     
                            
                             <a  style="margin-left:5px;" href="<?php echo site_url(ADMIN_DASHBOARD_PATH); ?>/members/ip_address/<?php print($status); ?>/<?php print($data->id); ?>">
                            <img  src='<?php print(ADMIN_IMG_DIR_FULL_PATH); ?>/ip_address.png' title="View IP Address" ></a>
                    </td>
                </tr>
                <?php
            }
            //if ($this->pagination->create_links()) {
                ?>
                <tr> 
                    <td colspan="7" align="center" style="border-right:none;" class="paging"><?php echo $this->pagination->create_links(); ?></td>
                </tr>
                <?php
            //}
        } else {
            ?>
            <tr> 
                <td colspan="7" align="center" style="border-right:none;"> (0) Zero Record Found </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<div class="clear"></div>
</div>

<!-- <script>
$.post( "test.php", function( data ) {
  alert( "Data Loaded: " + data );
});
</script> -->