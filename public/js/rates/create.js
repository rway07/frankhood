$(document).ready(function () {
    $('#create_rate').validate({
        rules: {
            year: {required: true, digits: true, minlength: 4, maxlength: 4},
            quota: {required: true},
            funeral_cost: {required: true},
        },
        messages: {
            year: {required: 'Inserire l\'anno'},
            quota: {required: 'Inserire la quota'},
        },
    });
});
