<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>AtlasVG - Administrator</title>
    <meta name="author" content="usantisteban@othercode.es">

    <link href="{{URL::to('/')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/admin.colors.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/admin.style.css" rel="stylesheet">

    <link rel="shortcut icon" href="#">
</head>
<body>

<div class="outer">

    <div class="sidebar">
        <div class="sidey">

            <div class="logo">
                <h1>
                    <a href="{{URL::to('/')}}/admin">
                        <i class="fa fa-map-marker br-red"></i> AtlasVG <span>Find your way</span>
                    </a>
                </h1>
            </div>

            <div class="sidebar-dropdown">
                <a href="#" class="br-red"><i class="fa fa-bars"></i></a>
            </div>

            <div class="side-nav">
                <div class="side-nav-block">
                    <h4>Main</h4>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{URL::to('/')}}" target="_blank">
                                <i class="fa fa-map"></i> Front
                            </a>
                        </li>
                    </ul>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{URL::to('/')}}/admin">
                                <i class="fa fa-desktop"></i> Dashboard</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mainbar">

        <div class="main-head">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <h2><i class="fa fa-desktop lblue"></i> Dashboard</h2>
                    </div>

                    <div class="col-md-3 col-sm-4 hidden-xs">
                        <div class="clearfix"></div>
                    </div>

                </div>
            </div>

        </div>

        <div class="main-content">
            <div class="container">
                @yield('content')
            </div>
        </div>

    </div>

    <div class="clearfix"></div>
</div>

</body>