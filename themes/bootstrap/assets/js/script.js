$('#image-view-button').click(function(e){
    $('.image-section').show();
    $('.location-section').hide();
});
$('#location-view-button').click(function(e){
    $('.location-section').show();
    $('.image-section').hide();
});

/********************************
 * AFFIXED SIDEBAR
 *******************************/
/* activate sidebar */
$('.sidebar').affix({
  offset: {
    top: 235
  }
});

/* activate scrollspy menu */
var $body   = $(document.body);
var navHeight = $('.navbar').outerHeight(true) + 10;

$body.scrollspy({
	target: '.leftCol',
	offset: navHeight
});

/* smooth scrolling sections */
$('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top - 50
        }, 1000);
        return false;
      }
    }
});


/************************
 * Drag and Drop Stuff
 ***********************/
$(function() {
    $( "ul.droptrue" ).sortable({
      connectWith: "ul",
      placeholder: "ui-state-highlight"
    });
 
    $( "ul.dropfalse" ).sortable({
      connectWith: "ul",
      dropOnEmpty: false
    });
    
    $( ".sortable" ).disableSelection();
  });