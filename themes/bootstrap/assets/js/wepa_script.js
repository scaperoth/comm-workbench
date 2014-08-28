/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$('.btn-toggle').click(function() {
    $(this).find('.btn').toggleClass('active');

    if ($(this).find('.btn-primary').size() > 0) {
        $(this).find('.btn').toggleClass('btn-primary');
    }
    if ($(this).find('.btn-danger').size() > 0) {
        $(this).find('.btn').toggleClass('btn-danger');
    }
    if ($(this).find('.btn-success').size() > 0) {
        $(this).find('.btn').toggleClass('btn-success');
    }
    if ($(this).find('.btn-info').size() > 0) {
        $(this).find('.btn').toggleClass('btn-info');
    }

    $(this).find('.btn').toggleClass('btn-default');
    ajaxtogglewepaoutage().done(function(data) {
        if(data==1)
            document.getElementById("outage-status").innerHTML = 'On';
        else
            document.getElementById("outage-status").innerHTML = 'Off';
        console.log(data)
    }).fail(function(data) {
        console.log(data)
    });
});