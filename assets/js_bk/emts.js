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


    if (counter_format1 == '1')
    {		
        //console.log('ss');
        if (dayfield == '01' || dayfield == '1' || dayfield == '00')
            day_text = lang.day;
        else
            day_text = lang.days;

        if (hourfield == '01' || hourfield == '1' || hourfield == '00')
            hour_text = lang.hour;
        else
            hour_text = lang.hours;

        if (secondfield == '01' || secondfield == '1' || secondfield == '00')
            sec_text = lang.second;
        else
            sec_text = lang.seconds;

        if (minutefield == '01' || minutefield == '1' || minutefield == '00')
            min_text = lang.minute;
        else
            min_text = lang.minutes;
     
        return '<span><em>' + day_text + '</em>' + dayfield + '</span>' + '<span><em>' + hour_text + '</em>' + hourfield + '</span><span><em>' + min_text + '</em>' + minutefield + '</span><span><em>' + sec_text + '</em>' + secondfield + '</span>';
    } else if (counter_format2 == '2')
    {
        //console.log('nn');
        if (dayfield != 00) //<span><em>DAYS</em> 04</span>
            return '<span>' + dayfield + ' ' + lang.days + ' </span>' + '<span>' + hourfield + ' : </span><span>' + minutefield + ' : </span><span>' + secondfield + '</span>';
        else if (hourfield != 00) //<span><em>DAYS</em> 04</span>
            return '<span>' + hourfield + ' : </span><span>' + minutefield + ' : </span><span>' + secondfield + '</span>';
        else if (minutefield != 00) //<span><em>DAYS</em> 04</span>
            return '</span><span>' + minutefield + ' : </span><span>' + secondfield + '</span>';
        else if (secondfield != 00) //<span><em>DAYS</em> 04</span>
            return '</span><span>' + secondfield +' '+lang.seconds+'</span>';
    } else if (counter_format2 == '3')
    {
        //console.log('nn');
        if (dayfield != 00) //<span><em>DAYS</em> 04</span>
            return '<span>' + dayfield + ' ' + lang.days + ' </span>' + '<span>' + hourfield + ' : </span><span>' + minutefield + ' : </span><span>' + secondfield + '</span>';
        else if (hourfield != 00) //<span><em>DAYS</em> 04</span>
            return '<span>' + hourfield + ' : </span><span>' + minutefield + ' : </span><span>' + secondfield + '</span>';
        else if (minutefield != 00) //<span><em>DAYS</em> 04</span>
            return '</span><span>' + minutefield + ' : </span><span>' + secondfield + '</span>';
        else if (secondfield != 00) //<span><em>DAYS</em> 04</span>
            return '</span><span>00:</span><span>' + secondfield +'</span>';
        else 
            return '</span><span>Closed</span>';
    } 
    else
    {
        //console.log('yy');
        if (dayfield == 00) {
            if (hourfield == 00) {
                if (minutefield == 00) {
                    //return secondfield + ' sec';
                   
                    return '<span>' + secondfield + '</span> ' + lang.seconds;
                }
                //return minutefield + ':' + secondfield;
                return '<span>' + minutefield + '</span> ' + lang.minutes + ' :<span>' + secondfield + '</span> ' + lang.seconds;
            }
            //return hourfield + ':' + minutefield + ':' + secondfield;
            return '<span>' + hourfield + '</span> ' + lang.hour + ' :<span>' + minutefield + '</span> ' + lang.minutes + ' :<span>' + secondfield + '</span> ' + lang.seconds;
        } else {
            //return dayfield + ':' + hourfield + ':' + minutefield + ':' + secondfield;
            return '<span>' + dayfield + '</span> ' + lang.days + '<span>' + hourfield + '</span>:<span>' + minutefield + '</span>:<span>' + secondfield + '</span>' + lang.seconds;
        }
    }
}
   