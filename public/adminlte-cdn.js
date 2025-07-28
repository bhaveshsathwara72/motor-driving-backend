/* Custom AdminLTE JS */
/* This file replaces the missing adminlte-cdn.js */

// Create a global variable to track jQuery loading status
window.jQueryLoaded = false;

// Function to load scripts sequentially
function loadScriptsSequentially(scripts, callback) {
    if (scripts.length === 0) {
        if (callback) callback();
        return;
    }
    
    var script = document.createElement('script');
    script.src = scripts[0];
    script.onload = function() {
        // If this is jQuery being loaded, set the global flag
        if (scripts[0].includes('jquery.min.js')) {
            window.jQueryLoaded = true;
            // Dispatch an event that jQuery is loaded
            document.dispatchEvent(new Event('jQueryLoaded'));
            console.log('jQuery loaded successfully');
        }
        loadScriptsSequentially(scripts.slice(1), callback);
    };
    script.onerror = function() {
        console.error('Error loading script: ' + scripts[0]);
        loadScriptsSequentially(scripts.slice(1), callback);
    };
    document.head.appendChild(script);
}

// Define scripts in the order they should be loaded
var scripts = [
    '/vendor/jquery/jquery.min.js',                                          // jQuery first
    '/vendor/bootstrap/js/bootstrap.bundle.min.js',                           // Bootstrap
    '/vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js',          // OverlayScrollbars
    '/vendor/adminlte/dist/js/adminlte.min.js',                              // AdminLTE core
    '/vendor/datatables/jquery.dataTables.min.js',                           // DataTables core
    '/vendor/datatables/dataTables.bootstrap4.min.js',                       // DataTables Bootstrap 4
    'https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js', // DataTables Buttons
    'https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js', // DataTables Buttons Bootstrap 4
    'https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js', // DataTables Responsive
    'https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js'  // DataTables Responsive Bootstrap 4
];

// Load all scripts in sequence
loadScriptsSequentially(scripts, function() {
    console.log('All scripts loaded successfully');
});