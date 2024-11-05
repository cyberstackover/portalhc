/**
 * Requirements:
 * - jQuery (http://jquery.com/)
 * - DataTables (http://datatables.net/)
 * - BootboxJS (http://bootboxjs.com/)
 * ---------------------------------------------------------------------------
 * Credits to https://gist.github.com/flackend/9517696
 * ---------------------------------------------------------------------------
 * This monitors all AJAX calls that have an error response. If a user's
 * session has expired, then the system will return a 401 status,
 * "Unauthorized", which will trigger this listener and so prompt the user if
 * they'd like to be redirected to the login page.
 */
$(document).ajaxError(function(event, jqxhr, settings, exception) {

    if (exception == 'Unauthorized') {

        // Prompt user if they'd like to be redirected to the login page
        bootbox.confirm("Your session has expired. Would you like to be redirected to the login page?", function(result) {
            if (result) {
                window.location = '/login';
            }
        });

    }
});

// disable datatables error prompt
$.fn.dataTable.ext.errMode = 'none';
