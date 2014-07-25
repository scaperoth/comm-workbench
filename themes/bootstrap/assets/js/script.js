/*******************************
 * features
 ******************************/
//check if element is empty
function isEmpty(el) {
     return !$.trim(el.html())

}

$('.location').each (function(){
    if (isEmpty($(this))) {
        $(this).addClass('well')
    }
})
    



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
var $body = $(document.body);
var navHeight = $('.navbar').outerHeight(true) + 10;

$body.scrollspy({
    target: '.leftCol, .rightCol',
    offset: navHeight
});

/* smooth scrolling sections */
$('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            $('html,body').animate({
                scrollTop: target.offset().top - 50
            }, 1000);
            return false;
        }
    }
});


