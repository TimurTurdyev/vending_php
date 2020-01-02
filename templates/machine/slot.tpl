<div class="vm-slot<?= $slot->inStock() ? '' : ' vm-slot--empty' ?>">
    <div class="vm-slot__code"><?= $this->e($slot->code()) ?></div>
    <?php if ($product !== null): ?>
    <div class="vm-slot__name">
        <?= $this->e($product->name()) ?>
    </div>
    <div class="vm-slot__price">
        <?php if ($quote->changed()): ?>
        <s class="text-muted small"><?= $this->e($currency->format($quote->base())) ?></s>
        <?php endif; ?>
        <?= $this->e($currency->format($quote->price())) ?>
    </div>
    <?php foreach ($quote->labels() as $label): ?>
    <div class="vm-slot__label"><?= $this->e($label) ?></div>
    <?php endforeach; ?>
    <form method="post" action="<?= $this->e($action) ?>/buy" class="vm-slot__buy">
        <input type="hidden" name="slot" value="<?= $this->e($slot->code()) ?>">
        <button type="submit" class="btn btn-primary btn-sm btn-block"<?= $slot->inStock() ? '' : ' disabled' ?>><?= $slot->inStock() ? 'Купить' : 'Нет в наличии' ?></button>
    </form>
    <?php else: ?>
    <div class="vm-slot__name text-muted">Пусто</div>
    <?php endif; ?>
</div>
