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

        function onScanSuccess(decodedText) {

            alert("Scanned: " + decodedText);

        }


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
