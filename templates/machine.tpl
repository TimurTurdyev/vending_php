<?php
$this->extend('layout.tpl', ['title' => $machine->info()->name()]);
$currency = $machine->currency();
$credit = $machine->cash()->credit();
$wallet = $customer->wallet($currency);
$action = $base . '/machines/' . $machine->id();
?>

<div class="d-flex flex-wrap justify-content-between align-items-baseline mb-3">
    <h1 class="h3 mb-1"><?= $this->e($machine->info()->name()) ?></h1>
    <span class="text-muted"><?= $this->e($machine->info()->location()) ?></span>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="vm-showcase">
            <?php foreach ($machine->slots() as $code => $slot): ?>
            <?= $this->render('machine/slot.tpl', [
                'slot' => $slot,
                'product' => $slot->sku() !== null ? $machine->product($slot->sku()) : null,
                'quote' => $quotes[$code],
                'currency' => $currency,
                'credit' => $credit,
                'action' => $action,
            ]) ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="vm-panel card mb-4">
            <div class="card-body">
                <div class="vm-display mb-3">
                    <div class="small">Внесено</div>
                    <div class="vm-display__credit"><?= $this->e($busy ? '---' : $currency->format($credit)) ?></div>
                </div>

                <?= $this->render('machine/status.tpl', ['machine' => $machine, 'busy' => $busy, 'now' => $now]) ?>

                <h2 class="h6">Ваш кошелек: <?= $this->e($currency->format($wallet->total())) ?></h2>
                <div class="vm-coins mb-3">
                    <?php foreach ($machine->cash()->coins()->denominations() as $coin): ?>
                    <form method="post" action="<?= $this->e($action) ?>/insert">
                        <button type="submit" name="coin" value="<?= $coin ?>" class="vm-coin"<?= $wallet->of($coin) && !$busy ? '' : ' disabled' ?>>
                            <span class="vm-coin__value"><?= $this->e($currency->format($coin)) ?></span>
                            <span class="vm-coin__count">x <?= $wallet->of($coin) ?></span>
                        </button>
                    </form>
                    <?php endforeach; ?>
                </div>

                <form method="post" action="<?= $this->e($action) ?>/refund">
                    <button type="submit" class="btn btn-outline-secondary btn-block"<?= $credit && !$busy ? '' : ' disabled' ?>>Сдача</button>
                </form>
            </div>
        </div>

        <?php if (!empty($flash['tray'])): ?>
        <?= $this->render('machine/tray.tpl', ['tray' => $flash['tray']]) ?>
        <?php endif; ?>

        <?= $this->render('machine/rules.tpl', ['machine' => $machine]) ?>
    </div>
</div>
