<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mobile Uploads</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="/svg.png?v=3">
</head>

<body class="bg-gray-100 pt-24">

<nav class="fixed top-0 left-0 w-full border-b shadow-sm px-8 py-4 flex justify-between items-center z-50"
     style="background-color:#2F4B63;">
    <div class="flex items-center gap-2">
        <img src="/ppa1.png" alt="PPATH Logo" style="width:150px;height:auto;">
        <span class="text-white text-lg ml-2">| Monitoring System</span>
    </div>
    <a href="/dashboard" class="bg-white text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-200">
        ← Back to Dashboard
    </a>
</nav>

<main class="max-w-6xl mx-auto mt-6 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mobile Uploads</h1>
        <p class="text-gray-500 mt-1">Attendance records uploaded from mobile application</p>
    </div>

    @if($uploads->count() == 0)
    <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-400">
        No uploaded attendance yet.
    </div>
    @else
    <div class="space-y-8">
        @foreach($uploads as $upload)
        @php
            $total = $upload->attendees->count();
            $male = $upload->attendees->where('gender','MALE')->count();
            $female = $upload->attendees->where('gender','FEMALE')->count();
        @endphp

        <div class="bg-white border rounded-2xl shadow-sm p-8 upload-card"
             data-title="{{ $upload->table_name }}"
             data-author="{{ $upload->author }}"
             data-total="{{ $total }}"
             data-male="{{ $male }}"
             data-female="{{ $female }}">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $upload->table_name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Uploaded by: {{ $upload->author }}</p>
                    <p class="text-sm text-blue-600">Mobile Attendance Upload</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="exportCSV(this)" class="px-3 py-1 rounded border" style="background-color:#D8DEE4;">Export CSV</button>
                    <button onclick="exportExcel(this)" class="text-white px-3 py-1 rounded" style="background-color:#0E1A24;">Export Excel</button>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $total }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-gray-600">Male</p>
                    <p class="text-2xl font-bold text-green-700">{{ $male }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-gray-600">Female</p>
                    <p class="text-2xl font-bold text-purple-700">{{ $female }}</p>
                </div>
            </div>

        <!-- Dropdown Button -->
<div class="mt-4">
 <button
    onclick="toggleAttendance(this)"
    class="flex items-center gap-2 text-gray-700 hover:text-black transition">

    <span class="arrow transition-transform duration-300">▶</span>
    <span>Attendance List</span>

</button>
</div>

<!-- Hidden Attendance Table -->
<div class="attendance-content hidden mt-4 overflow-x-auto">

    <table class="w-full border attendance-table">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-3">#</th>
                <th class="border p-3">Name</th>
                <th class="border p-3">Gender</th>
            </tr>
        </thead>

        <tbody>
            @php $count = 1; @endphp

            @forelse($upload->attendees as $person)
            <tr>
                <td class="border p-3">{{ $count++ }}</td>
                <td class="border p-3">{{ $person->name }}</td>
                <td class="border p-3">{{ $person->gender }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-gray-400 p-6">
                    No attendees yet
                </td>
            </tr>
            @endforelse

        </tbody>
    </table>

</div>
              <br>

            <a href="javascript:void(0)"
               onclick="confirmDelete('/mobile-uploads/delete/{{ $upload->id }}')"
               class="text-white px-4 py-2 text-sm rounded hover:bg-red-600"
               style="background-color:#CB0000;">

                Delete table

            </a>
        </div>
        @endforeach
    </div>
    @endif
</main>

<script>
function exportExcel(button)
{
    const card = button.closest('.upload-card');
    const table = card.querySelector('table');

    const title = card.dataset.title;
    const author = card.dataset.author;
    const total = card.dataset.total;
    const male = card.dataset.male;
    const female = card.dataset.female;

    let data = [];

    // REPORT HEADER
    data.push(["PPATH Mobile Attendance Report"]);
    data.push(["Activity", title]);
    data.push(["Uploaded By", author]);
    data.push([]);

    // SUMMARY
    data.push(["Attendance Summary"]);
    data.push(["Total", total]);
    data.push(["Male", male]);
    data.push(["Female", female]);
    data.push([]);

    // ATTENDEE TABLE
    const rows = table.querySelectorAll("tr");
    rows.forEach(row => {
        let rowData = [];
        row.querySelectorAll("th, td").forEach(cell => {
            rowData.push(cell.innerText.trim());
        });
        data.push(rowData);
    });

    const worksheet = XLSX.utils.aoa_to_sheet(data);

    // Dynamic Column Auto-fitting Logic
    const maxCols = data.reduce((max, row) => Math.max(max, row.length), 0);
    const colWidths = [];

    for (let i = 0; i < maxCols; i++) {
        let maxLength = 12; // Minimum baseline width
        data.forEach(row => {
            if (row[i] !== undefined && row[i] !== null) {
                const cellLength = row[i].toString().length;
                if (cellLength > maxLength) {
                    maxLength = cellLength;
                }
            }
        });
        colWidths.push({ wch: maxLength + 5 }); // Plus margin padding characters
    }
    worksheet['!cols'] = colWidths;

    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Attendance");
    XLSX.writeFile(workbook, title + "_Attendance.xlsx");
}

function exportCSV(button)
{
    const card = button.closest('.upload-card');
    const table = card.querySelector('table');

    const title = card.dataset.title;
    const author = card.dataset.author;
    const total = card.dataset.total;
    const male = card.dataset.male;
    const female = card.dataset.female;

    let csv = [];

    // REPORT HEADER
    csv.push(`"PPATH Mobile Attendance Report"`);
    csv.push(`"Activity","${title}"`);
    csv.push(`"Uploaded By","${author}"`);
    csv.push("");

    // SUMMARY
    csv.push(`"Attendance Summary"`);
    csv.push(`"Total","${total}"`);
    csv.push(`"Male","${male}"`);
    csv.push(`"Female","${female}"`);
    csv.push("");

    // TABLE DATA
    const rows = table.querySelectorAll("tr");
    rows.forEach(row => {
        let rowData = [];
        row.querySelectorAll("th, td").forEach(cell => {
            rowData.push(`"${cell.innerText.trim()}"`);
        });
        csv.push(rowData.join(","));
    });

    const blob = new Blob([csv.join("\n")], { type:"text/csv" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");

    a.href = url;
    a.download = title + "_Attendance.csv";
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>
<script>
    function confirmDelete(url) {

    Swal.fire({

        title: 'Delete Table?',

        text: 'This action cannot be undone.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#CB0000',

        cancelButtonText: 'Cancel',

        confirmButtonText: 'Delete'

    }).then((result)=>{


        if(result.isConfirmed){

            window.location.href = url;

        }


    });

}
</script>
    <script>
        function toggleAttendance(button)
{
    const content = button.parentElement.nextElementSibling;
    const arrow = button.querySelector(".arrow");

    content.classList.toggle("hidden");

    if(content.classList.contains("hidden"))
    {
        arrow.style.transform = "rotate(0deg)";
    }
    else
    {
        arrow.style.transform = "rotate(90deg)";
    }
}
    </script>
</body>
</html>
