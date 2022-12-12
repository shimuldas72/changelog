<!DOCTYPE html>
<html lang="en">
<head>

    <title>{!! isset($pageTitle) ? $pageTitle : 'Changelog Package' !!}</title>

    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="canonical" href=""/>
    <!-- END META -->
    <!-- CSFR token for ajax call -->
    <meta name="_token" content="{{ csrf_token() }}"/>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    @yield('content')

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
