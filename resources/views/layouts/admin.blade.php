<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin | Lancar Ekspedisi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f5f6fa; }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1e293b;
        }
        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background: #334155;
            color: #fff;
        }
        .content {
            padding: 25px;
        }
    </style>
</head>
<body>

<div class="d-flex">
    @include('partials.sidebar')

    <div class="flex-grow-1">
        @include('partials.navbar')

        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
