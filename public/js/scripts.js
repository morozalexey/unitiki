$(document).ready(function(){

var inProgress = false;
var startFrom = 10;

    $(window).scroll(function() {

        if($(window).scrollTop() + $(window).height() >= $(document).height() - 200 && !inProgress) {

        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: {"startFrom" : startFrom},
            beforeSend: function() {
            inProgress = true;}
            }).done(function(data){

            data = jQuery.parseJSON(data);

            if (data.length > 0) {

            $.each(data, function(index, data){

            $("#articles").append("<p><b>" + data.title + "</b><br />" + data.text + "</p>");
            });

            inProgress = false;
            startFrom += 10;
            }});
        }
    });
});
