<?php $this->extend('layout.tpl'); ?>

<h1 class="h3 mb-4">Аппараты</h1>

<div class="list-group">
    <?php foreach ($world->machines() as $machine): ?>
    <a href="<?= $this->e($base) ?>/machines/<?= $this->e($machine->id()) ?>" class="list-group-item list-group-item-action">
        <strong><?= $this->e($machine->info()->name()) ?></strong>
        <span class="text-muted">- <?= $this->e($machine->info()->location()) ?></span>
    </a>
    <?php endforeach; ?>
</div>
