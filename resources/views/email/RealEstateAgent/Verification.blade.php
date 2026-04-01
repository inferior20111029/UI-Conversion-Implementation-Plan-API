<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <title>Password Email</title>
    <style>
        .email-body {
            width: calc(100% - 40px);
            height: calc(100vh - 32px);
            background-color: #F5F7FA;
            margin: 0;
            padding: 20px 16px;
            color: #273045;
        }

        .container {
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            margin-bottom: 15px;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0px 0px 12px 0px rgba(221, 230, 234, 0.3);
            border-radius: 20px;
        }

        .header {
            text-align: center;
            color: #1E1F24;
            padding: 5px 0;
        }

        .header h1 {
            margin: 0;
            margin-top: 10px;
        }

        .header .icon-container {
            display: inline-block;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            overflow: hidden;
        }

        .content {
            text-align: center;
            margin: 8px 0;
        }

        .content p {
            margin: 0;
            line-height: 20px;
            font-size: 16px;
        }

        .content p+.content p {
            margin-top: 10px;
        }

        .button {
            display: inline-block;
            padding: 10px 25px;
            font-size: 16px;
            color: white !important;
            background-color: #165DFF;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s;
            margin: 20px;
            cursor: pointer !important;
        }

        .button:hover {
            background-color: #1252e2;
        }

        .footer {
            text-align: center;
            color: #B9BBC6;
            font-size: 15px;
            margin-top: 20px;
        }

        .footer a {
            cursor: pointer !important;
            text-decoration: none;
            color: inherit !important;;
        }
    </style>
</head>

<body>
    <div class="email-body">
        <div class="container">
            <div class="header">
                <h1>請驗證您的帳號</h1>
            </div>
            <div class="content">
                <p>您的房仲帳號已經建立！</p>
                <p>請點擊下方按鈕驗證您的帳號</p>
                <a target="_blank" href="{{ $shortUrl }}" class="button">驗證</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; <a href="https://www.ezplus.com.tw" target="_blank">億集科技</a> EZPlus</p>
        </div>
    </div>
</body>
</html>
