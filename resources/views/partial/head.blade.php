<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>GA-STOK || @yield('title')</title>

    <meta name="description" content="Internal Program STOK Opname GA">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Internal Program">
    <meta property="og:site_name" content="GA">
    <meta property="og:description" content="Internal Program STOK Opname GA">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ url('assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ url('assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('assets/media/favicons/apple-touch-icon-180x180.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and Codebase framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ url('assets/css/codebase.min.css') }}">

    <style type="text/css">
    	.banitem {
	      -webkit-animation:bounce 1s infinite;
	    }
	    
	    @-webkit-keyframes bounce {
	      0%       { bottom:5px; }
	      25%, 75% { bottom:15px; }
	      50%      { bottom:20px; }
	      100%     {bottom:0;}
	    }
    </style>
    @yield('css')
</head>