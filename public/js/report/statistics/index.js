$(document).ready(function() {
    loadOldestList();
});

function loadOldestList() {
    $.get('/report/statistics/oldest', function (data) {
        console.log(data);
        data.forEach((element) => {
            var date = convertDate(element.birth_date);
            $('#oldest_table').append(
                '<tr><td>' + element.first_name + ' ' + element.last_name + '</td>' +
                '<td>' + date + '</td></tr>'
            );
        });
    });
}

function loadDeceasedOverTime() {
    var beginYear = '2008';
    var endYear = '2020';
    var url = '/report/statistics/deceasedovertime/' + beginYear + '/' + endYear

    $.get(url, function(data) {

    });
}