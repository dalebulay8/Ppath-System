<!DOCTYPE html>
<html>
<head>
    <title>PPATH QR Scanner</title>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            background:#f4f6f9;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
        }

        .container{
            width:100%;
            max-width:500px;
            background:#fff;
            padding:30px;
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,0.1);
            text-align:center;
        }

        h1{
            color:#2c3e50;
            margin-bottom:10px;
        }

        p{
            color:#777;
            margin-bottom:25px;
        }

        label{
            display:block;
            text-align:left;
            font-weight:bold;
            margin-bottom:8px;
            color:#444;
        }

        select{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:15px;
            margin-bottom:20px;
            outline:none;
        }

        #reader{
            border:2px solid #e5e5e5;
            border-radius:10px;
            overflow:hidden;
        }

        footer{
            margin-top:20px;
            color:#888;
            font-size:13px;
        }

        @media(max-width:600px){
            .container{
                margin:15px;
                padding:20px;
            }
        }
    </style>

</head>

<body>

<div class="container">

    <h1>📷 PPATH QR Scanner</h1>
    <p>Scan participant QR Code to record attendance.</p>

    <label>Select Attendance Activity</label>

    <select id="activity_id">
        @foreach($activities as $activity)
            <option value="{{ $activity->id }}">
                {{ $activity->title }}
            </option>
        @endforeach
    </select>

    <div id="reader"></div>

    <footer>
        PPATH Attendance Monitoring System
    </footer>

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

        if(data.success){
            alert("Attendance Recorded Successfully");
        }else{
            alert(data.message || "Failed to record attendance");
        }

    })
    .catch(error=>{
        console.log(error);
        alert("Error:\n"+error);
    })
    .finally(()=>{
        resetScanner();
    });

}

function resetScanner(){
    setTimeout(()=>{
        isScanning = false;
    },2000);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
        fps:10,
        qrbox:250
    }
);

html5QrcodeScanner.render(onScanSuccess);

</script>

</body>
</html>
