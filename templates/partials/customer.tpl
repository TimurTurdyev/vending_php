<form method="post" action="<?= $this->e($base) ?>/customers/choose" class="form-inline my-1">
    <input type="hidden" name="back" value="<?= $this->e($path) ?>">
    <label class="text-light small mr-2" for="customer">Покупатель</label>
    <select name="customer" id="customer" class="custom-select custom-select-sm" data-autosubmit>
        <?php foreach ($world->customers() as $item): ?>
        <option value="<?= $this->e($item->id()) ?>"<?= $item->id() === $customer->id() ? ' selected' : '' ?>><?= $this->e($item->name()) ?></option>
        <?php endforeach; ?>
    </select>
    <noscript><button type="submit" class="btn btn-sm btn-outline-light ml-1">OK</button></noscript>
</form>
<form method="post" action="<?= $this->e($base) ?>/customers/new" class="my-1 ml-1">
    <input type="hidden" name="back" value="<?= $this->e($path) ?>">
    <button type="submit" class="btn btn-sm btn-link text-light">Новый гость</button>
</form>
