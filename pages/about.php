<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .about-box {
            background-color: rgba(0, 0, 0, 0.4);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            max-width: 500px;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        p {
            font-size: 1.2em;
            line-height: 1.6em;
        }
    </style>
</head>
<body>
    <div class="about-box">
        <h1>About Me</h1>
        <p><strong>Name:</strong> Nathani Leela Krishna</p>
        <p><strong>College:</strong> NIT Calicut (2023 - 2027)</p>
        <p>This is just for the sake of practice and while making this website i used AI tools for design and writing few blovks of code snippets.</p>
    </div>
</body>
</html>