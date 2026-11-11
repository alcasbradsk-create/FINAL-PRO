<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

$message = isset($_GET['success']) ? 'Faculty record saved successfully.' : '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $facultyId = filter_var($_POST['faculty_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$facultyId) {
        $error = 'Invalid faculty record.';
    } else {
        try {
            $stmt = $pdo->prepare('DELETE FROM faculty WHERE id = :id');
            $stmt->execute([':id' => $facultyId]);
            $message = $stmt->rowCount() ? 'Faculty record deleted successfully.' : 'Faculty record was not found.';
        } catch (PDOException $e) {
            $error = 'Unable to delete the faculty record.';
        }
    }
}

try {
    $stmt = $pdo->query('SELECT * FROM faculty ORDER BY lastname, firstname');
    $faculty = $stmt->fetchAll();
} catch (PDOException $e) {
    $faculty = [];
    $error = 'Unable to load faculty records. Please check the database connection.';
}

$pageTitle = 'Faculty Management';
require_once __DIR__ . '/includes/header.php';
?>
<main class="page-shell">
    <div class="container">
        <div class="page-heading">
            <div><span class="eyebrow text-primary">Records</span><h1 class="h2 mb-1">Faculty Management</h1><p class="text-muted mb-0">Manage faculty information stored in the database.</p></div>
            <a href="faculty_form.php" class="btn btn-primary-custom" style="width:auto"><i class="bi bi-person-plus me-1"></i> Add Faculty</a>
        </div>
        <?php if ($message): ?><div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <div class="table-card">
            <?php if (!$faculty): ?>
                <div class="text-center p-5"><i class="bi bi-people fs-1 text-muted"></i><h2 class="h5 mt-3">No faculty records yet</h2><p class="text-muted">Add the first faculty member to begin.</p><a href="faculty_form.php" class="btn btn-primary">Add Faculty</a></div>
            <?php else: ?>
                <div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>ID</th><th>Name</th><th>Status</th><th>Employment</th><th>Department</th><th>Position</th><th class="text-end">Actions</th></tr></thead><tbody>
                <?php foreach ($faculty as $member): ?><tr>
                    <td>#<?= (int) $member['id'] ?></td><td class="fw-semibold"><?= htmlspecialchars(trim($member['firstname'] . ' ' . ($member['middlename'] ?? '') . ' ' . $member['lastname'])) ?></td><td><?= htmlspecialchars($member['status']) ?></td><td><?= htmlspecialchars($member['employment_status']) ?></td><td><?= htmlspecialchars($member['department']) ?></td><td><?= htmlspecialchars($member['position']) ?></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="faculty_view.php?id=<?= (int) $member['id'] ?>" title="View"><i class="bi bi-eye"></i></a> <a class="btn btn-sm btn-outline-secondary" href="faculty_form.php?id=<?= (int) $member['id'] ?>" title="Edit"><i class="bi bi-pencil"></i></a> <form class="d-inline" method="post" onsubmit="return confirm('Are you sure you want to delete this faculty record?');"><input type="hidden" name="action" value="delete"><input type="hidden" name="faculty_id" value="<?= (int) $member['id'] ?>"><button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button></form></td>
                </tr><?php endforeach; ?></tbody></table></div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
