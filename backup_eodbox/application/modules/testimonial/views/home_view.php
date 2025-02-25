	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?php echo MAIN_JS_DIR_FULL_PATH;?>jquery.fancybox.js?v=2.1.3"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo MAIN_CSS_DIR_FULL_PATH;?>jquery.fancybox.css?v=2.1.2" media="screen" />
<script type="text/javascript">
		
		function single_img(img)
			{
				$.fancybox.open(img);
			}
	</script>
<section id="product-sec">
<h1><?php echo $this->lang->line('all_testimonial');?></h1>
<div class="testimonial-area">

<?php if($testimonial_data){foreach($testimonial_data as $testimonial){?>
<article>
<figure>  <img src="<?php echo site_url(TESTIMONIAL_PATH.'thumb_'.$testimonial->image);?>" onclick="single_img('<?php echo site_url(TESTIMONIAL_PATH.$testimonial->image);?>')"  /></figure>
<section>
  <div class="t-textbg"><?php echo $testimonial->description;?></div>
<ul>

<li><?php echo $testimonial->winner_name;?></li>
<li><span><?php echo $this->lang->line('footer_item_name');?>:</span> <?php echo $testimonial->product_name;?></li>
</ul> 
</section>
</article>
<?php }}else{?>
<div class="abt-secbg" align="center">
		<?php echo $this->lang->line('all_0record_found');?>
		</div>

<?php }?>
</div>
<div class="h-shaing"></div>

</section>