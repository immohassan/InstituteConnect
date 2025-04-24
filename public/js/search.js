$(document).ready(function() {
    $('#suggestion-block').on('click', function() {
        const url = $(this).data('url');
        console.log('here');
        window.location.href = url;
    });
});
