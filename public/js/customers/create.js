/**
 * @file customers/create.js
 * @author kain rway07@gmail.com
 */
$(function () {
    $("#create_customer_form").validate({
        rules: {
            first_name: { required: true },
            last_name: { required: true },
            alias: {},
            cf: { required: true, minlength: 16, maxlength: 16 },
            birth_date: { required: true, date: true },
            birth_place: { required: true },
            birth_province: { required: true },
            gender: { required: true },
            municipality: { required: true },
            address: { required: true },
            CAP: { required: true, digits: true, maxlength: 5 },
            province: { required: true },
            phone: { digits: true },
            mobile_phone: { digits: true },
            email: { email: true },
            enrollment_year: {
                required: true,
                digits: true,
                minlength: 4,
                maxlength: 4,
            },
            death_date: { date: true },
            revocation_date: { date: true },
        },
        messages: {
            first_name: { required: "Inserire il Nome" },
            last_name: { required: "Inserire il Cognome" },
            cf: { required: "Inserire il Codice Fiscale" },
            birth_date: { required: "Inserire la data di nascita" },
            birth_place: { required: "Inserire il luogo di nascita" },
            birth_province: { required: "Inserire la provincia di nascita" },
            gender: { required: "Inserire il sesso" },
            address: { required: "Inserire l'indirizzo" },
            municipality: { required: "Inserire il comune" },
            CAP: {
                required: "Inserire il CAP",
                maxlength: "Il CAP deve essere composto da 5 numeri",
            },
            province: { required: "Inserire la provincia" },
            phone: {
                digits: "Il numero di telefono deve essere composto solo da numeri",
            },
            mobile_phone: {
                digits: "Il numero di telefono deve essere composto solo da numeri",
            },
            email: { email: "email non valida" },
            enrollment_year: {
                required: "Inserire l'anno di iscrizione",
                digits: "Anno di iscrizione non valido",
                minlength: "Anno di iscrizione non valido",
                maxlength: "Anno di iscrizione non valido",
            },
        },
    });
});
