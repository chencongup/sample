<?php
/**
 * Created by PhpStorm.
 * User: flytienon
 * Date: 2018/12/13
 * Time: 16:58
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title','sample app') - Laravel 入门教程</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header class="navbar navbar-fixed-top navbar-inverse">
        <div class="container">
            <div class="col-md-offset-1 col-md-10">
                <a href="/" id="logo">Sample App</a>
                <nav>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{ route('help') }}">帮助</a></li>
                        <li><a href="#">登陆</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>