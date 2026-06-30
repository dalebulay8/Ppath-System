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
            font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body{
            background:#eef2f7;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:30px;
        }

        .scanner-card{
            width:100%;
            max-width:700px;
            background:#fff;
            border-radius:12px;
            box-shadow:0 8px 25px rgba(0,0,0,.08);
            overflow:hidden;
        }

        .header{
            background:#0d6efd;
            color:#fff;
            padding:22px 30px;
            border-bottom:1px solid #d9e3f0;
        }

        .header h1{
            font-size:28px;
            font-weight:600;
        }

        .header p{
            margin-top:6px;
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
            color:#444;
        }

        select{
            width:100%;
            padding:12px 14px;
            border:1px solid #cfd6df;
            border-radius:8px;
            font-size:15px;
            outline:none;
            transition:.2s;
            background:#fff;
        }

        select:focus{
            border-color:#0d6efd;
            box-shadow:0 0 0 3px rgba(13,110,253,.15);
        }

        #reader{
            border:1px solid #d9dfe7;
            border-radius:10px;
            overflow:hidden;
            background:#fafafa;
            padding:10px;
        }

        .footer{
            text-align:center;
            padding:18px;
            border-top:1px solid #ececec;
            font-size:13px;
            color:#777;
            background:#fafafa;
        }

        @media (max-width:768px){

            body{
                padding:15px;
            }

            .header{
                padding:18px;
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

<div class="scanner-card">

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

<!-- Keep your existing JavaScript below -->

</body>
</html>
