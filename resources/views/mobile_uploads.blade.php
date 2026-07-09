<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mobile Uploads</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <link rel="icon" href="/svg.png?v=3">

</head>


<body class="bg-gray-100 pt-24">


<!-- NAVBAR -->
<nav class="fixed top-0 left-0 w-full border-b shadow-sm px-8 py-4 flex justify-between items-center z-50"
     style="background-color:#2F4B63;">


    <div class="flex items-center gap-2">

        <img src="/ppa1.png"
             alt="PPATH Logo"
             style="width:150px;height:auto;">


        <span class="text-white text-lg ml-2">
            | Monitoring System
        </span>

    </div>



    <a href="/dashboard"
       class="bg-white text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-200">

        ← Back to Dashboard

    </a>


</nav>





<main class="max-w-6xl mx-auto mt-6 px-4">



<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-900">
        Mobile Uploads
    </h1>


    <p class="text-gray-500 mt-1">
        Attendance records uploaded from mobile application
    </p>

</div>





@if($uploads->count() == 0)



<div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-400">

    No uploaded attendance yet.

</div>




@else




<div class="space-y-8">





@foreach($uploads as $upload)





<div class="bg-white border rounded-2xl shadow-sm p-8">





<!-- HEADER -->

<div class="flex justify-between items-center mb-5">



<div>


<h2 class="text-2xl font-bold text-gray-900">

{{ $upload->table_name }}

</h2>



<p class="text-sm text-gray-500 mt-1">

Uploaded by: {{ $upload->author }}

</p>



<p class="text-sm text-blue-600">

Mobile Attendance Upload

</p>


</div>





<!-- EXPORT BUTTONS -->

<div class="flex gap-2">


<button onclick="exportCSV(this)"
class="px-3 py-1 rounded border"
style="background-color:#D8DEE4;">

Export CSV

</button>




<button onclick="exportExcel(this)"
class="text-white px-3 py-1 rounded"
style="background-color:#0E1A24;">

Export Excel

</button>



</div>




</div>







<!-- STATISTICS -->


@php


$total = $upload->attendees->count();



$male = $upload->attendees
                ->where('gender','MALE')
                ->count();



$female = $upload->attendees
                ->where('gender','FEMALE')
                ->count();



@endphp







<div class="grid grid-cols-3 gap-4 mb-6">



<div class="bg-blue-50 p-4 rounded-lg">

<p class="text-gray-600">
Total
</p>

<p class="text-2xl font-bold text-blue-700">

{{ $total }}

</p>

</div>





<div class="bg-green-50 p-4 rounded-lg">


<p class="text-gray-600">
Male
</p>


<p class="text-2xl font-bold text-green-700">

{{ $male }}

</p>


</div>






<div class="bg-purple-50 p-4 rounded-lg">


<p class="text-gray-600">
Female
</p>


<p class="text-2xl font-bold text-purple-700">

{{ $female }}

</p>


</div>



</div>









<!-- TABLE -->

<div class="overflow-x-auto">


<table class="w-full border attendance-table">


<thead class="bg-gray-100">


<tr>


<th class="border p-3">
#
</th>



<th class="border p-3">
Name
</th>



<th class="border p-3">
Gender
</th>



</tr>


</thead>






<tbody>



@php
$count = 1;
@endphp





@forelse($upload->attendees as $person)



<tr>


<td class="border p-3">

{{ $count++ }}

</td>




<td class="border p-3">

{{ $person->name }}

</td>




<td class="border p-3">

{{ $person->gender }}

</td>




</tr>





@empty



<tr>

<td colspan="3"
class="text-center text-gray-400 p-6">

No attendees yet

</td>

</tr>



@endforelse





</tbody>



</table>



</div>





</div>






@endforeach





</div>





@endif





</main>









<script>


function exportExcel(button)
{

    const tableBlock = button.closest('.bg-white');

    const table = tableBlock.querySelector('table');



    const workbook = XLSX.utils.table_to_book(table, {

        sheet:"Mobile Upload"

    });



    XLSX.writeFile(

        workbook,

        "PPATH_Mobile_Attendance.xlsx"

    );


}







function exportCSV(button)
{


    const tableBlock = button.closest('.bg-white');


    const table = tableBlock.querySelector('table');



    const rows = table.querySelectorAll('tr');



    let csv = [];





    rows.forEach(row => {



        const cols = row.querySelectorAll('th, td');



        let rowData = [];




        cols.forEach(col => {



            rowData.push(

                `"${col.innerText.trim()}"`

            );



        });




        csv.push(rowData.join(","));



    });







    const blob = new Blob(

        [csv.join("\n")],

        {type:"text/csv"}

    );





    const url = URL.createObjectURL(blob);





    const a = document.createElement("a");



    a.href = url;



    a.download = "PPATH_Mobile_Attendance.csv";





    document.body.appendChild(a);



    a.click();





    document.body.removeChild(a);





    URL.revokeObjectURL(url);



}




</script>



</body>

</html>
