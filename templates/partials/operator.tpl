<?php if ($operator): ?>
<a href="<?= $this->e($base) ?>/operator" class="btn btn-sm btn-warning my-1 ml-2"><?= $this->e($operator->name()) ?></a>
<form method="post" action="<?= $this->e($base) ?>/operator/logout" class="my-1">
    <?= $this->render('partials/csrf.tpl', ['csrf' => $csrf]) ?>
    <button type="submit" class="btn btn-sm btn-link text-light">Выйти</button>
</form>
<?php else: ?>
<a href="<?= $this->e($base) ?>/operator/login" class="btn btn-sm btn-link text-light my-1 ml-2">Владельцам</a>
<?php endif; ?>
