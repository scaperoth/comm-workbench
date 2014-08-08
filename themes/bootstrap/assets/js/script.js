

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


