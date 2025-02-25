

$(document).ready(function() {
  $.ajaxSetup({ cache: true });
  $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
    FB.init({
      appId: '219594195184352',
      version: 'v2.10' // or v2.1, v2.2, v2.3, ...
    }); 



  });
});

  

function share_on_fb(){
	FB.ui({
  method: 'share',
  href:share_url,
  scope: 'publish_actions', 
    return_scopes: true
}, function(response){

	console.log(response);

});

}










  // function share_on_fb() {

  

  //   var url="https://www.facebook.com/sharer/sharer.php?u="+share_url;

  //   window.open(url, "myWindow", "status = 1, height = 400, width = 300, resizable = 0" )

  // }

  function share_on_gl() {

  

    var url="https://plus.google.com/share?url="+share_url;

    window.open(url, "myWindow", "status = 1, height = 400, width = 300, resizable = 0" )

  }


