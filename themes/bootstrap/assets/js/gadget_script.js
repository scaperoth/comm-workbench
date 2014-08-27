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