/**
 *  @file offers/create.js
 *  @author kain rway07@gmail.com
 */
$(() => {
    $('#create_offer_form').validate({
        rules: {
            description: { required: true },
            date: { required: true, date: true },
            amount: { required: true, number: true },
        },
        messages: {
            description: { required: 'Inserire la descrizione' },
            date: { required: 'Inserire la data della spesa' },
            amount: { required: 'Inserire il totale della spesa' },
        },
    });
});
