<div class="vm-slot<?= $slot->inStock() ? '' : ' vm-slot--empty' ?>">
    <div class="vm-slot__code"><?= $this->e($slot->code()) ?></div>
    <?php if ($product !== null): ?>
    <div class="vm-slot__name">
        <?= $this->e($product->name()) ?>
        <?php if ($product->adult()): ?><span class="badge badge-danger">18+</span><?php endif; ?>
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
    <?php if ($slot->inStock() && $credit > 0 && $credit < $quote->price()): ?>
    <div class="vm-slot__hint">Не хватает <?= $this->e($currency->format($quote->price() - $credit)) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= $this->e($action) ?>/buy" class="vm-slot__buy">
        <input type="hidden" name="slot" value="<?= $this->e($slot->code()) ?>">
        <?php if ($product->adult()): ?>
        <div class="custom-control custom-checkbox small mb-1">
            <input type="checkbox" name="confirm_age" value="1" id="age-<?= $this->e($slot->code()) ?>" class="custom-control-input">
            <label class="custom-control-label" for="age-<?= $this->e($slot->code()) ?>">Мне есть 18</label>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary btn-sm btn-block"<?= $slot->inStock() ? '' : ' disabled' ?>><?= $slot->inStock() ? 'Купить' : 'Нет в наличии' ?></button>
    </form>
    <?php else: ?>
    <div class="vm-slot__name text-muted">Пусто</div>
    <?php endif; ?>
</div>
