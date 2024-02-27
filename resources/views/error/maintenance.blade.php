<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css");

        *{
        font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-light wh-100">
    <div class="container w-50 text-center py-5">
        <div class="">
            <h1 class="mb-2">We'll be back soon!</h1>
            <h2 class="mb-3">Sorry for the inconvenience but we're performing some maintenance at the moment.</h2>
            <p>If you need to you can always contact us, otherwise we'll be back online shortly!</p>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3 cala">Go Home</a>
        </div>
        
    </div>
</body>
</html>

