$(document).ready(function(){
    // script for total items per page start
    $(document).on('change', '#items', function(){
        $(this).closest('form').submit();
    });
    // script for total items per page end
});