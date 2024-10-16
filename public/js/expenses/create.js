/**
 * @file expenses/create.js
 * @author kain rway07@gmail.com
 */
$(() => {
    $('#create_expense_form').validate({
        rules: {
            description: { required: true },
            date: { required: true, date: true },
            amount: { required: true, number: true },
        },
        messages: {
            description: { required: 'Inserire la descrizione' },
            date: {
                required: 'Inserire la data della spesa',
                date: 'Data nel formato sbagliato',
            },
            amount: {
                required: 'Inserire il totale della spesa',
                number: 'La cifra non è un numero',
            },
        },
    });
});
