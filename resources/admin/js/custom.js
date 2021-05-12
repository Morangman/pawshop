/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */
require('select2');

$(document).ready(() => {
    let sidebar_items = [
        'orders-nav',
        'products-nav',
        'statistics-nav',
        'settings-nav',
    ];

    $('#orders-nav').addClass('nav-item-open');
    $.each(sidebar_items, function( index, value ) {
        let localValue = localStorage.getItem(value);

        if (localValue) {
            $('#'+value).parent('.nav-item-submenu').addClass('nav-item-open');

            $('#'+value).parent('.nav-item-submenu').find('.nav-group-sub').show();
        }
    });

    // Select 2.
    $('.select2').select2();

    // Dropdown.
    $('.dropdown-toggle').dropdown();

    $('#searchOrder').focus();

    $('#orders-nav').click(() => {
        let localValue = localStorage.getItem("orders-nav");

        if (localValue){
            localStorage.removeItem("orders-nav");
        } else {
            localStorage.setItem("orders-nav", true);
        }
    });

    $('#products-nav').click(() => {
        let localValue = localStorage.getItem("products-nav");

        if (localValue){
            localStorage.removeItem("products-nav");
        } else {
            localStorage.setItem("products-nav", true);
        }
    });

    $('#statistics-nav').click(() => {
        let localValue = localStorage.getItem("statistics-nav");

        if (localValue){
            localStorage.removeItem("statistics-nav");
        } else {
            localStorage.setItem("statistics-nav", true);
        }
    });

    $('#settings-nav').click(() => {
        let localValue = localStorage.getItem("settings-nav");

        if (localValue){
            localStorage.removeItem("settings-nav");
        } else {
            localStorage.setItem("settings-nav", true);
        }
    });
});
