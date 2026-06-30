<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPATH QR Scanner</title>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body{
            background:#f4f6f9;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:30px;
        }

        .container{
            width:100%;
            max-width:720px;
            background:#fff;
            border-radius:12px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            overflow:hidden;
        }

        .header{
            background:#1f4e79;
            color:#fff;
            padding:25px 30px;
        }

        .header h1{
            font-size:28px;
            font-weight:600;
            margin-bottom:5px;
        }

        .header p{
            font-size:14px;
            opacity:.9;
        }

        .content{
            padding:30px;
        }

        .form-group{
            margin-bottom:25px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:600;
            color:#333;
        }

        select{
            width:100%;
            padding:12px;
            border:1px solid #ced4da;
            border-radius:8px;
            font-size:15px;
            outline:none;
            background:#fff;
        }

        select:focus{
            border-color:#1f4e79;
            box-shadow:0 0 0 3px rgba(31,78,121,.15);
        }

        #reader{
            border:1px solid #dee2e6;
            border-radius:10px;
            background:#fafafa;
            padding:15px;
        }

        /* Hide image upload option */
        #reader__filescan_region{
            display:none !important;
        }

        #reader__dashboard_section_swaplink{
            display:none !important;
        }

        /* Style Start/Stop buttons */
        #reader button{
            background:#1f4e79 !important;
            color:#fff !important;
            border:none !important;
            border-radius:6px !important;
            padding:10px 18px !important;
            font-size:15px !important;
            font-weight:600 !important;
            cursor:pointer !important;
            transition:.25s;
        }

        #reader button:hover{
            background:#163a5c !important;
        }

        /* Camera dropdown inside scanner */
        #reader select{
            width:auto !important;
            margin-right:10px;
            border-radius:6px;
            padding:8px 12px;
        }

        .footer{
            text-align:center;
            padding:18px;
            border-top:1px solid #e9ecef;
            color:#777;
            font-size:13px;
            background:#fafafa;
        }

        @media(max-width:768px){

            body{
                padding:15px;
            }

            .header{
                padding:20px;
            }

            .content{
                padding:20px;
            }

            .header h1{
                font-size:24px;
            }

        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>PPATH QR Scanner</h1>
        <p>Scan participant QR codes to record attendance.</p>
    </div>

    <div class="content">

        <div class="form-group">
            <label>Select Attendance Activity</label>

            <select id="activity_id">
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">
                        {{ $activity->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="reader"></div>

    </div>

    <div class="footer">
        PPATH Attendance Monitoring System
    </div>

</div>

<script>

let isScanning = false;

function onScanSuccess(decodedText) {

    if (isScanning) return;
    isScanning = true;

    console.log("Scanned:", decodedText);

    let activity_id = document.getElementById('activity_id').value;

    // Expected format: Name/Gender
    let data = decodedText.split("/");

    if (data.length !== 2) {
        alert("Invalid QR Format!\nExpected: Name/Gender");
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
        console.error(error);
        alert("An error occurred while recording attendance.");
    })
    .finally(() => {
        resetScanner();
    });

}

function resetScanner() {
    setTimeout(() => {
        isScanning = false;
    }, 2000);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: 250,
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
    }
);

html5QrcodeScanner.render(onScanSuccess);

</script>

</body>
</html>
