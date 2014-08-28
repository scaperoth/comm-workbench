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
        data: {args: params, service:service},
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
        data: {args: params, service:service},
        beforeSend: function() {
// this is where we append a loading image
            $('#ajax_panel').html('<div  id="ajax_background"><div id="loading"><img src="' + images + 'loading.gif" alt="Loading..." /></div></div>');
            //$('#ajax_background').fadeIn();
        },
        success: function(data) {
            // successful request; do something with the data
            $('#ajax_background').fadeOut();
            $('#ajax_panel').html('');
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

/*
 * this function takes parameters and adds an image to the given location 
 * @param {type} params
 * @returns {unresolved}
 */
function ajaxtogglewepaoutage() {
    return $.ajax({
        type: 'POST',
        url: toggleoutageurl,
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