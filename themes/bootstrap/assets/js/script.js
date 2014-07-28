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

/******************************
 * Affixed sidebar
 ******************************/
$("#bucket-affix").on("affix.bs.affix", (function() {
    $(this).addClass("col-xs-3")
    $(this).removeClass("col-sm-12")
}))

$("#bucket-affix").on("affixed-top.bs.affix", (function() {
    $(this).removeClass("col-xs-3")
    $(this).addClass("col-sm-12")
}))

/******************************
 * loction effects
 ******************************/