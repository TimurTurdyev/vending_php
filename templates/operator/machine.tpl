<?php
$this->extend('layout.tpl', ['title' => $machine->info()->name()]);
$currency = $machine->currency();
$cash = $machine->cash();
$action = $base . '/operator/machines/' . $machine->id();
?>

<p><a href="<?= $this->e($base) ?>/operator">&larr; Все аппараты</a></p>
<h1 class="h3 mb-4"><?= $this->e($machine->info()->name()) ?></h1>

<div class="row">
    <div class="col-lg-8">
        <h2 class="h5">Ячейки</h2>
        <div class="table-responsive mb-4">
            <table class="table table-sm bg-white">
                <thead>
                <tr><th>Ячейка</th><th>Товар</th><th>Остаток</th><th>Пополнить</th></tr>
                </thead>
                <tbody>
                <?php foreach ($machine->slots() as $slot): ?>
                <tr>
                    <td><?= $this->e($slot->code()) ?></td>
                    <td><?= $this->e($slot->sku() !== null ? $machine->product($slot->sku())->name() : '-') ?></td>
                    <td><?= $slot->quantity() ?> / <?= $slot->capacity() ?></td>
                    <td>
                        <form method="post" action="<?= $this->e($action) ?>/restock" class="form-inline">
                            <input type="hidden" name="slot" value="<?= $this->e($slot->code()) ?>">
                            <button type="submit" class="btn btn-sm btn-outline-primary"<?= $slot->sku() === null || $slot->quantity() === $slot->capacity() ? ' disabled' : '' ?>>До полной</button>
                        </form>
                    </td>                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h5">Касса: <?= $this->e($currency->format($cash->coins()->total())) ?></h2>
                <ul class="list-unstyled small">
                    <?php foreach ($cash->coins() as $coin => $count): ?>
                    <li><?= $this->e($currency->format($coin)) ?> x <?= $count ?></li>
                    <?php endforeach; ?>
                </ul>
                <p class="small text-muted">При инкассации остается по <?= $cash->policy()->keep() ?> монет каждого номинала</p>
                <form method="post" action="<?= $this->e($action) ?>/collect">
                    <button type="submit" class="btn btn-success btn-block">Забрать выручку</button>
                </form>
            </div>
        </div>
    </div>
</div>
