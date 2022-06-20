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
    
    <h1 style="font-family: DejaVu Sans;">Расходы</h1>
    
    <p>Название: {{ name }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Источник</th>
                <th>Дата</th>
                <th>Категория</th>
                <th>Доп. Категория</th>
            </tr>
        </thead>
        
        <tbody>
            {{ spendings }}
        </tbody>
    </table>
    
    <footer>
        Онлайн платформа для планирования расходов
    </footer>
    
</body>
</html>