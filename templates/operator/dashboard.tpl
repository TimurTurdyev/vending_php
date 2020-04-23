<?php $this->extend('layout.tpl', ['title' => 'Панель владельца']); ?>

<h1 class="h3 mb-4"><?= $this->e($operator->name()) ?>: аппараты</h1>

<div class="table-responsive">
    <table class="table table-hover bg-white">
        <thead>
        <tr>
            <th>Аппарат</th>
            <th class="text-right">Выручка сегодня</th>
            <th class="text-right">Всего продаж</th>
            <th class="text-right">В кассе</th>
            <th>Внимание</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($machines as $machine): ?>
        <?php
        $currency = $machine->currency();
        $report = $machine->journal()->report();
        $empty = array_filter($machine->slots(), function ($slot) {
            return $slot->sku() !== null && $slot->quantity() <= $slot->capacity() / 4;
        });
        ?>
        <tr>
            <td>
                <a href="<?= $this->e($base) ?>/operator/machines/<?= $this->e($machine->id()) ?>"><?= $this->e($machine->info()->name()) ?></a>
                <div class="small text-muted"><?= $this->e($machine->info()->location()) ?></div>
            </td>
            <td class="text-right"><?= $this->e($currency->format($report->between($today, $today)->revenue())) ?></td>
            <td class="text-right"><?= $report->count() ?></td>
            <td class="text-right"><?= $this->e($currency->format($machine->cash()->coins()->total())) ?></td>
            <td>
                <?php if ($empty): ?>
                <span class="badge badge-warning">Пополнить: <?= $this->e(implode(', ', array_keys($empty))) ?></span>
                <?php endif; ?>
                <?php if ($machine->cash()->exactChangeOnly()): ?>
                <span class="badge badge-info">Мало мелочи</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
