/*##############################
 * Drag and Drop
 ##############################*/
/**
 * 
 */
$('.location').on('drop', function(ev) {
    
    drop(ev)
})

/**
 * 
 */
$('.location').on('dragover', function(ev) {
    allowDrop(ev)
})

/**
 * 
 */
$('span[draggable=true]').on('dragstart', function(event) {
    drag(event, $(this).attr('id'))
})

/**
 * 
 * @param {type} ev
 * @returns {undefined}
 */
function allowDrop(ev) {
    ev.preventDefault();
}

/*
 * 
 * @param {type} ev
 * @param {type} id
 * @returns {undefined}
 */
function drag(ev, id) {
    ev.originalEvent.dataTransfer.setData("Text", ev.target.id);


}

function drop(ev) {

    ev.stopPropagation()
    ev.preventDefault()
    
    var data = ev.originalEvent.dataTransfer.getData("Text")
    var parent = $(ev.target)
    var newchild = document.getElementById(data).cloneNode(true)
    var campus
    var building
    var room
    var image_name
    var location

    
//make sure it's dropped on the right parent
    if ($(parent).hasClass('location')) {
        
        newchild.id = ''
        campus = newchild.getAttribute('data-campus') ? newchild.getAttribute('data-campus') : ''
        building = newchild.getAttribute('data-building') ? newchild.getAttribute('data-building') : ''
        room = newchild.getAttribute('data-room') ? newchild.getAttribute('data-room') : ''
        location = (building ? campus + "/" : campus) + (room ? building + "/" : building) + room
        newchild.innerHTML = location?location:"GWU"
        

        //fix the child's image name and make sure it is formatted properly
        image_name = encodeURIComponent($(parent).attr('data-image'))

        $(newchild).attr('data-image', location +"/"+ image_name)
        //time to update...
        values = {campus: campus, building: building, room: room, image_name: image_name}

        ajaxsubmitnewlocation(values, $(parent), newchild);

        //bind the new child to the click event just in case the user wants to delete it before a refresh
        $(newchild).bind('click', function() {
            var image = $(this).attr('data-image')
            ajaxremovelocation({image_name: image}, $(this));
        })
        
    }
}

/**
 * 
 * @param {type} params
 * @param {type} target
 * @param {type} source
 * @returns {undefined}
 */
function ajaxsubmitnewlocation(params, target, source) {
    $.ajax({
        type: 'POST',
        url: addlocationajaxurl,
        data: {args: params},
        beforeSend: function() {
// this is where we append a loading image
            $('#ajax_panel').html('<div  id="ajax_background"><div id="loading"><img src="' + images + 'loading.gif" alt="Loading..." /></div></div>');
            //$('#ajax_background').fadeIn();
        },
        success: function(data) {
            var items = [];
            // successful request; do something with the data
            $('#ajax_background').fadeOut();
            $('#ajax_panel').html('');
            
            target.append(source);
            target.removeClass('well');
            console.log(data);
        },
        error: function(data) {
            $('#ajax_panel').html('');
            console.log(data);
            // failed request; give feedback to user
            console.log('Oops! Try that again in a few moments.');
        }
    });
}

$('.image-location').bind('click', function() {
    var image = $(this).attr('data-image')
    ajaxremovelocation({image_name: image}, $(this));
})

/**
 * 
 * @param {type} params
 * @param {type} target
 * @returns {undefined}
 */
function ajaxremovelocation(params, target) {
    $.ajax({
        type: 'POST',
        url: removelocationajaxurl,
        data: params,
        beforeSend: function() {
// this is where we append a loading image
            $('#ajax_panel').html('<div  id="ajax_background"><div id="loading"><img src="' + images + 'loading.gif" alt="Loading..." /></div></div>');
            //$('#ajax_background').fadeIn();
        },
        success: function(data) {
            var items = [];
            // successful request; do something with the data
            $('#ajax_background').fadeOut();
            $('#ajax_panel').html('');
            target.fadeOut('', function() {
                target.remove()
            });
            console.log(data);
        },
        error: function(data) {
            $('#ajax_panel').html('');
            // failed request; give feedback to user
            console.log(data)
            console.log('Oops! Try that again in a few moments.');
        }
    });
}