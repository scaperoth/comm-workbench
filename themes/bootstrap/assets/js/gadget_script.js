/**
 * javascript that only relates directly to the gadget section(s)

$('#image-view-button').click(function(e) {
    $('.image-section').show();
    $('.location-section').hide();
});
$('#location-view-button').click(function(e) {
    $('.location-section').show();
    $('.image-section').hide();
});
*/


 /********************
 * Section refresh
 *********************/
$('#reset_button').click(function(){
    selectbox = document.getElementById('AddimageForm_campus');
    selectbox.value = ''
    $('#AddimageForm_campus').change();
})

/************************
 * Drag and Drop Stuff
 ***********************/
$(function() {

    
    $("ul.droptrue").sortable({
        connectWith: "ul",
        placeholder: "ui-state-highlight"
    });

    $("ul.dropfalse").sortable({
        connectWith: "ul",
        dropOnEmpty: false
    });

    $(".sortable").disableSelection();
});


/*##############################
 * Form and Click Events
 ##############################*/

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

        //get select value for each piece
        campus = $('select[data-type="campus"][data-group="' + group + '"]').val();

        building = $('select[data-type="building"][data-group="' + group + '"]').val();


        //place data into object to send to ajax
        values = {location:{campus: campus, building: building}, service:service};

        ajaxgetlocations(values).done(function(ajax_data) { //send data, get response
            //animate target
            

            if (campus === '') {
                draganddropitems.push('<a href="#?javascript:void(0)" id="drag_GWU"  class=" col-lg-4 col-md-5 col-sm-5 col-xs-10 bottom10 right5 label label-primary medium-font" draggable="true" >GWU</a>')
                
                target = $(document.getElementById('AddimageForm_campus'));
            }
            
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
                draganddropitems.push('<a href="#?javascript:void(0)" id="drag_' + i + '"  class=" col-lg-4 col-md-5 col-sm-5 col-xs-10 bottom10 right5 label label-primary medium-font" data-campus="' + campus + '" data-building="' + building_data + '" data-room="' + room_data + '" draggable="true" >' + i + '</a>')

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

    })
});


/*##############################
 * DrillDown
 ##############################*/

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
            params = {hierarchy: 'files', bucketdir: bucketdir, root: 'root', campus: campus, building: building, service:service}
            $('#location-nav').attr('data-root', 'GWU')
            break;
        case 'root':
            params = {hierarchy: 'root', bucketdir: bucketdir, root: 'subfolder', campus: location, building: building, service:service}
            $('#location-nav').attr('data-root', 'files')
            break;
        case 'subfolder':
            params = {hierarchy: 'subfolder', bucketdir: bucketdir, root: 'bottomfolder', campus: campus, building: location, service:service}
            $('#location-nav').attr('data-root', 'root')
            $('#location-nav').attr('data-location', campus)
            break;
        default:
            params = {hierarchy: 'default', bucketdir: bucketdir, root: 'files', campus: campus, building: location, service:service}
            hidden = "hidden"
            break;

    }

    document.getElementById('location-nav').className = hidden;

    ajaxdrilldownlocations(params).done(function(data) {
        $(parent).html(data)
        unbind_drag_and_drop()
        bind_drag_and_drop()
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
