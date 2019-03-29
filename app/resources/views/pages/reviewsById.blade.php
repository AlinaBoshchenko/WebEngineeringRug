<!DOCTYPE html>
<html>
<head>
    <title>Reviews by ID</title>
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
        .isa_info, .isa_success, .isa_warning, .isa_error {
            margin: 10px 0px;
            padding:12px;

        }
        .isa_info {
            color: #3b6741;
            background-color: #93c9a7;
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
        showData.open('GET',"{!!  URL::route('api_get_review', ['user_name' =>$data['user_name'], 'review_id' => $data['review_id']]) !!} ");

        showData.onload = function(){
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
        window.addEventListener("load", function () {
            var table_body = '<table width="100%"><thead><tr><th>Name</th><th>Review</th><th>Carrier #1</th><th>Carrier #2</th><th>Carrier #3</th><th>Time</th></tr></thead><tbody>';

            table_body+='<tr>';
            table_body +='<td>';
            table_body +=data[0]["user_name"];
            table_body +='</td>';

            table_body +='<td>';
            table_body +=data[0]["reviews"];
            table_body +='</td>';

            table_body +='<td>';
            table_body +=data[0]["carrier_code_rank_1"];
            table_body +='</td>';

            table_body +='<td>';
            table_body +=data[0]["carrier_code_rank_2"];
            table_body +='</td>';

            table_body +='<td>';
            table_body +=data[0]["carrier_code_rank_3"];
            table_body +='</td>';

            table_body +='<td>';
            table_body +=data[0]["timestamp"]["date"];
            table_body +='</td>';

            table_body+='</tr>';


            table_body+='</tbody></table>';
            $('#tableDiv').html(table_body);

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

<body background="/images/more3.jpg">
<div class="isa_info" id = "p1"></div>
<div style="margin-top: 50px; margin-left: 250px; margin-right: 250px;">
    <div id="tableDiv" style="margin-top: 40px">
    </div>
</div>
</body>
</html>