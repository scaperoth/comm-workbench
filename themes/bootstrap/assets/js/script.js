/*******************************
 * features
 ******************************/

$('.location').each(function() {
    check_is_empty($(this))
})

function check_is_empty(obj) {
    if (isEmpty(obj)) {
        obj.addClass('well')
        //console.log("it's empty, alright...")
        return true
    }else{
        //console.log("so not empty")
        return false
    }
}
//check if element is empty
function isEmpty(el) {
    return !$.trim(el.html())

}

function get(name){
   if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      return decodeURIComponent(name[1]);
}

/******************************
 * Affixed sidebar
 ******************************/
$(".sidebar-affix").on("affix.bs.affix", (function() {
    $(this).addClass("col-xs-3")
    $(this).removeClass("col-sm-12")
}))

$(".sidebar-affix").on("affixed-top.bs.affix", (function() {
    $(this).removeClass("col-xs-3")
    $(this).addClass("col-sm-12")
}))

/******************************
 * size detection
 ******************************/
function findBootstrapEnvironment() {
    var envs = ['xs', 'sm', 'md', 'lg'];

    $el = $('<div>');
    $el.appendTo($('body'));

    for (var i = envs.length - 1; i >= 0; i--) {
        var env = envs[i];

        $el.addClass('hidden-'+env);
        if ($el.is(':hidden')) {
            $el.remove();
            return env
        }
    };
}

//start by binding elements
bind_drag_and_drop()

/**********************************\
 |*        Main Script            *|
 \*********************************/

/*##############################
 * Drag and Drop Events
 ##############################*/

/**
 * 
 * @returns {undefined}
 */
function bind_drag_and_drop() {
    /**/
    $('.dropper').on('drop', function(ev) {
        if (is_location_page()) {
            drop_image_onto_location(ev)
        } else {
            drop_location_onto_image(ev)
        }
    })
    $('.trashcan').on('drop', function(ev) {
        drop_to_trash(ev);
    })
    /**/
    $('.dropper').on('dragenter dragover', function(ev) {

        allowDrop(ev)
    })
    $('.dropper').on('dragleave dragexit', function(ev) {
    })

    $('.trashcan').on('dragenter dragover', function(ev) {
        allowDrop(ev)
        toggle_icon($(this), true)
        //console.log('woooah')
    })

    $('.trashcan').on('dragleave dragexit', function(ev) {
        //console.log("SPloUSH")
        toggle_icon($(this), false)
    })

    /**/
    $('a[draggable=true]').on('dragstart', function(event) {
        drag(event, $(this).attr('id'))

    })

}

function toggle_icon(obj, dragover) {
    if (dragover) {
        obj.removeClass('trash')
        obj.addClass('trash_open')
    } else {
        obj.removeClass('trash_open')
        obj.addClass('trash')
    }
}
/**
 * 
 * @returns {undefined}
 */
function unbind_drag_and_drop() {
    $('.dropper, .trashcan').unbind('drop')
    /**/
    $('.dropper, .trashcan').unbind('dragover')
    /**/
    $('a[draggable=true]').unbind('dragstart')
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
    var target = ev.target
    var newchild = document.getElementById(data).cloneNode(true)
    var campus
    var building
    var room
    var image_name
    var location

    //allow the image to dropped on anything that is a child of the drop container
    if (!$(target).hasClass('dropper')) {
        while (target) {
            if ($(target).hasClass('dropper')) {
                break;
            }
            target = target.parentNode
        }
    }

    target = $(target);

    //make sure it's dropped on the right target
    if ($(target).hasClass('dropper')) {

        newchild.id = ''
        campus = newchild.getAttribute('data-campus') ? newchild.getAttribute('data-campus') : ''
        building = newchild.getAttribute('data-building') ? newchild.getAttribute('data-building') : ''
        room = newchild.getAttribute('data-room') ? newchild.getAttribute('data-room') : ''
        location = (building ? campus + "/" : campus) + (room ? building + "/" : building) + room

        newchild.id = "trashable_" + newchild.innerHTML
        newchild.innerHTML = location ? location : "GWU"
        newchild.className = newchild.className + " image-location";
        $(newchild).removeClass('col-lg-4 col-md-5 col-sm-5')
        $(newchild).addClass('col-lg-3 col-md-4 col-sm-5')
        newchild.setAttribute("draggable", true)

        //fix the child's image name and make sure it is formatted properly
        image_name = encodeURIComponent($(target).attr('data-image'))
        newchild.id = newchild.id + "_" + image_name

        $(newchild).attr('data-image', location + "/" + image_name)
        //time to update...
        values = {campus: campus, building: building, room: room, image_name: image_name}
        ajaxsubmitnewlocation(values).done(function(ajax_data) {
            $(target).append(newchild);
            $(target).removeClass('well');
            unbind_drag_and_drop()
            bind_drag_and_drop()
            console.log(ajax_data);
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

    var target = ev.target
    var newchild = document.getElementById(data).cloneNode(true)
    var db
    var root
    var campus
    var building
    var room
    var image_name
    var location

    if (newchild.tagName == 'A') {
        newchild = $(newchild).children('img')
        console.log($(newchild)[0].outerHTML);

    }
    //allow the image to dropped on anything that is a child of the drop container
    if (!$(target).hasClass('dropper')) {
        while (target) {
            if ($(target).hasClass('dropper')) {
                break;
            }
            target = target.parentNode
        }

    }

    target = $(target);

    //make sure it's dropped on the right target
    if ($(target).hasClass('dropper')) {
        root = target.attr('data-root')
        location = target.attr('data-location')
        campus = target.attr('data-campus')
        building = target.attr('data-building')
        room = ''
        db = dbstructure
        image_name = $(newchild).attr('data-image')

        var newitem = [];

        newchild.id = root + "_" + image_name
        switch (root) {
            case 'root':
                params = {campus: location, building: building, room: room, image_name: image_name}
                location = (building ? location + "/" : location) + (room ? building + "/" + room + "/" : building)
                break;
            case 'subfolder':
                params = {campus: campus, building: location, room: room, image_name: image_name}
                location = (location ? campus + "/" : campus) + (room ? location + "/" : location) + room
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

        $(newchild).removeAttr('data-image')
        var data_image = location + "/" + image_name
        $(target).attr('data-image', data_image)
        
        

        ajaxsubmitnewlocation(params).done(function(ajax_data) {
            console.log(ajax_data)

            newitem.push('<a class="col-xs-2 imager pre-delete no-padding" href="#?javascript:void(0)" draggable="true" data-image="' + data_image + '" id="trashable_' + data_image + '">')
            newitem.push($(newchild)[0].outerHTML)
            newitem.push('</a>')
            $(target).append(newitem.join(""));
            $(target).removeClass('well');

            unbind_drag_and_drop()
            bind_drag_and_drop()
            //
            //console.log(ajax_data);
        }).fail(function(data) {
            //console.log(data);
            console.log("NEW LOCATION FAIL")
            //console.log(data);
        })

        //bind the new child to the click event just in case the user wants to delete it before a refresh
    }
}

/**
 *
 * @param {type} ev
 * @returns {undefined} 
 */
function drop_to_trash(ev) {


    toggle_icon($(ev.target), false)
    var data = ev.originalEvent.dataTransfer.getData("Text")
    var item = document.getElementById(data)
    $this = $(item)
    var image
    if (item.tagName == 'A' || item.tagName == 'DIV') {

        image = $this.attr('data-image')
        //console.log("it's an a, boss")
    } else {
        image = $this.parent('a').attr('data-image')
        $this = $this.parent('a');
    }
    $parent = $this.parent('.dropper')
    console.log($parent);
    ajaxremovelocation({image_name: image}).done(function(data) {
        console.log(data)
        //console.log("SUCCESS")
        $this.fadeOut('', function() {
            $this.remove()
            //checks if parent container is empty
            //if the container is empty, add a "well" class
            check_is_empty($parent)
        });
    }).fail(function(data) {
        console.log(data)
        console.log("REMOVAL FAIL")
        //console.log(data)
    });

}

function is_location_page() {
    var page = get('page_id');
    if (page === 'location' || page==='outage')
        return true;
    else
        return false
}


