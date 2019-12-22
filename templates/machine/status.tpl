<?php if ($machine->cash()->exactChangeOnly()): ?>
<div class="alert alert-info small">Мало мелочи: аппарат продает только без сдачи</div>
<?php endif; ?>
<?php if ($machine->cash()->policy()->maxChange() !== null): ?>
<p class="small text-muted">Сдача не больше <?= $this->e($machine->currency()->format($machine->cash()->policy()->maxChange())) ?></p>
<?php endif; ?>
