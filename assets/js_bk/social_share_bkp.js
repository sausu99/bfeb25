

  function share_on_fb() {

  

    var url="https://www.facebook.com/sharer/sharer.php?u="+share_url;

    window.open(url, "myWindow", "status = 1, height = 400, width = 300, resizable = 0" )

  }

  function share_on_gl() {

  

    var url="https://plus.google.com/share?url="+share_url;

    window.open(url, "myWindow", "status = 1, height = 400, width = 300, resizable = 0" )

  }




 //   // Social media sharing links
	// $(document).ready( function($) {
 //     //social sharings
 //    //'use strict';

 //    // Share Icons
 //    $.fn.socShare = function(opts) {

 //      var $this = this;
 //      var $win = $(window);

 //      opts = $.extend({
 //        attr : 'href',
 //        facebook : false,
 //        google_plus : false,
 //        twitter : false,
 //        linked_in : false,
 //        pinterest : false
 //      }, opts);

 //      for(var opt in opts) {

 //        if(opts[opt] === false) {
 //          continue;
 //        }
 //        switch (opt) {

 //          case 'facebook':
 //          var url = 'https://www.facebook.com/sharer/sharer.php?u=';
 //          var name = 'Facebook';
 //          _popup(url, name, opts[opt], 400, 640);
 //          break;

 //          case 'twitter':
 //          // var posttitle = $(".sbtwitter a").data("title");
 //          // var url = 'https://twitter.com/intent/tweet?text='+posttitle+'&url=';
 //          var url = 'https://twitter.com/intent/tweet?url=';
 //          var name = 'Twitter';
 //          _popup(url, name, opts[opt], 440, 600);
 //          break;

 //          case 'google_plus':
 //          var url = 'https://plus.google.com/share?url=';
 //          var name = 'Google+';
 //          _popup(url, name, opts[opt], 600, 600);
 //          break;

 //          case 'linked_in':
 //          var url = 'https://www.linkedin.com/shareArticle?mini=true&url=';
 //          var name = 'LinkedIn';
 //          _popup(url, name, opts[opt], 570, 520);
 //          break;

 //          case 'pinterest':
 //          var url = 'https://www.pinterest.com/pin/find/?url=';
 //          var name = 'Pinterest';
 //          _popup(url, name, opts[opt], 500, 800);
 //          break;
 //          default:
 //          break;
 //        }
 //      }

 //      function isUrl(data) {
 //        var regexp = new RegExp( '(^(http[s]?:\\/\\/(www\\.)?|ftp:\\/\\/(www\\.)?|(www\\.)?))[\\w-]+(\\.[\\w-]+)+([\\w-.,@?^=%&:/~+#-]*[\\w@?^=%&;/~+#-])?', 'gim' );
 //        return regexp.test(data);
 //      }

 //      function _popup(url, name, opt, height, width) {
 //        if(opt !== false && $this.find(opt).length) {
 //          $this.on('click', opt, function(e){
 //            e.preventDefault();

 //            var top = (screen.height/2) - height/2;
 //            var left = (screen.width/2) - width/2;
 //            var share_link = $(this).attr(opts.attr);

 //            if(!isUrl(share_link)) {
 //              share_link = window.location.href;
 //            }

 //            window.open(
 //              url+encodeURIComponent(share_link)+"?share_id="+en_text,
 //              name,
 //              'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height='+height+',width='+width+',top='+top+',left='+left
 //              );
            

 //            return false;
 //          });
 //        }

 //      }
     
 //      return;
 //    };

 //    $('.sb_share').socShare({
      
 //      facebook : '.sbsoc-fb',
 //      twitter : '.sbsoc-tw',
 //      google_plus : '.sbsoc-gplus',
 //      linked_in : '.sbsoc-linked',
 //      pinterest : '.sbsoc-pinterest'

 //    });
 //  });