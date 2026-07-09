<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mobile Uploads</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" href="/svg.png?v=3">
</head>


<body class="bg-gray-100">


<div class="max-w-6xl mx-auto mt-10">

    <div class="bg-white rounded-xl shadow-lg p-6">


        <h1 class="text-3xl font-bold mb-6">
            Mobile Uploads
        </h1>



        @if($uploads->count() == 0)

            <p class="text-gray-500">
                No uploaded attendance yet.
            </p>


        @else


            @foreach($uploads as $upload)


            <div class="mb-8">


                <h2 class="text-xl font-bold mb-1">
    {{ $upload->table_name }}
</h2>

<p class="text-gray-500 mb-3">
    Uploaded by: {{ $upload->author }}
</p>



                <table class="w-full border">


                    <thead>

                        <tr class="bg-gray-100">

                            <th class="border p-3">
                                Name
                            </th>


                            <th class="border p-3">
                                Gender
                            </th>


                        </tr>

                    </thead>



                    <tbody>


                    @foreach($upload->attendees as $person)


                        <tr>


                            <td class="border p-3">
                                {{ $person->name }}
                            </td>


                            <td class="border p-3">
                                {{ $person->gender }}
                            </td>


                        </tr>


                    @endforeach


                    </tbody>


                </table>


            </div>


            @endforeach


        @endif



    </div>

</div>


</body>

</html>
