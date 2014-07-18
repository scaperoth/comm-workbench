/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$('select[data-type="gadgetcampus"]').change(function() {
    var group = $(this).attr('data-group');
    console.log($('select[data-group="'+group+'"][data-type="gadgetbuilding"').val());
});
