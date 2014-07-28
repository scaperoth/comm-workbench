//start by binding elements
bind_drag_and_drop()
bind_location_removal()
/*##############################
 * Drag and Drop Events
 ##############################*/

/**
 * 
 * @returns {undefined}
 */
function bind_drag_and_drop() {
    /**/
    $('.location').on('drop', function(ev) {
        drop(ev)
    })
    /**/
    $('.location').on('dragover', function(ev) {
        allowDrop(ev)
    })
    /**/
    $('span[draggable=true]').on('dragstart', function(event) {
        drag(event, $(this).attr('id'))
    })

}
/**
 * 
 * @returns {undefined}
 */
function unbind_drag_and_drop() {
    $('.location').unbind('drop')
    /**/
    $('.location').unbind('dragover')
    /**/
    $('.location').unbind('dragstart')
}

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

/**
 * 
 * @param {type} ev
 * @returns {drop}
 */
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
        newchild.innerHTML = location ? location : "GWU"
        newchild.className = newchild.className + " image-location";

        //fix the child's image name and make sure it is formatted properly
        image_name = encodeURIComponent($(parent).attr('data-image'))

        $(newchild).attr('data-image', location + "/" + image_name)
        //time to update...
        values = {campus: campus, building: building, room: room, image_name: image_name}

        ajaxsubmitnewlocation(values).done(function(ajax_data) {
            $(parent).append(newchild);
            $(parent).removeClass('well');
            bind_location_removal() //add events to newly created object
            //console.log(ajax_data);
        }).fail(function(data) {
            console.log("NEW LOCATION FAIL")
            //console.log(data);
        })

        //bind the new child to the click event just in case the user wants to delete it before a refresh
    }
}

/*##############################
 * Form and Click Events
 ##############################*/
function bind_location_removal() {
    var length

    $('.image-location').unbind('click')
    $('.image-location').on('click', function() {
        var image = $(this).attr('data-image')
        $this = $(this)
        ajaxremovelocation({image_name: image}).done(function(data) {
            //console.log(data);
            $this.fadeOut('', function() {
                $parent = $(this).parent('.location')
                $this.remove()
                check_is_empty($parent)
            });

        }).fail(function(data) {
            console.log("REMOVAL FAIL")
            //console.log(data)
        });


    })


}

/**
 * bind change function to sidebar select boxes
 */
$('select[data-script=location_load].sidebar-select').each(function() {
    $(this).change(function() {
        //select parameters
        var group = $(this).attr('data-group');
        var target_name = $(this).attr('data-target');
        var target = $('select[data-type="' + target_name + '"][data-group="' + group + '"]');
        var values;

        //selection specific-items
        var campus_select = $("#AddimageForm_campus")
        var building_select = $("#AddimageForm_building")
        var campus = campus_select.val() ? campus_select.val() : ""
        var building = building_select.val() ? building_select.val() : ""
        var room = null

        //loop-specific data
        var building_data = null
        var room_data = null

        //ajax return data and arrays to fill
        var ajax_data
        var selectitems = [];
        var draganddropitems = [];

        target.html('');

        if ($(this).val() != '') {
            //get select value for each piece
            campus = $('select[data-type="campus"][data-group="' + group + '"]').val();

            building = $('select[data-type="building"][data-group="' + group + '"]').val();

            //place data into object to send to ajax
            values = {campus: campus, building: building};

            ajaxgetlocations(values).done(function(ajax_data) { //send data, get response
                //animate target
                target.effect("highlight");
                target.focus();

                selectitems.push("<option value=''></option>");

                $.each(ajax_data, function(i) {
                    //if building is not selected, there will be no room
                    room_data = building ? i : ""
                    //if room is not the iterator, building is the iterator
                    building_data = room_data ? building : i

                    //build out select options
                    selectitems.push("<option value='" + i + "'>" + i + "</option>");
                    draganddropitems.push('<span id="drag_' + i + '"  class=" col-lg-2 col-md-4 col-sm-4 col-xs-10 bottom10 right5 label label-primary medium-font" data-campus="' + campus + '" data-building="' + building_data + '" data-room="' + room_data + '" draggable="true" >' + i + '</span>')

                });

                //if there's a target, set new data (in the case of building)
                target.html(selectitems.join(""));
                //set new bucket_list data
                $('#bucket_list').html(draganddropitems.join(''))

                //unbind/rebind events for select (to keep from event stacking)
                unbind_drag_and_drop()
                bind_drag_and_drop()

            }).fail(function(data) {
                //catch an error
                console.log('fail')
                console.log(data)
            })
        }
    })
});


/*##############################
 * Ajax Calls
 ##############################*/

/*
 * this function takes parameters and adds an image to the given location 
 * @param {type} params
 * @returns {unresolved}
 */
function ajaxsubmitnewlocation(params) {
    return $.ajax({
        type: 'POST',
        url: addlocationajaxurl,
        data: {args: params},
        beforeSend: function() {
// this is where we append a loading image
            $('#ajax_panel').html('<div  id="ajax_background"><div id="loading"><img src="' + images + 'loading.gif" alt="Loading..." /></div></div>');
        },
        success: function(data) {
            // successful request; do something with the data
            $('#ajax_background').fadeOut();
            $('#ajax_panel').html('');
        },
        error: function(data) {
            $('#ajax_panel').html('');

        }
    });
}



/**
 * this function removes a given location from the local file system
 * @param {type} params
 * @param {type} target
 * @returns {undefined}
 */
function ajaxremovelocation(params) {
    return $.ajax({
        type: 'POST',
        url: removelocationajaxurl,
        data: params,
        beforeSend: function() {
// this is where we append a loading image
            $('#ajax_panel').html('<div  id="ajax_background"><div id="loading"><img src="' + images + 'loading.gif" alt="Loading..." /></div></div>');
            //$('#ajax_background').fadeIn();
        },
        success: function(data) {
            // successful request; do something with the data
            $('#ajax_background').fadeOut();
            $('#ajax_panel').html('');
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

/**
 * this function searches with the given params and returns a list of locations
 * @param {type} params
 * @returns {@exp;$@call;ajax}
 */
function ajaxgetlocations(params) {

    return $.ajax({
        type: 'POST',
        url: getlocationajaxurl,
        data: {args: params},
        beforeSend: function() {
// this is where we append a loading image
            $('#ajax_panel').html('<div  id="ajax_background"><div id="loading"><img src="' + images + 'loading.gif" alt="Loading..." /></div></div>');
            //$('#ajax_background').fadeIn();
        },
        success: function(data) {
            // successful request; do something with the data
            $('#ajax_background').fadeOut();
            $('#ajax_panel').html('');
            //console.log(target.html());
        }, error: function(data) {
            $('#ajax_panel').html('');

        }
    });
}