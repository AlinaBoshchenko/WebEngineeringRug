$(document).ready(function () {
    $('#get-data').click(function () {
        var showData = new XMLHttpRequest();

        showData.open('GET', 'localhost:8000/API/airports', true);

        showData.onload = function(){}

        showData.text('Loading the JSON file.');
    });
});