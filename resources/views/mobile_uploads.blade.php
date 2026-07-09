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


            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="border p-3 text-left">
                                Table Name
                            </th>

                            <th class="border p-3 text-left">
                                Name
                            </th>

                            <th class="border p-3 text-left">
                                Gender
                            </th>

                            <th class="border p-3 text-left">
                                Uploaded At
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($uploads as $upload)

                        <tr>

                            <td class="border p-3">
                                {{ $upload->table_name }}
                            </td>


                            <td class="border p-3">
                                {{ $upload->name }}
                            </td>


                            <td class="border p-3">
                                {{ $upload->gender }}
                            </td>


                            <td class="border p-3">
                                {{ $upload->created_at }}
                            </td>

                        </tr>


                    @endforeach


                    </tbody>

                </table>

            </div>


        @endif


    </div>

</div>

</body>
</html>
