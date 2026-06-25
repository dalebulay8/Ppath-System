<!DOCTYPE html>
<html>
<head>
    <title>PPATH QR Scanner</title>

    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body>

<h1>PPATH QR Scanner</h1>

<h3>Select Attendance Table</h3>

<select id="activity_id">
    @foreach($activities as $activity)
        <option value="{{ $activity->id }}">
            {{ $activity->title }}
        </option>
    @endforeach
</select>

<br><br>

<div id="reader"></div>

<script>
let isScanning = false;

function onScanSuccess(decodedText) {

    // Prevent multiple triggers
    if (isScanning) return;
    isScanning = true;

    console.log("Scanned:", decodedText);

    let activity_id = document.getElementById('activity_id').value;

    // Expected format: Name|Gender
    let data = decodedText.split("|");

    if (data.length !== 2) {
        alert("Invalid QR Format!\nExpected: Name|Gender");
        resetScanner();
        return;
    }

    let name = data[0].trim();
    let gender = data[1].trim();

    fetch('/scanner/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            activity_id: activity_id,
            name: name,
            gender: gender
        })
    })
    .then(response => response.json())
    .then(data => {

        console.log(data);

        if (data.success) {
            alert('Attendance Recorded Successfully');
        } else {
            alert(data.message || 'Failed to record attendance');
        }

    })
    .catch(error => {
        console.log(error);
        alert("Error:\n" + error);
    })
    .finally(() => {
        resetScanner();
    });
}

// Reset scanner lock (prevents spam scanning)
function resetScanner() {
    setTimeout(() => {
        isScanning = false;
    }, 2000);
}

// QR Scanner init
let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: 250
    }
);

html5QrcodeScanner.render(onScanSuccess);

</script>

</body>
</html>
