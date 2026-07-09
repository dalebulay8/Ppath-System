<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mobile Uploads</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-6xl mx-auto py-8 px-5">

    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        Mobile Uploaded Attendance
    </h1>


    @forelse($uploads as $upload)

    <!-- Separate Upload Card -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-200">


        <!-- Header -->
        <div class="flex justify-between items-center mb-5">

            <div>
                <h2 class="text-xl font-bold text-gray-700">
                    {{ $upload->table_name }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Uploaded by: {{ $upload->author }}
                </p>

                <p class="text-sm text-gray-400">
                    {{ $upload->created_at->format('F d, Y h:i A') }}
                </p>
            </div>


            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold">
                {{ $upload->attendees->count() }} Attendees
            </span>

        </div>



        <!-- Attendees Table -->
        <div class="overflow-x-auto">

            <table class="w-full border-collapse">

                <thead>

                    <tr class="bg-gray-200 text-gray-700">

                        <th class="px-4 py-3 text-left rounded-tl-lg">
                            #
                        </th>

                        <th class="px-4 py-3 text-left">
                            Name
                        </th>

                        <th class="px-4 py-3 text-left rounded-tr-lg">
                            Gender
                        </th>

                    </tr>

                </thead>


                <tbody>

                @foreach($upload->attendees as $index => $attendee)

                    <tr class="border-b hover:bg-gray-50 transition">

                        <td class="px-4 py-3">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $attendee->name }}
                        </td>

                        <td class="px-4 py-3">

                            @if(strtoupper($attendee->gender) == 'MALE')

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    MALE
                                </span>

                            @else

                                <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    FEMALE
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach


                </tbody>

            </table>

        </div>


    </div>


    @empty

    <div class="bg-white rounded-xl shadow p-8 text-center text-gray-500">

        No mobile uploads yet.

    </div>

    @endforelse


</div>


</body>
</html>
