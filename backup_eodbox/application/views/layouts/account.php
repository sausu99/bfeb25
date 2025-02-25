<?php echo $this->load->view('common/header');?>

		<?php if ($this->uri->segment(2) == 'my-account') { ?>
            <link type="text/css" rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH); ?>crop-select-js.min.css">
            <!--profile pic crop upload js new method-->
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>load-image.js"></script>
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>load-image-scale.js"></script>
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>load-image-meta.js"></script>
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>load-image-fetch.js"></script>
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>load-image-exif.js"></script>
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>load-image-exif-map.js"></script>
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>load-image-orientation.js"></script>
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>jquery.Jcrop.js"></script>
            <script src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH . 'crop_image_js/'); ?>demo.js"></script>
            <link type="text/css" rel="stylesheet" href="<?php echo site_url(MAIN_CSS_DIR_FULL_PATH); ?>jquery.Jcrop.css">
        <?php } ?>
        
<!--============= Hero Section Starts Here =============-->
    <div class="hero-section style-2 pb-lg-400">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>">Home</a>
                </li>
                <li>
                    <a href="<?php echo $this->general->lang_uri('/my-account/user/index'); ?>">My Account</a>
                </li>
                <li>
                    <span><?php echo $account_page_name;?></span>
                </li>
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>banner/hero-bg.png"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->
<?php
$user_profile_imag = $this->session->userdata(SESSION . 'profile_pic');


if ($user_profile_imag!='') {
    $profile_image = site_url(USER_PROFILE_PATH . $user_profile_imag);
    //$rem = '<a href="javascript:void(0)" id="rem_img"> <i class="fa fa-times"></i> </a> ';
} else {
    if ($this->session->userdata(SESSION . 'gender') == 'M') {
        $profile_image = site_url('assets/images/MALE.jpg');
    } else {
        $profile_image = site_url('assets/images/FEMALE.jpg');
    }
}
?>

    <!--============= Dashboard Section Starts Here =============-->
    <section class="dashboard-section padding-bottom mt--240 mt-lg--325 pos-rel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-10 col-md-7 col-lg-4">
                    <div class="dashboard-widget mb-30 mb-lg-0">
                        <div class="user">
                            <div class="thumb-area">
                                <div class="thumb">
                                    <img src="<?php echo $profile_image; ?>" id="profile_img"  alt="user">
                                </div>
                                <button id="change_profile_image" class="profile-pic-edit"><i class="flaticon-pencil"></i></button>
                                
                            </div>
                            <div class="content">
                                <h5 class="title"><?php echo $this->session->userdata(SESSION . 'username');?></h5>
                                <span class="username"><?php echo $this->session->userdata(SESSION . 'first_name');?> <?php echo $this->session->userdata(SESSION . 'last_name');?></span>
                            </div>
                        </div>
                        <ul class="dashboard-menu">
                            <li><a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/index') ?>"  <?php if($account_menu_active=='dashboard') echo 'class="active"'; ?>><i class="flaticon-dashboard"></i>Dashboard</a>
                            </li>
                            <li><a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/edit') ?>" <?php if($account_menu_active=='profile') echo 'class="active"'; ?>><i class="flaticon-user"></i>Personal Profile </a>
                            </li>
                            <li><a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/mobile') ?>" <?php if($account_menu_active=='moible') echo 'class="active"'; ?>><i class="flaticon-user"></i>Personal Mobile </a>
                            </li>
                            <?php if($this->session->userdata(SESSION.'social_id')==""){?>
                            <li><a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/changepassword') ?>" <?php if($account_menu_active=='account_menu_active') echo 'class="active"'; ?>><i class="flaticon-user"></i><?php echo lang('account_change_pass');?></a>
                            </li>
                            <?php }?>
                            <li>
                                <a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/buybids') ?>" <?php if($account_menu_active=='buybids') echo 'class="active"'; ?>><i class="flaticon-bidcredit"></i>My Bid Credits </a>
                            </li>
                            
                            <li><a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/ongoing_auction') ?>" <?php if($account_menu_active=='ongoing_auction') echo 'class="active"';?>><i class="flaticon-auction"></i><?php echo lang('ongoing_auctions'); ?></a></li>
                            <li>
                                <a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/wonauctions') ?>" <?php if($account_menu_active=='wonauctions') echo 'class="active"';?>><i class="flaticon-best-seller"></i>Winning Bids</a>
                            </li>
                            <li><a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/buyauctions') ?>" <?php if($account_menu_active=='my_order') echo 'class="active"';?>><i class="flaticon-shopping-basket"></i><?php echo lang('order_auctions'); ?></a></li>
                            
                            <li>
                                <a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/watchlist') ?>" <?php if($account_menu_active=='watchlist') echo 'class="active"';?>><i class="flaticon-star"></i><?php echo lang('favorite_auctions'); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/refer') ?>" <?php if($account_menu_active=='refer') echo 'class="active"';?>><i class="flaticon-shake-hand"></i><?php echo lang('refer_a_friend'); ?></a>
                            </li>
                            
                            <li><a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/bonuspackage') ?>" <?php if($account_menu_active=='bonuspackage') echo 'class="active"'; ?>><i class="fa fa-gift"></i><?php echo lang('redem_bonus_points'); ?></a></li>
                            
                            <li><a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/cancel') ?>" <?php if($account_menu_active=='cancel') echo 'class="active"'; ?>><i class="fas fa-ban"></i><?php echo lang('delete_account'); ?></a></li>
            				<li><a href="<?php echo $this->general->lang_uri('/users/logout'); ?>"><i class="fa fa-gift"></i><?php echo lang('sign_out'); ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    
                    <?php echo $template['body']; ?>
                </div>
            </div>
        </div>
    </section>
    <!--============= Dashboard Section Ends Here =============-->
 <div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="m_0 text-left">Image Upload</h6>
        <span class="close" data-dismiss="modal">&times;</span>
      </div>
      <div class="modal-body">
        <div class="imageBox">
          <div id="result" class="result">
            <p>This demo works only in browsers with support for the <a href="https://developer.mozilla.org/en/DOM/window.URL">URL</a> or <a href="https://developer.mozilla.org/en/DOM/FileReader">FileReader</a> API.</p>
          </div>
        </div>
        <div class="action">
          <input type="file" id="file-input" class="up_file_decal">
          <label for="file-input">Choose File</label>
          <p id="actions" style="display:none;">
            <button type="button" id="edit" class="btn_crop" style="display:none;">Edit</button>
            <button type="button" id="crop" class="btn_crop">Done</button>
          </p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger main_btn single_bt" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal_overlay" style="display:none;">
    <i class="fa fa fa-spinner fa-spin"></i>
</div>
<script type="text/javascript">
    $('#change_profile_image').on('click', function () {
        $('#myModal').modal('show');
    });
    var upload_url = "<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/upload_cropped_img') ?>";
    var rem_img_url="<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/remove_profile_img') ?>";
    var female_dummy="<?php echo site_url('assets/images/FEMALE.jpg');?>";
    var rem = '<a href="javascript:void(0)" id="rem_img"> <i class="fa fa-times"></i> </a> ';
    var male_dummy="<?php echo site_url('assets/images/MALE.jpg');?>";

        $(document).on('click','#rem_img',function () {
        $("#message").html('<i class="fa fa fa-spinner fa-spin"></i>');
            $('#message').show();
        $.post(rem_img_url, {})
                .done(function (data) {
                    var gend=$('#profile_img').data("gender")
            if(gend=="M"){
                    $("#profile_img").attr('src', male_dummy);
                $("#p_pic").attr('src', male_dummy);
            }else{
                    $("#profile_img").attr('src', female_dummy);
          $("#p_pic").attr('src', female_dummy);        
        }
                 $('#message').hide();
                 $("#rm_img").html('');
                });
    });
</script>
<?php echo $this->load->view('common/footer');?>

