<?php
$labels = [];
foreach ($machine->rules()->all() as $rule) {
    if ($machine->rules()->enabled($rule->id())) {
        $labels[] = $rule->label();
    }
}
?>
<?php if ($labels): ?>
<div class="card mb-4">
    <div class="card-body">
        <h2 class="h6">Правила аппарата</h2>
        <ul class="small mb-0 pl-3">
            <?php foreach ($labels as $label): ?>
            <li><?= $this->e($label) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>
