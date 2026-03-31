<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>您的帳號已驗證完畢</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        ul {
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $realEstateAgent->name ?? '' }} 您好！</h1>
        <p>以下是您的帳號識別碼，請妥善保管</p>
        <p>{{ $realEstateAgent->identification_code ?? '' }}</p>
    </div>
</body>
</html>
