<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $this->e(isset($title) ? $title . ' - Vending' : 'Vending') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= $this->e($base) ?>/assets/app.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= $this->e($base) ?>/">Vending</a>
        <?php if (isset($customer)): ?>
        <div class="d-flex flex-wrap align-items-center">
            <?= $this->render('partials/customer.tpl', ['base' => $base, 'path' => $path, 'world' => $world, 'customer' => $customer]) ?>
            <?= $this->render('partials/operator.tpl', ['base' => $base, 'operator' => $operator]) ?>
        </div>
        <?php endif; ?>
    </div>
</nav>

<main class="container my-4">
    <?php if (!empty($flash['success'])): ?>
    <div class="alert alert-success"><?= $this->e($flash['success']) ?></div>
    <?php endif; ?>

    <?= $content ?>
</main>

<?php if (!empty($flash['error'])): ?>
<div class="modal fade" id="message" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body"><?= $this->e($flash['error']) ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<?php if (!empty($flash['error'])): ?>
<script>
    $('#message').modal('show');
</script>
<?php endif; ?>
</body>
</html>
