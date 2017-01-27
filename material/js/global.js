jQuery(document).ready(function($) {
// Load Flexslider
    $(".flexslider").flexslider({
        animation: "slide",
        controlNav: false,
        prevText: "",
        nextText: "",
        smoothHeight: true   
    });

    $('.navbar-header button').click(function(){
      //$(".navbar-toggle").click(); //bootstrap 3.x by Richard
      //$(".navbar-toggle").is(":visible")
      //alert("hello");
        $(".navbar-collapse").css('height','auto');
        $(".navbar-collapse").toggleClass("in");
    });
});