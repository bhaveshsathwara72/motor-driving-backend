import './bootstrap';


// App initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('App initialized');
    
    // Initialize Bootstrap components
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    // Initialize any custom components
    initializeCustomComponents();
});

// Custom components initialization
function initializeCustomComponents() {
    // Add any custom component initialization here
}