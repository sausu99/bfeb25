<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function form_fckeditor($name = '', $value = '', $extra = '')
{
			include_once("./assets/ckeditor/ckeditor.php");
			include_once("./assets/ckfinder/ckfinder.php");
			
			 
			
			
			$ckeditor = new CKEditor();
			$ckeditor->basePath	= site_url().'assets/ckeditor/';
    		
            $config = array();
            $config['width']=705;
            $config['height']=400;
			
			$parse_url = parse_url(site_url());

			$ckfinder_basepath   = $parse_url['path'].'assets/ckfinder/';
			
            CKFinder::SetupCKEditor($ckeditor,$ckfinder_basepath);
			
            return $ckeditor->editor($name,$value,$config);
    
}

?>