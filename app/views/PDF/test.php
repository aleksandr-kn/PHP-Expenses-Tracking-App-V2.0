<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://unpkg.com/gutenberg-css@0.6">
    <style>
        body {
          font-family: DejaVu Sans;
          font-size: 14px;
        }
        table {
            width: 100%;
        }
        footer {
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    
    <!-- <img src="example.png"> -->
    
    <h1>Invoice</h1>
    
    <p>Name: {{ name }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Источник</th>
                <th>Дата</th>
                <th>Дата</th>
                <th>Категория</th>
                <th>Доп. Категория</th>
            </tr>
        </thead>
        {{ spendings }}
        <tbody>
            <tr>
                <td>A sample product</td>
                <td>A sample product</td>
                <td>A sample product</td>
                <td>A sample product</td>
                <td>A sample product</td>
                <td>A sample product</td>
                <td>A sample product</td>
            </tr>
        </tbody>
    </table>
    
    <footer>
        This is an example
    </footer>
    
</body>
</html>