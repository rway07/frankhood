/**
 * @file rates/create.js
 * @author kain rway07@gmail.com
 */
$(() => {
    $('#create_rate').validate({
        rules: {
            year: { required: true, digits: true, minlength: 4, maxlength: 4 },
            quota: { required: true, number: true },
            funeral_cost: { required: true, number: true },
        },
        messages: {
            year: {
                required: "Inserire l'anno.",
                digits: "L'anno deve essere composto da numeri.",
                minlength: "L'anno deve essere composto da 4 numeri.",
                maxlength: "L'anno deve essere composto da 4 numeri.",
            },
            quota: {
                required: 'Inserire la quota.',
                number: 'La quota deve essere un numero',
            },
            funeral_cost: {
                required: 'Inserire il costo del funerale.',
                number: 'Il costo del funerale deve essere un numero.',
            },
        },
    });
});
