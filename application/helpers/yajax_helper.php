<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter Ajax Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		mrigess
 * @link		
 */
// ------------------------------------------------------------------------

/**
 * isAjax
 *
 * Lets you determine whether current request is an ajax request or normal.
 *
 * @access	public
 * @return	bolean	true if ajax else, false
 */
if (!function_exists('is_ajax')) {

    function is_ajax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")?true:false;
    }

}

/**
 * loadJS
 *
 * Includes the Javascript Library Along with the css required to the template
 *
 * @access	public
 * @return	bolean	true if ajax else, false
 */
if (!function_exists('load_js')) {

    function load_js($jsStack) {

        $CI =& get_instance();
        if(!is_array($jsStack)){
            $tmpStack = $jsStack;
            $jsStack = array();
            $jsStack[0] = $tmpStack;
        }
        foreach($jsStack as $k =>$jslib){
            switch($jslib){
                case 'jquery-ui':
                    $CI->template->add_js('js/ui/ui.core.min.js');
                    $CI->template->add_css('js/ui/theme/orange.css');
                    break;
				case 'penny':
				  $CI->template->add_js('js/jquery.js');
				  $CI->template->add_js('js/jquery.timer.js');
					$CI->template->add_js('js/jquery.simplemodal.js');
					$CI->template->add_css('css/modal.css');
				   $CI->template->add_js('js/penny/js.js');
                 break;
				 case 'penny_detail':
				  $CI->template->add_js('js/jquery.js');
				  $CI->template->add_js('js/jquery.timer.js');
					$CI->template->add_js('js/jquery.simplemodal.js');
					$CI->template->add_css('css/modal.css');
				   $CI->template->add_js('js/penny/js_detail.js');
				    $CI->template->add_js('js/jquery.form.js');
                    $CI->template->add_js('js/jquery.validate.js');
                    $CI->template->add_js('js/jquery.validate.more.js');
					 $CI->template->add_js('js/jquery.cycle.all.min.js');
					
                 break;
				  
				 case 'common':
				   $CI->template->add_js('js/jquery.js');
                   $CI->template->add_js('js/jquery.simplemodal.js');
				  
				   $CI->template->add_css('css/modal.css');	
				break;	
				 case 'user':
				   $CI->template->add_js('js/jquery.js');
                 
				    $CI->template->add_js('js/user/function.js');
				 
				   $CI->template->add_js('js/jquery.simplemodal.js');
					$CI->template->add_css('css/modal.css');
                    $CI->template->add_js('js/jquery.validate.js');
					$CI->template->add_js('js/jquery.validate.more.js');
				
					break;	
                case 'lightbox':
                    $CI->template->add_js('js/jquery.lightbox.js');
                    $CI->template->add_css('css/jquery.lightbox.css');
                    break;
                case 'cke':
                    $CI->template->add_js('js/ck/ckeditor_basic.js');
                    $CI->template->add_js('js/ck/jckeditor.js');
                    break;
				 case 'editor':
                    $CI->template->add_js('scripts/jHtmlArea-0.7.0.js');
					 $CI->template->add_css('style/jHtmlArea.css');	
                   
                    break;	
                case 'form':
                    $CI->template->add_js('js/jquery.form.js');
                    $CI->template->add_js('js/jquery.validate.js');
                    $CI->template->add_js('js/jquery.validate.more.js');
                    break;
                case 'chain-select':
                    $CI->template->add_js('js/jquery.chainedSelects.js');
                    break;
                case 'dual-list':
                    $CI->template->add_js('js/dualList/jquery.dualListBox.min.js');
                    $CI->template->add_css('js/dualList/style.css');
                    break;
                case 'masked-input':
                    $CI->template->add_js('js/jquery.maskedinput-1.2.2.js');
                    break;
                case 'autocomplete':
                    //$CI->template->add_js('js/jquery.autocomplete/jquery.jquery.autocomplete.min.js');
                    $CI->template->add_js('js/jquery.autocomplete/jquery.autocomplete.js');
                    $CI->template->add_css('js/jquery.autocomplete/jquery.autocomplete.css');
                    break;
            }
        }

    }

}


/* End of file ajax_helper.php */
/* Location: ./system/helpers/ajax_helper.php */