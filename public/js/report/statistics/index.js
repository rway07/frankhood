$(() => {
    loadOldestList();
});

function loadOldestList() {
    $.get("/report/statistics/oldest", (data) => {
        console.log(data);
        data.forEach((element) => {
            const date = convertDate(element.birth_date);
            $("#oldest_table").append(
                `<tr><td>${ 
                    element.first_name 
                    } ${ 
                    element.last_name 
                    }</td>` +
                    `<td>${ 
                    date 
                    }</td></tr>`
            );
        });
    });
}

function loadDeceasedOverTime() {
    const beginYear = "2008";
    const endYear = "2020";
    const url =
        `/report/statistics/deceasedovertime/${  beginYear  }/${  endYear}`;

    $.get(url, (data) => {});
}
