<?php $this->extend('layout.tpl'); ?>

<h1 class="h3 mb-4">Аппараты</h1>

<div class="row">
    <?php foreach ($world->machines() as $machine): ?>
    <?php
    $occupied = $machine->busyFor($customer, $now);
    $stock = 0;
    foreach ($machine->slots() as $slot) {
        $stock += $slot->quantity();
    }
    ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <a href="<?= $this->e($base) ?>/machines/<?= $this->e($machine->id()) ?>" class="vm-card card h-100">
            <div class="card-body">
                <h2 class="h5 card-title mb-1"><?= $this->e($machine->info()->name()) ?></h2>
                <p class="text-muted small mb-3"><?= $this->e($machine->info()->location()) ?></p>
                <p class="mb-2">
                    <?php if ($occupied): ?>
                    <span class="badge badge-warning">Занят</span>
                    <?php else: ?>
                    <span class="badge badge-success">Свободен</span>
                    <?php endif; ?>
                    <?php if ($machine->cash()->exactChangeOnly()): ?>
                    <span class="badge badge-info">Только без сдачи</span>
                    <?php endif; ?>
                    <span class="badge badge-light"><?= $this->e($machine->currency()->code()) ?></span>
                </p>
                <p class="small text-muted mb-0">Товаров: <?= count($machine->products()) ?>, в наличии <?= $stock ?> шт.</p>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
