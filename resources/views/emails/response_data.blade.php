<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $data['companyName'] }}</title>
    <style>
        /* Reset styles for email clients */
        body, table, td, p, a {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            /* max-width: 600px; */
        }

        /* Header styles */
        .header {
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        /* Content container */
        .content {
            padding: 30px;
            text-align: center;
        }

        /* Footer styles */
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <h1>{{ $data['companyName'] }}</h1>
            </td>
        </tr>
    </table>

    <table class="content">
        <tr>
            <td>
                <h2>Start Date and End Date:,</h2>
                <p>From {{ $data['startDate'] }} to {{ $data['endDate'] }}</p>
            </td>
        </tr>
    </table>

    <table class="footer">
        <tr>
            <td>
                <p>Created By Saquib Hasan, saquibhasan3@gmail.com</p>
            </td>
        </tr>
    </table>
</body>
</html>
