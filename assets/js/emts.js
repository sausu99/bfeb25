function get_timer(timerHolder, id, show_time, counter_format1, counter_format2) {
//    var auction_closed='Auction Closed';
    $('#' + timerHolder).everyTime('1s', function (i) {
//        console.log(show_time);
        if ((show_time - i) > 0) {
            var result = formateDate(show_time - i, counter_format1, counter_format2);
            //console.log(result);
            $('#' + timerHolder).html(result);
        } else {
            $('#' + id).hide();
            $('#' + timerHolder).html('<time><strong>' + 'Auction Closed' + '</strong></time>');
            //$('#timer_'+id).hide();
        }
    }, show_time);
}

function formateDate(time_left, counter_format1, counter_format2) {
//    if (time_left >= 0 && time_left <= 60)
//        return '<span><em>' +lang.day + '</em>' + '00' + '</span>' + '<span><em>' + lang.hour + '</em>' + '00' + '</span><span><em>' + lang.minute + '</em>' + '00' + '</span><span><em>' + lang.seconds + '</em>' + time_left + '</span>';
//        return '<span>' + time_left + '</span> ' + lang.seconds;

    var oneMinute = 60;
    var oneHour = oneMinute * 60;
    var oneDay = oneHour * 24;

    var dayfield = Math.floor(time_left / oneDay);
    var hourfield = Math.floor((time_left - dayfield * oneDay) / oneHour);
    var minutefield = Math.floor((time_left - dayfield * oneDay - hourfield * oneHour) / oneMinute);
    var secondfield = Math.floor((time_left - dayfield * oneDay - hourfield * oneHour - minutefield * oneMinute));

    if (dayfield > 0 && dayfield < 10) {
        dayfield = "0" + dayfield;
    } else if (dayfield > 0 && dayfield >= 10) {
        dayfield = dayfield;
    } else {
        dayfield = "00";
    }

    if (hourfield < 10)
        hourfield = "0" + hourfield;
    if (minutefield < 10)
        minutefield = "0" + minutefield;
    if (secondfield < 10)
        secondfield = "0" + secondfield;

	
	 if (dayfield == 00) {
            if (hourfield == 00) {
                if (minutefield == 00) {
                    //return secondfield + ' sec';
                   
                    return minutefield + ' m :' + secondfield + ' s';
                }
                //return minutefield + ':' + secondfield;
                return hourfield + 'h : ' + minutefield + ' m :' + secondfield + ' s';
            }
            //return hourfield + ':' + minutefield + ':' + secondfield;
            return dayfield + 'd : ' + hourfield + 'h : ' + minutefield + ' m :' + secondfield + ' s';
        } else {
            //return dayfield + ':' + hourfield + ':' + minutefield + ':' + secondfield;
           return dayfield + 'd : ' + hourfield + 'h : ' + minutefield + ' m :' + secondfield + ' s';
        }
   
}
   