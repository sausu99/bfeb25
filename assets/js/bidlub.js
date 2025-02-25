// JavaScript Document

function filterText(element, val) {

    var validList = ".1234567890";

    //var numberfixed=validList.toFixed(2); 		

    var outString = '';

    var inChar;

    var i;



    for (i = 0; i <= val.length - 1; i++) {

        inChar = val.charAt(i);



        if (validList.indexOf(inChar) != -1) {

            outString = outString + inChar;

        }

    }

    //console.log(outString);	

    $("#" + element).val(outString);

}



$(document).ready(function () {



    //function to prevent pressing of enter

    function stopEnter(evt) {

        var evt = (evt) ? evt : ((event) ? event : null);

        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);

        if ((evt.keyCode == 13) && (node.type == "text") && ((node.name == "amount") || (node.name == "amount_f") || (node.name = "amount_t"))) {

            return false;

        }

    }

    document.onkeypress = stopEnter;







    //single bid function

    $("#singleBidBtn").click(function (e) {

        e.preventDefault();

		var _bttn_name =  $("#singleBidBtn").text();
		$('#bidResponse').hide();
		$('#bidResponseSuccess').hide();
		//alert(_bttn_name);

        if ($("input[name=amount]").val() == '') {

            $('#bidResponse').show();
			$("#bid_error").html("Enter a valid Bid Amount.");

            return false;

        } else

            //console.log(LUB_single_bid_URL);



            jQuery.ajax({

                type: "POST",

                url: LUB_single_bid_URL,

                datatype: 'json',

                beforeSend: function () {

                    $("#singleBidBtn").html('<i class="fa fa fa-spinner fa-spin"></i>');
					
                    $("#singleBidBtn").attr("disabled", true);

                },

                complete: function () {

                    $("#singleBidBtn").removeAttr("disabled");

                    $('#singleBidForm').trigger('reset');

                },

                data: $('#singleBidForm').serialize(),

                success: function (data) {

                    //console.log(data);

                    var response = jQuery.parseJSON(data);

					 $("#singleBidBtn").text(_bttn_name);
                    if (response.status == "success") {
						$('#total_bid_hist').html(response.total_bids);
                        $('#normalCredit').html(response.new_balance);
						$('#bidResponseSuccess').show();
						$("#bid_success").html(response.message);
						user_bid_history();

                    } else {
						
						$('#bidResponse').show();
						$("#bid_error").html(response.message);
						
                    }

                },

            }); //jquery ajax ends here

        return false;

    }); //single button click ends here



    $("#multiBidBtn").click(function (e) {

        e.preventDefault();

		var _bttn_name =  $("#multiBidBtn").text();
		$('#bidResponse').hide();
		$('#bidResponseSuccess').hide();
				
        if ($("input[name=user_id]").val() == '') {

            window.location.href = loginURL;

            return false;

        } else if ($("input[name=amount_f]").val() == '' || $("input[name=amount_t]").val() == '') {

           $('#bidResponse').show();
		   $("#bid_error").html("Enter a valid Bid Amount.");

            return false;

        }



        //console.log(LUB_multi_bid_URL);



        jQuery.ajax({

            type: "POST",

            url: LUB_multi_bid_URL,

            datatype: 'json',

            beforeSend: function () {

                $("#multiBidBtn").html('<i class="fa fa fa-spinner fa-spin"></i>');					
                $("#multiBidBtn").attr("disabled", true);
                $("#multiBidBtn").attr("disabled", true);
            },

            complete: function () {

                $("#multiBidBtn").removeAttr("disabled");

                $('#multiBidForm').trigger('reset');

            },

            data: $('#multiBidForm').serialize(),

            success: function (response) {

                //console.log(response);

                var response = jQuery.parseJSON(response);
				$("#multiBidBtn").text(_bttn_name);
								
                if (response.status == "success") {
					$('#total_bid_hist').html(response.total_bids);
                    $('#normalCredit').html(response.new_balance);
                    $('#bidResponseSuccess').show();
					$("#bid_success").html(response.message);
					user_bid_history();

                } else {

                    $('#bidResponse').show();
					$("#bid_error").html(response.message);

                }

            },

        }); //jquery ajax ends here

        return false;

    });



    //executes code below when user click on pagination links

    $("#pagination").on("click", ".pagination a", function (e) {

        e.preventDefault();

        var page = $(this).attr("data-page"); //get page number from link

        //var page = $(this).data('page'); //get page number from link



        //form.submit();

        jQuery.ajax({

            type: "POST",

            url: LUB_get_user_bids_URL,

            //datatype: 'html',

            dataType: 'json',

            cache: false,

            //beforeSend: function(){ $("#loading-div").show(); },

            //complete: function(){ $("#loading-div").hide();},

            data: {page: page, aid: aid, 'perpage': perpage},

            success: function (data) {

                //console.log(data); //return false;

                //var response = jQuery.parseJSON(data);

                if (data.response == 'success') {



                    $('html, body').animate({scrollTop: $("#bidHistory").offset().top - 100}, 'slow');



                    $('#bidHistory').html(data.bidders_data);

                    $('#pagination').html(data.pagination_nav_data);

                } else {

                    //alert(data);

                    //console.log(data);

                }

            }

        });

        return false; // required to block normal submit since ajax is used

        //alert('clicked '+$(this).data('page'));

    });
	
	$("#pagination_other").on("click", ".pagination a", function (e) {

        e.preventDefault();

        var page = $(this).attr("data-page"); //get page number from link

        //var page = $(this).data('page'); //get page number from link



        //form.submit();

        jQuery.ajax({

            type: "POST",

            url: LUB_get_other_user_bids_URL,

            //datatype: 'html',

            dataType: 'json',

            cache: false,

            //beforeSend: function(){ $("#loading-div").show(); },

            //complete: function(){ $("#loading-div").hide();},

            data: {page: page, aid: aid, 'perpage': perpage},

            success: function (data) {

                //console.log(data); //return false;

                //var response = jQuery.parseJSON(data);

                if (data.response == 'success') {



                    $('html, body').animate({scrollTop: $("#otherUsersBidHistory").offset().top - 100}, 'slow');



                    $('#otherUsersBidHistory').html(data.bidders_data);

                    $('#pagination_other').html(data.pagination_nav_data);

                } else {

                    //alert(data);

                    //console.log(data);

                }

            }

        });

        return false; // required to block normal submit since ajax is used

        //alert('clicked '+$(this).data('page'));

    });

});











function user_bid_history() {

    jQuery.ajax({

        type: "POST",

        url: LUB_get_user_bids_URL,

        //datatype: 'html',

        dataType: 'json',

        cache: false,

        //beforeSend: function(){ $("#loading-div").show(); },

        //complete: function(){ $("#loading-div").hide();},

        data: {page: 1, aid: aid, 'perpage': perpage},

        success: function (data) {

            //console.log(data); //return false;

            //var response = jQuery.parseJSON(data);

            if (data.response == 'success') {

                $('#bidHistory').html(data.bidders_data);

                $('#pagination').html(data.pagination_nav_data);

            } else {

                //alert(data);

                //console.log(data);

            }

        }

    });

    return false; // required to block normal submit since ajax is used

}


function other_users_bid_history() {

    jQuery.ajax({

        type: "POST",
        url: LUB_get_other_user_bids_URL,
        dataType: 'json',
        cache: false,
        data: {page: 1, aid: aid, 'perpage': perpage},
        success: function (data) {

            if (data.response == 'success') {

                $('#otherUsersBidHistory').html(data.bidders_data);

                $('#pagination_other').html(data.pagination_nav_data);

            } else {

                
            }

        }

    });

    return false; // required to block normal submit since ajax is used

}





