/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */
require('select2');

$(document).ready(() => {
    // Select 2.
    $('.select2').select2();

    // Dropdown.
    $('.dropdown-toggle').dropdown();
});
