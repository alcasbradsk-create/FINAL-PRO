<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT * FROM faculty WHERE id = :id');
$stmt->execute([':id' => $id]);
$member = $stmt->fetch();
$pageTitle = 'View Faculty';
require_once __DIR__ . '/includes/header.php';
$fullName = $member ? trim($member['firstname'] . ' ' . ($member['middlename'] ? $member['middlename'] . ' ' : '') . $member['lastname']) : '';
?>
<main class="page-shell"><div class="container"><div class="faculty-form-card table-card p-4 p-md-5">
    <a href="faculty.php" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Back to Faculty Management</a>
    <?php if (!$member): ?><div class="alert alert-warning mt-4">Faculty record not found.</div><?php else: ?>
        <div class="d-flex justify-content-between align-items-start mt-3 mb-4"><div><span class="eyebrow text-primary">Faculty record #<?= (int)$member['id'] ?></span><h1 class="h2 mb-1"><?= htmlspecialchars($fullName) ?></h1><p class="text-muted mb-0"><?= htmlspecialchars($member['position']) ?> · <?= htmlspecialchars($member['department']) ?></p></div><a href="faculty_form.php?id=<?= (int)$member['id'] ?>" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i> Edit</a></div>
        <div class="row"><div class="col-md-6">
            <?php foreach (['firstname'=>'First Name','middlename'=>'Middle Name','lastname'=>'Last Name','status'=>'Status','position'=>'Position','department'=>'Department','contact_num'=>'Contact Number'] as $field=>$label): ?><div class="detail-label"><?= $label ?></div><div class="detail-value"><?= htmlspecialchars($member[$field] ?: '—') ?></div><?php endforeach; ?>
        </div><div class="col-md-6">
            <?php foreach (['gender'=>'Gender','email'=>'Email','employment_status'=>'Employment Status','specialization'=>'Specialization','date_hired'=>'Date Hired','birthday'=>'Birthday'] as $field=>$label): ?><div class="detail-label"><?= $label ?></div><div class="detail-value"><?= htmlspecialchars($member[$field] ?: '—') ?></div><?php endforeach; ?>
        </div></div>
    <?php endif; ?>
</div></div></main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
