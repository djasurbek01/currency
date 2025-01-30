<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Курсы валют</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Курсы валют</h1>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Валюта</th>
                    <th>Курс</th>
                    <th>Изменение</th>
                </tr>
            </thead>
            <tbody id="currency-table">
                
            </tbody>
        </table>
    </div>

    <script>
        function loadCurrencies() {
            $.ajax({
                url: "{{ route('currency.data') }}",
                method: "GET",
                success: function (data) {
                    let tableBody = $('#currency-table');
                    tableBody.empty();

                    data.forEach(currency => {
                        tableBody.append(`
                            <tr>
                                <td>${currency.name} (${currency.char_code})</td>
                                <td>${currency.value}</td>
                                <td class="${currency.direction}">
                                    ${currency.change.toFixed(4)}
                                </td>
                            </tr>
                        `);
                    });
                },
                error: function (xhr) {
                    console.error("Ошибка загрузки данных:", xhr);
                }
            });
        }

        loadCurrencies();

        setInterval(loadCurrencies, 5000);
    </script>
</body>
</html>
