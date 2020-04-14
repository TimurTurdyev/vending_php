<?php $this->extend('layout.tpl', ['title' => 'Ошибка ' . $status]); ?>

<div class="text-center py-5">
    <div class="display-4 text-muted"><?= $status ?></div>
    <p class="lead my-3"><?= $this->e($message) ?></p>
    <a href="<?= $this->e($base) ?>/" class="btn btn-primary">К аппаратам</a>
    <?php if ($status === 403): ?>
    <a href="<?= $this->e($base) ?>/operator/login" class="btn btn-outline-secondary">Войти</a>
    <?php endif; ?>
</div>
