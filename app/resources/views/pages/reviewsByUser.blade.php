<!DOCTYPE html>
<html>
<head>
    <title>Reviews by User</title>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        .isa_info, .isa_success, .isa_warning, .isa_error {
            margin: 10px 0px;
            padding:12px;

        }
        .isa_info {
            color: #3b6741;
            background-color: #93c9a7;
        }
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
            background-color: #213646;
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
            background: #A7A1AE;
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
            background-color: #396669;
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
        showData.open('GET', '{{URL::route('api_get_reviews', $data['user_name'])}}',true);

        showData.onload = function(){
            console.log("hello");

            data = [];
            if (this.status == 404) {
                $('#p1').html("Invalid input in form.");
            } else if (this.status != 200) {
                $('#p1').html(this.response.toString());
            } else {
                data = JSON.parse(this.response);

                if (data.length == 0) {
                    $('#p1').html("No statistics found");
                }
            }
        };
        showData.send();

        //JSON Object End................
        //Create table and fetch data from JSON Object.
        window.addEventListener("load", function (){
                var number_of_rows = data.length;
                var k = 0;
                var table_body = '<table width="100%"><thead><tr><th>Name</th><th>Review</th><th>Carrier #1</th><th>Carrier #2</th><th>Carrier #3</th><th>Time</th></tr></thead><tbody>';
                for(k in data){

                    table_body+='<tr>';
                    table_body +='<td>';
                    table_body +=data[k]["user_name"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data[k]["reviews"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data[k]["carrier_code_rank_1"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data[k]["carrier_code_rank_2"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data[k]["carrier_code_rank_3"];
                    table_body +='</td>';

                    table_body +='<td>';
                    table_body +=data[k]["timestamp"]["date"];
                    table_body +='</td>';

                    table_body+='</tr>';

                }
                table_body+='</tbody></table>';
                $('#tableDiv').html(table_body);
                //display data..........


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
<body background="../images/more2.jpg" style="width: 1000px">
<div class="isa_info" id = "p1"></div>
<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <input type="text" id="search" placeholder="Search data here....."></input>
    <div id="tableDiv" style="margin-top: 40px>
        Table will be generated here.
    </div>
</div>
<p id="p1"></p>
</body>
</html>
