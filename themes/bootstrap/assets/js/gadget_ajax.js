//start by binding elements
bind_drag_and_drop()
bind_location_pre_removal()
/*##############################
 * Drag and Drop Events
 ##############################*/

/**********************\
 |*Image section Events*|
 \**********************/
/**
 * 
 * @returns {undefined}
 */
function bind_drag_and_drop() {
    var page = get('page_id');
    /**/
    $('.dropper').on('drop', function(ev) {
        if (page == 'location') {
            drop_image_onto_location(ev)
        } else
            drop_location_onto_image(ev)
    })
    /**/
    $('.dropper').on('dragover', function(ev) {
        allowDrop(ev)
    })
    /**/
    $('a[draggable=true]').on('dragstart', function(event) {
        drag(event, $(this).attr('id'))
    })

}
/**
 * 
 * @returns {undefined}
 */
function unbind_drag_and_drop() {
    $('.dropper').unbind('drop')
    /**/
    $('.dropper').unbind('dragover')
    /**/
    $('.dropper').unbind('dragstart')
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
function drop_location_onto_image(ev) {

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
    if ($(parent).hasClass('dropper')) {

        newchild.id = ''
        campus = newchild.getAttribute('data-campus') ? newchild.getAttribute('data-campus') : ''
        building = newchild.getAttribute('data-building') ? newchild.getAttribute('data-building') : ''
        room = newchild.getAttribute('data-room') ? newchild.getAttribute('data-room') : ''
        location = (building ? campus + "/" : campus) + (room ? building + "/" : building) + room
        
        newchild.id = "trashable_"+newchild.innerHTML
        newchild.innerHTML = location ? location : "GWU"
        newchild.className = newchild.className + " image-location";
        newchild.setAttribute("draggable", true)

        //fix the child's image name and make sure it is formatted properly
        image_name = encodeURIComponent($(parent).attr('data-image'))
        newchild.id = newchild.id+"_"+image_name
        
        $(newchild).attr('data-image', location + "/" + image_name)
        //time to update...
        values = {campus: campus, building: building, room: room, image_name: image_name}

        ajaxsubmitnewlocation(values).done(function(ajax_data) {
            $(parent).append(newchild);
            $(parent).removeClass('well');
            bind_location_pre_removal() //add events to newly created object
            //console.log(ajax_data);
        }).fail(function(data) {
            console.log("NEW LOCATION FAIL")
            //console.log(data);
        })

        //bind the new child to the click event just in case the user wants to delete it before a refresh
    }
}

function drop_image_onto_location(ev) {
    ev.stopPropagation()
    ev.preventDefault()

    var data = ev.originalEvent.dataTransfer.getData("Text")

    var parent = $(ev.target)
    var newchild = document.getElementById(data).cloneNode(true)
    var db
    var root
    var campus
    var building
    var room
    var image_name
    var location

    //make sure it's dropped on the right parent
    if ($(parent).hasClass('dropper')) {
        root = parent.attr('data-root')
        location = parent.attr('data-location')
        campus = parent.attr('data-campus')
        building = parent.attr('data-building')
        room = ''
        db = dbstructure
        image_name = encodeURIComponent($(newchild).attr('data-image'))

        var newitem = [];

        newchild.id = ''
        switch (root) {
            case 'root':
                params = {campus: location, building: building, room: room, image_name: image_name}
                location = (building ? location + "/" : location) + (room ? building + "/" + room + "/" : building)
                break;
            case 'subfolder':
                params = {campus: campus, building: location, room: room, image_name: image_name}
                location = (location ? location + "/" : location) + (room ? location + "/" : location) + room
                break;
            case 'bottomfolder':
                params = {campus: campus, building: building, room: location, image_name: image_name}
                location = (building ? campus + "/" : campus) + (location ? building + "/" : building) + location
                break;
            default:
                params = {campus: campus, building: building, room: room, image_name: image_name}
                location = (building ? campus + "/" : campus) + (room ? building + "/" : building) + room
                break;

        }


        $(parent).attr('data-image', location + image_name)

        var data_image = location + image_name

        ajaxsubmitnewlocation(params).done(function(ajax_data) {
            console.log(ajax_data)

            newitem.push("<div>")
            newitem.push('<a class="col-xs-1 pre-delete imager trashable dropper" draggble="true" data-image="' + data_image + '" href="#?javascript:void(0)">')
            newitem.push(newchild.outerHTML)
            newitem.push('<h2 class="image-overlay"><span>Delete?</span></h2>')
            newitem.push('</a>')
            newitem.push('</div>')
            $(parent).append(newitem.join(""));
            $(parent).removeClass('well');
            bind_location_pre_removal() //add events to newly created object
            //console.log(ajax_data);
        }).fail(function(data) {
            console.log(data);
            console.log("NEW LOCATION FAIL")
            //console.log(data);
        })

        //bind the new child to the click event just in case the user wants to delete it before a refresh
    }
}

/*##############################
 * Form and Click Events
 ##############################*/

/**
 * binds the element to a transitional click step
 * this step lets the user know they are about to delete the element
 * @returns {undefined}  
 */
function bind_location_pre_removal() {
    $('.pre-delete').off('click')
    $('.pre-delete').on('click', function() {
        console.log('clicker');
        $this = $(this)
        $canceller = $this

        if ($this.hasClass('imager')) {
            $canceller = $this.children('img')
            $this.children('.image-overlay').animate({
                opacity: 1,
            });
        } else {
            $this.text("delete?")
        }

        $this.addClass('image-location')
        $this.removeClass('pre-delete')
        $this.off('click')

        bind_close_removal($canceller)
        bind_location_removal($this)


    })
}
/**
 * binds the div to the removal function to remove a
 * location from an image (but in the db it is vice versa)
 * @returns {undefined}
 */
function bind_location_removal(obj) {
    
    obj.on('click', function() {
        var image = $(this).attr('data-image')
        $this = obj
        console.log('deleting...');
        ajaxremovelocation({image_name: image}).done(function(data) {
            console.log(data)
            $this.fadeOut('', function() {
                $parent = $(this).parent('.location')
                $this.remove()
                check_is_empty($parent)
            });
            obj.off('click')
        }).fail(function(data) {
            console.log("REMOVAL FAIL")
            //console.log(data)
        });


    })
}

/**
 * fires event once "delete ..." message appears
 * if and only if the user selects any area outside of the 
 * delete box to allow the user to cancel their actions
 * @param {type} obj
 * @returns {undefined} 
 */
function bind_close_removal(obj) {
    $('body').on("mouseup", function(event) {
        if (!obj.is(event.target) && !$('.image-overlay').is(event.target)) {

            $this = obj
            if ($this.parent('a').hasClass('imager')) {
                $this.parent('a').children('.image-overlay').animate({
                    opacity: 0,
                });
                $this = obj.parent('a')
            }

            var location = $this.attr('data-location')
            $this.text(location)
            $this.removeClass('image-location')
            $this.addClass('pre-delete')
            obj.unbind('click')
            bind_location_pre_removal()
            $('body').off("mouseup")
        }
    });
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
                    draganddropitems.push('<a href="#?javascript:void(0)" id="drag_' + i + '"  class=" col-lg-2 col-md-4 col-sm-4 col-xs-10 bottom10 right5 label label-primary medium-font" data-campus="' + campus + '" data-building="' + building_data + '" data-room="' + room_data + '" draggable="true" >' + i + '</a>')

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
$('.ajax-drilldown').live('click', function(ev) {
    navigate_drilldown($(this))
});

$('#location-nav').live('click', function() {
    navigate_drilldown($(this))
});

/**
 * 
 */
function navigate_drilldown(obj) {
    $this = obj
    var parent = $('.show-locations')
    var root = $this.attr('data-root')
    var location = $this.attr('data-location')
    var campus = $this.attr('data-campus')
    var building = $this.attr('data-building')
    var db = dbstructure
    var hidden = ""

    switch (root) {
        case 'files':
            params = {dbstructure: JSON.stringify(db.files), bucketdir: bucketdir, root: 'root', campus: campus, building: building}
            $('#location-nav').attr('data-root', 'GWU')

            break;
        case 'root':
            params = {dbstructure: JSON.stringify(db.files[location]), bucketdir: bucketdir, root: 'subfolder', campus: location, building: building}
            $('#location-nav').attr('data-root', 'files')

            break;
        case 'subfolder':
            params = {dbstructure: JSON.stringify(db.files[campus][location]), bucketdir: bucketdir, root: 'bottomfolder', campus: campus, building: location}
            $('#location-nav').attr('data-root', 'root')
            $('#location-nav').attr('data-location', campus)

            break;
        default:
            params = {dbstructure: JSON.stringify(db), bucketdir: bucketdir, root: 'files', campus: campus, building: location}
            hidden = "hidden"
            break;

    }

    document.getElementById('location-nav').className = hidden;

    ajaxdrilldownlocations(params).done(function(data) {
        $(parent).html(data)
        bind_drag_and_drop()
        bind_location_pre_removal()
    }).fail(function(data) {
        console.log(data)
    });
    event.preventDefault();
    event.stopPropagation();
}
function delete_cookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function check_cookie_unhide_navigation() {

}
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

/**
 * Makes call for drilldown on select of each location
 * @param {type} params in ajax format of {dbstructure:var, subdir:var, bucketdir:var}
 * @returns {@exp;$@call;ajax} */
function ajaxdrilldownlocations(params) {
    /*
     * format of api call.
     * //for images in GWU
     * ApiHelper_Gadgets::draw_gadget_location_one_directory($dbstructure, 'files', $bucket_dir); 
     * //for images in each under gwu
     ApiHelper_Gadgets::draw_gadget_location_one_directory($dbstructure['files'], 'root', $bucket_dir); 
     //for images in each under fb building
     ApiHelper_Gadgets::draw_gadget_location_one_directory($dbstructure['files']['FB'], 'subfolder', $bucket_dir); 
     //for images in each under fb and building ac0
     ApiHelper_Gadgets::draw_gadget_location_one_directory($dbstructure['files']['FB']['AC0'], 'bottomfolder', $bucket_dir); 
     */
    return $.ajax({
        type: 'POST',
        url: drilldownajaxurl,
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