<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Table</title>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        h2 {
            font-size: 1em;
            font-weight: 100%;
            text-align: center;
            display: block;
            line-height: 1em;
            padding-bottom: 2em;
            background-color: #fff;
            color: #636b6f;
        }
        h2 {
            display: block;
            font-size: 1.5em;
            font-weight: bold;
        }
        body {

            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 1.42em;
            color: #A7A1AE;

        }
        button{
            border-radius: 10px;
            height: 45px;
            width: 150px;
            text-align: center;
            background-color: #113049;
            font-size: 15px;
            color: #ffffff;
        }
        input{
            height: 35px;
            font-size: 15px;
        }
        table {

            width: 100%;
            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 2em;
            color: #A7A1AE;
            background: #1F2739;
            padding: 20px;


        }
        th, td {
            text-align: center;
            padding: 15px;
            font-size: 20px;
            color:#A7A1AE;
            border: 5px groove #ccc /* Граница между ячейками */
        }
        th {
            background-color: #1F2739;
            color: white;
            font-style: bold;
            font-size: 35px;
        }
        a {
            color: #FFE8E6;
        } /* link color */

    </style>
    <script >
        //JSON Object................

        var showData = new XMLHttpRequest();
        var data;
        console.log("hiii");
        showData.open('GET', 'http://localhost:8000/API/airport/ {{$data["airport_code"]}}/carriers',true);

        showData.onload = function(){
            console.log("hello");

            data = JSON.parse(this.response);
            console.log(data);
        }
        showData.send();

        //JSON Object End................
        //Create table and fetch data from JSON Object.
        $(document).ready(function(){
            $("button").click(function(){
                var number_of_rows = data.length;
                var k = 0;
                var table_body = '<table width="100%"><thead><tr><th>Name</th><th>Code</th></tr></thead><tbody>';
                for(k in data){

                    table_body+='<tr>';
                    table_body +='<td>';
                    table_body +=data[k]["carrier_name"] + '</br>';
                    table_body += '<a href="' + data[k]["link"] + '">view details</a>';
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data[k]["carrier_code"];
                    table_body +='</td>';


                    table_body+='</tr>';

                }
                table_body+='</tbody></table>';
                $('#tableDiv').html(table_body);
                //display data..........
            });

// for search function.................................. only............................
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("table tr").filter(function(index) {
                    if(index>0){
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    }
                });
            });

        });
    </script>
</head>
<body background="images/intro3.jpg" style="width: 1000px">
<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <button>Create Table</button>
    <input type="text" id="search" placeholder="Search data here....."></input>
    <div id="tableDiv" style="margin-top: 40px">
        Table will be generated here.
        {{$data['airport_code']}}
    </div>
</div>
<p id="p1"></p>
</body>
</html>
