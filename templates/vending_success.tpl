<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vending Machine</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .coin {
            position: relative;
            flex: none;
            width: 52px;
            height: 52px;
            margin-right: 15px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d19c35 0%, #f7e6c5 50%, #e8b558 100%);
            box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, .1);
            transition: transform .3s ease-in-out;
        }

        .coin:after {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(45deg, #dfb667 0%, #f9f3e8 56%, #e7c074 96%);
            border: 1px solid rgba(209, 156, 53, .3);
            box-shadow: inset 0 0 2px 2px rgba(153, 106, 26, .05);
        }

        .coin span {
            position: absolute;
            z-index: 1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: Georgia, serif;
            font-style: italic;
            font-size: 22px;
            color: #b8862f;
        }

        .coin:hover {
            transform: translateY(-5px);
        }
    </style>
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
                <li class="list-group-item d-flex align-items-center">
                    <div class="coin"><span><?= $value ?></span></div>
                    <div>
                        Монета: <?= $value ?> руб.<br>
                        Выдано: <?= $count ?> шт.
                    </div>
                </li>
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
