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
            <?php if ($product): ?>
            <h4 class="card-title">Ваш напиток</h4>
            <p class="card-text">
                <?= $this->e($product->name()) ?> x 1 шт.<br>
                Цена: <?= $product->price() ?> руб.
            </p>
            <hr>
            <?php endif; ?>

            <h5 class="card-title">Ваша сдача <small class="text-muted"><?= $change->total() ?> руб.</small></h5>
            <?php if ($change->isEmpty()): ?>
            <p class="text-muted mb-4">Без сдачи</p>
            <?php else: ?>
            <ul class="list-group mb-4">
                <?php foreach ($change as $value => $count): ?>
                <li class="list-group-item"><?= $value ?> руб. x <?= $count ?> шт.</li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <h4 class="text-center mb-3">Хорошего дня!</h4>
            <a href="<?= $this->e($base) ?>/" class="btn btn-primary btn-block">Назад</a>
        </div>
    </div>
</div>
</body>
</html>
