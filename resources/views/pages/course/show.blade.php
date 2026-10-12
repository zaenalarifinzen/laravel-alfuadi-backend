<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Course Detail</title>
</head>
<body>
    <h1>{{ $course->title }}</h1>
    <p>{{ $course->description }}</p>
    <p>{{ $course->price }}</p>
</body>
</html>