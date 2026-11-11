<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT) ?: null;
$fields = ['firstname','middlename','lastname','status','position','department','contact_num','gender','email','employment_status','specialization','date_hired','birthday'];
$values = array_fill_keys($fields, '');
$values['status'] = 'Active';
$values['employment_status'] = 'Permanent';
$errors = [];

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM faculty WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $record = $stmt->fetch();
    if ($record) $values = array_merge($values, $record);
    else $errors[] = 'Faculty record not found.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $field) $values[$field] = trim($_POST[$field] ?? '');
    foreach ($fields as $field) if ($values[$field] === '') $errors[] = 'Please complete all faculty fields.';
    if ($values['email'] && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Please provide a valid email address.';

    if (!$errors) {
        try {
            $params = array_intersect_key($values, array_flip($fields));
            if ($id) {
                $set = implode(', ', array_map(fn($field) => "`$field` = :$field", $fields));
                $stmt = $pdo->prepare("UPDATE faculty SET $set WHERE id = :id");
                $params['id'] = $id;
            } else {
                $columns = '`' . implode('`, `', $fields) . '`';
                $placeholders = ':' . implode(', :', $fields);
                $stmt = $pdo->prepare("INSERT INTO faculty ($columns) VALUES ($placeholders)");
            }
            $stmt->execute($params);
            header('Location: faculty.php?success=1');
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Unable to save the faculty record. Please try again.';
        }
    }
}

$pageTitle = $id ? 'Edit Faculty' : 'Add Faculty';
require_once __DIR__ . '/includes/header.php';
?>
<main class="page-shell"><div class="container"><div class="faculty-form-card table-card p-4 p-md-5">
    <div class="mb-4"><a href="faculty.php" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Back to Faculty Management</a><h1 class="h2 mt-3 mb-1"><?= $id ? 'Edit Faculty' : 'Add Faculty' ?></h1><p class="text-muted mb-0">Enter the faculty member's information.</p></div>
    <?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach (array_unique($errors) as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post"><div class="row g-3">
        <div class="col-md-4"><label class="form-label">First Name *</label><input class="form-control" name="firstname" value="<?= htmlspecialchars($values['firstname']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Middle Name *</label><input class="form-control" name="middlename" value="<?= htmlspecialchars($values['middlename']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Last Name *</label><input class="form-control" name="lastname" value="<?= htmlspecialchars($values['lastname']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Status *</label><select class="form-select" name="status" required><?php foreach(['Active','Inactive'] as $option): ?><option <?= $values['status']===$option?'selected':'' ?>><?= $option ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">Position *</label><input class="form-control" name="position" value="<?= htmlspecialchars($values['position']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Department *</label><input class="form-control" name="department" value="<?= htmlspecialchars($values['department']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Contact Number *</label><input class="form-control" name="contact_num" value="<?= htmlspecialchars($values['contact_num']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Gender *</label><select class="form-select" name="gender" required><option value="">Select gender</option><?php foreach(['Male','Female','Other'] as $option): ?><option <?= $values['gender']===$option?'selected':'' ?>><?= $option ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label">Email *</label><input type="email" class="form-control" name="email" value="<?= htmlspecialchars($values['email']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Employment Status *</label><input class="form-control" name="employment_status" value="<?= htmlspecialchars($values['employment_status']) ?>" required></div>
        <div class="col-md-8"><label class="form-label">Specialization *</label><input class="form-control" name="specialization" value="<?= htmlspecialchars($values['specialization']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Date Hired *</label><input type="date" class="form-control" name="date_hired" value="<?= htmlspecialchars($values['date_hired']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Birthday *</label><input type="date" class="form-control" name="birthday" value="<?= htmlspecialchars($values['birthday']) ?>" required></div>
    </div><div class="d-flex justify-content-end gap-2 mt-4"><a href="faculty.php" class="btn btn-light">Cancel</a><button class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Faculty</button></div></form>
</div></div></main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
