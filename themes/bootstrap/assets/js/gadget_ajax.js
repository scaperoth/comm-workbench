/**
 * ajax that only relates directly to the gadget section(s)
 */
$('select[data-script=location_load]').each(function() {
    $(this).change(function() {
        
        var group = $(this).attr('data-group');
        var type = $(this).attr('data-type');
        
        if(type == 'campus'){
            $('select[data-type="building"][data-group="' + group + '"]').html('');
        }
       
        
        var target_name = $(this).attr('data-target');
        var target = $('select[data-type="'+target_name+'"][data-group="' + group + '"]');
        
        var campus = $('select[data-type="campus"][data-group="' + group + '"]').val();

        var building = $('select[data-type="building"][data-group="' + group + '"]').val();

        var values = {campus: campus, building: building};
        ajax(values, target);
    })
});

function ajax(params, target) {
    console.log(params);
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {args: params},
        beforeSend: function() {
// this is where we append a loading image
            $('#ajax_panel').html('<div class="loading"><img src="' + images + 'loading.gif" alt="Loading..." /></div>');
        },
        success: function(data) {
            var items = [];
            // successful request; do something with the data
            $('#ajax_panel').html('');
            items.push("<option value=''></option>");

            $.each(data, function(i) {
                items.push("<option value='" + i + "'>" + i + "</option>");
            });

            target.html(items.join(""));
            console.log(target.html());
        },
        error: function() {
            $('#ajax_panel').html('');
            // failed request; give feedback to user
            console.log('Oops! Try that again in a few moments.');
        }
    });
}