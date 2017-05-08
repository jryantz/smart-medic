<!doctype html>

<html>
    <head>
        <link href="css/app.css" type="text/css" rel="stylesheet">
        <link href="css/loading.css" type="text/css" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
        <title>Smart Medic</title>
    </head>

    <body style="overflow: hidden;">
        <div class="window">
            <div class="main-container">
                <p class="title">Welcome to Smart Medic</p>
                <p class="subtitle">Where your ailments are solved.</p>
                <center><ul class="loading">
                    <li>&#8226;</li>
                    <li>&#8226;</li>
                    <li>&#8226;</li>
                    <li>&#8226;</li>
                </ul></center>
            </div>
        </div>

        <div class="window" id="welcome" style="transform: translateX(100%)">
            <div class="main-container">
                <p class="title">Welcome to Smart Medic</p>
                <br>
                <p class="question">How to use:</p>
                <p class="subtitle">1. Follow any instructions on the screen.</p>
                <p class="subtitle">2. Click the listed symptoms that apply to you.</p>
                <br><br>
                <center class="button">
                    <a href="symptoms">Get Started</a>
                </center>
            </div>
        </div>

        <script>
            setTimeout(function () { document.getElementById('welcome').style = ''; }, 3000);
        </script>
    </body>
</html>
