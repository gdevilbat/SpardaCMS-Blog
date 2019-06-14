$ = window.$;
jQuery = window.jQuery;

jQuery.fn.exists = function(){return this.length>0;}

//jQuery to collapse the navbar on scroll
$(window).scroll(function() {
	affix();
});

$(document).ready(function() {
	affix();
});

function affix() {
	navbar_height = $(".navbar").height();
    if ($(document).scrollTop() > (navbar_height+20)) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
}