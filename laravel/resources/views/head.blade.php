<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="/styles/css/datepicker/jquery-ui-1.9.2.custom.css">
    <link rel="stylesheet" href="/styles/css/jquery.datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="/styles/css/jquery.fancybox.css">
    <link rel="stylesheet" href="/styles/css/styles.css">
    @if(Route::currentRouteName()   ==  'stylecorporate')
        <link rel="stylesheet" href="/styles/css/corporate.css">
    @endif
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <meta charset="UTF-8">
</head>
