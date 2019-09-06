<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vending Machine</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="bg-dark">
<div class="container my-5">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Напитки</h4>
            <ul class="list-group mb-4">
                <?php foreach ($products as $product): ?>
                <li class="list-group-item">
                    <strong><?= $this->e($product->name()) ?></strong>
                    <span class="text-muted">- <?= $product->price() ?> руб., осталось <?= $product->amount() ?> шт.</span>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="row">
                <div class="col-sm-6 mb-3">
                    <h5>Ваш кошелек <small class="text-muted"><?= $wallet->total() ?> руб.</small></h5>
                    <ul class="list-group">
                        <?php foreach ($wallet as $value => $count): ?>
                        <li class="list-group-item"><?= $value ?> руб. x <?= $count ?> шт.</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-sm-6 mb-3">
                    <h5>Монеты аппарата <small class="text-muted"><?= $bank->total() ?> руб.</small></h5>
                    <ul class="list-group">
                        <?php foreach ($bank as $value => $count): ?>
                        <li class="list-group-item"><?= $value ?> руб. x <?= $count ?> шт.</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
