(function($) {
$( document ).ready(function() {
    /* isotope */
    var $container = $('.lms_isotope').isotope({
        itemSelector: '.element-item',
        layoutMode: 'fitRows',
    });
    // layout Isotope after each image loads
    $container.imagesLoaded().progress( function() {
        $container.isotope('layout');
    });
    // bind filter button click
   $('#filters').on( 'click', 'button', function() {
        var filterValue = $( this ).attr('data-filter'); //alert(filterValue);
        $container.isotope({ filter: filterValue });
    });
    // change is-checked class on buttons
    $('.button-group').each( function( i, buttonGroup ) {
        var $buttonGroup = $( buttonGroup );
        $buttonGroup.on( 'click', 'button', function() {
        $buttonGroup.find('.is-checked').removeClass('is-checked');
        $( this ).addClass('is-checked');
        });
    });

    /*style my tooltip*/
    $("#portfolio").find("[title]").style_my_tooltips({
        tip_follows_cursor:true,
        tip_delay_time:400,
        tip_fade_speed:300,
        //attribute:"title"
    });

    /*other functions*/
    $( ".element-item .thumbnail" ).hover(
        function() {
            $( this ).find( "span" ).css( "display", "block" );
        }, function() {
            $( this ).find( "span" ).css( "display", "none" );
        }
    );
})
})(jQuery);
