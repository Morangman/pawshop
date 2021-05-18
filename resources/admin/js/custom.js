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
        'products-nav',
        'statistics-nav',
        'settings-nav',
        'warehouse-nav',
    ];

    $.each(sidebar_items, function(index, value) {
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

        hideMenuItems("orders-nav");

        if (localValue){
            localStorage.removeItem("orders-nav");
        } else {
            localStorage.setItem("orders-nav", true);
        }
    });

    $('#warehouse-nav').click(() => {
        let localValue = localStorage.getItem("warehouse-nav");

        hideMenuItems("warehouse-nav");

        if (localValue){
            localStorage.removeItem("warehouse-nav");
        } else {
            localStorage.setItem("warehouse-nav", true);
        }
    });

    $('#products-nav').click(() => {
        let localValue = localStorage.getItem("products-nav");

        hideMenuItems("products-nav");

        if (localValue){
            localStorage.removeItem("products-nav");
        } else {
            localStorage.setItem("products-nav", true);
        }
    });

    $('#statistics-nav').click(() => {
        let localValue = localStorage.getItem("statistics-nav");

        hideMenuItems("statistics-nav");

        if (localValue){
            localStorage.removeItem("statistics-nav");
        } else {
            localStorage.setItem("statistics-nav", true);
        }
    });

    $('#settings-nav').click(() => {
        let localValue = localStorage.getItem("settings-nav");

        hideMenuItems("settings-nav");

        if (localValue){
            localStorage.removeItem("settings-nav");
        } else {
            localStorage.setItem("settings-nav", true);
        }
    });

    $('.sidebar-link').click(() => {
        hideMenuItems();
    });

    function hideMenuItems(excluded = null) {
        $.each(sidebar_items, function(index, value) {
            if (value !== excluded) {
                localStorage.removeItem(value);
    
                $('#'+value).parent('.nav-item-submenu').removeClass('nav-item-open');
    
                $('#'+value).parent('.nav-item-submenu').find('.nav-group-sub').hide();
            }
        });
    }
});
