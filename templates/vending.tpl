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
            <h4 class="card-title">Выберите напиток</h4>
            <ul class="list-group mb-4">
                <?php foreach ($products as $product): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <strong><?= $this->e($product->name()) ?></strong>
                        <span class="text-muted">- <?= $product->price() ?> руб., осталось <?= $product->amount() ?> шт.</span>
                    </span>
                    <form method="post" action="<?= $this->e($base) ?>/buy" class="m-0">
                        <button type="submit" name="product" value="<?= $product->id() ?>" class="btn btn-primary btn-sm"<?= $product->inStock() ? '' : ' disabled' ?>>Купить</button>
                    </form>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="alert alert-primary d-flex justify-content-between align-items-center">
                <span>Внесенная сумма: <strong><?= $credit ?> руб.</strong></span>
                <form method="post" action="<?= $this->e($base) ?>/refund" class="m-0">
                    <button type="submit" class="btn btn-outline-primary btn-sm"<?= $credit ? '' : ' disabled' ?>>Сдача</button>
                </form>
            </div>

            <div class="row">
                <div class="col-sm-6 mb-3">
                    <h5>Ваш кошелек <small class="text-muted"><?= $wallet->total() ?> руб.</small></h5>
                    <ul class="list-group">
                        <?php foreach ($wallet as $value => $count): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= $value ?> руб. x <?= $count ?> шт.</span>
                            <form method="post" action="<?= $this->e($base) ?>/insert" class="m-0">
                                <button type="submit" name="coin" value="<?= $value ?>" class="btn btn-success btn-sm"<?= $count ? '' : ' disabled' ?>>Внести</button>
                            </form>
                        </li>
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

<?php if ($error): ?>
<div class="modal fade" id="message" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body"><?= $this->e($error) ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $('#message').modal('show');
</script>
<?php endif; ?>
</body>
</html>
