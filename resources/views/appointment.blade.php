<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
</head>
<body>

<h1>Book Appointment with Dr. {{ $doctor->name }}</h1>

<form method="POST" action="/appointment">
    @csrf

    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

    <input type="text" name="patient_name" placeholder="Your Name"><br><br>

    <input type="text" name="phone" placeholder="Phone"><br><br>

    <input type="date" name="date"><br><br>

    <input type="time" name="time"><br><br>

    <button type="submit">Confirm Booking</button>
</form>

</body>
</html>
