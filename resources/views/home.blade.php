<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Al Omraniya Clinic</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f7fb;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #2d6cdf;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .name {
            font-size: 20px;
            font-weight: bold;
        }

        .specialty {
            color: gray;
            margin-top: 5px;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background: #2d6cdf;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Al Omraniya Medical Center</h1>
</div>

<div class="container">

    <h2>Our Doctors</h2>

    @foreach($doctors as $doctor)
        <div class="card">
            <div class="name">{{ $doctor->name }}</div>
            <div class="specialty">{{ $doctor->specialty }}</div>

            <a href="{{ url('/appointment/' . $doctor->id) }}" class="btn">Book Appointment</a>
            
        </div>
    @endforeach

</div>

</body>
</html>
