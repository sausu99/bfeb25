function vars(){
	this.servertime = 0;	
}
ds = new vars();
var init = function(){
        $.ajax({
            url: siteurl+"servertimer",
            cache:false,
            async:false,
            dataType: 'text',
            success: function(resp){
				
                ms_server_time = parseInt(resp,10);
                ds.servertime = ms_server_time;
             
            }
        });
    }
/*Server clock in local updater function, based on caller's recursion*/
var updateTimer = function(){
	ds.servertime = ds.servertime+1; //update every seconds to simulate a clock
	$('#clock').html(formattedDate(ds.servertime));
}
var hourfield;
var minutefield;
var secondfield;
var ap;

var formattedDate = function(){
	if(ds.servertime >= 3600)
{
    hourfield = Math.floor(ds.servertime/3600);
	
secondfield = ds.servertime % 3600;
}
else
{
    hourfield = 0;
	secondfield = ds.servertime % 3600;
}	

if(secondfield >= 60)
	{
    minutefield = Math.floor(secondfield/60);
    secondfield = secondfield%60;
	}
else
{
    minutefield = 0;
}






	if(hourfield < 10)
		hourfield="0"+hourfield;
	if(minutefield < 10)
		minutefield="0"+minutefield;
	if(secondfield < 10)
		secondfield="0"+secondfield;
	return hourfield+':'+minutefield+':'+secondfield;
}

/*Usage*/
init();
setInterval("updateTimer()",1000);