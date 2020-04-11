<?php $this->extend('layout.tpl', ['title' => 'Вход для владельцев']); ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <h1 class="h4 mb-3">Вход для владельцев</h1>
        <form method="post" action="<?= $this->e($base) ?>/operator/login" class="card card-body">
            <?= $this->render('partials/csrf.tpl', ['csrf' => $csrf]) ?>
            <div class="form-group">
                <label for="login">Логин</label>
                <input type="text" name="login" id="login" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Войти</button>
        </form>
    </div>
</div>
