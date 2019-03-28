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
            color: #ffe9e6;
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
            color: #FFFFFF;
            background-size: 100%;



        }
        button{
            border-radius: 10px;
            height: 45px;
            width: 150px;
            text-align: center;
            background-color: #535454;
            font-size: 15px;
            color: #ffffff;
        }
        input{
            height: 35px;
            font-size: 15px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: 'Nunito', sans-serif;
            font-weight: 100%;
            line-height: 2em;
            color: #f7c6c5;
            background: #8c8f88;
            padding: 20px;


        }
        th, td {
            text-align: center;
            padding: 15px;
            font-size: 20px;
            color: #ffffff;
            border: 5px groove #ccc /* Граница между ячейками */
        }
        th {
            background-color: #6ccfab;
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
        showData.open('GET',"{!!  URL::route('api_carrier_delayed_statistics', ['airport1' =>$data['airport1'], 'airport2' => $data['airport2']]) !!} ");

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
                var table_body = '<table width="100%"><thead><tr><th>Airport1</th><th>Airport2</th><th>Mean</th><th>Median</th><th>Standard deviation</th></tr></thead><tbody>';


                    table_body+='<tr>';

                    table_body +='<td>';
                    table_body +=data["airport1"]["airport_name"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data["airport2"]["airport_name"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data["mean"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data["median"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data["standard_deviation"];
                    table_body +='</td>';
                    table_body+='</tr>';


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

<body background="/images/map1.png">

<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <button>Show statistics</button>
    <div id="tableDiv" style="margin-top: 40px">
    </div>
</div>
<p id="p1"></p>

</body>
</html>