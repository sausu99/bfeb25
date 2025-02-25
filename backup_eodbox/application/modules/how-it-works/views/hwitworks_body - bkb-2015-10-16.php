<script type="text/javascript">

$(function () {
 $("#slider4").responsiveSlides({
        auto: true,
        pager: true,
        nav: true,
        speed: 500,
      });
     
    });
</script>
  
<section id="product-sec">
<h1><?php echo lang('label_how_it_works');?></h1>
<div class="howit-area">
  <?php if($hwitworks){ ?>
 

      <ul class="rslides" id="slider4">
       <?php foreach($hwitworks as $hwitwork){?>
        <li>
         <img src="<?php echo site_url($hwitwork->banner);?>" alt="how it works" />
   </li>
        <?php }?>       
      </ul>

 
      
    
 
     
    <?php }else{?>
	<div class="nohwitwork">There is no content.</div>
	<?php }?>

</div>
</div>
<div class="h-shaing"></div>

</section>

<!--footer sec end-->
<!-- Javascripts --> 
<script type="text/javascript" src="<?php echo MAIN_JS_DIR_FULL_PATH;?>jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php echo MAIN_JS_DIR_FULL_PATH;?>jquery.cycle.all.min.js"></script> 
<script type="text/javascript" src="<?php echo MAIN_JS_DIR_FULL_PATH;?>init.js"></script>