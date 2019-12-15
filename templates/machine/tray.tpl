<div class="vm-tray card mb-4">
    <div class="card-body">
        <h2 class="h6">Лоток выдачи</h2>
        <?php if ($tray['product'] !== null): ?>
        <p class="vm-tray__product mb-1"><?= $this->e($tray['product']) ?></p>
        <p class="small text-muted mb-2">
            Цена <?= $this->e($tray['price']) ?>
        </p>
        <?php endif; ?>
        <?php if ($tray['change']): ?>
        <p class="mb-1">Сдача <?= $this->e($tray['total']) ?>:</p>
        <ul class="list-inline mb-0">
            <?php foreach ($tray['change'] as $item): ?>
            <li class="list-inline-item vm-tray__coin"><?= $this->e($item['coin']) ?> x <?= $item['count'] ?></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p class="text-muted mb-0">Без сдачи</p>
        <?php endif; ?>
    </div>
</div>
