<?php
// Faculty Management: list of all faculty records with View, Edit and Delete.
require_once __DIR__ . '/includes/auth.php';

const PER_PAGE = 20;

$search = trim((string) ($_GET['q'] ?? ''));
$page = max(1, (int) ($_GET['page'] ?? 1));

/** Link back to this list, keeping the current search and page. */
function list_url(array $changes = []): string
{
    global $search, $page;
    $query = array_filter(array_merge(['q' => $search, 'page' => $page > 1 ? $page : null], $changes),
        fn($value) => $value !== null && $value !== '');
    return 'faculty.php' . ($query ? '?' . http_build_query($query) : '');
}

$messages = [
    'added'    => 'Faculty record added successfully.',
    'updated'  => 'Faculty record updated successfully.',
    'deleted'  => 'Faculty record deleted successfully.',
    'notfound' => 'Faculty record was not found.',
];
$message = $messages[$_GET['msg'] ?? ''] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $facultyId = filter_var($_POST['faculty_id'] ?? null, FILTER_VALIDATE_INT);
    if (!csrf_valid()) {
        $error = 'Your session expired. Please try again.';
    } elseif (!$facultyId) {
        $error = 'Invalid faculty record.';
    } else {
        try {
            $stmt = $pdo->prepare('DELETE FROM faculty WHERE id = :id');
            $stmt->execute([':id' => $facultyId]);
            redirect(list_url(['msg' => $stmt->rowCount() ? 'deleted' : 'notfound']));
        } catch (PDOException $e) {
            error_log('[faculty-system] faculty delete failed: ' . $e->getMessage());
            $error = 'Unable to delete the faculty record.';
        }
    }
}

$faculty = [];
$total = 0;
$pages = 1;
try {
    $where = '';
    $params = [];
    if ($search !== '') {
        $like = '%' . addcslashes($search, '%_\\') . '%';
        $searchable = ["CONCAT_WS(' ', firstname, middlename, lastname)", 'department', 'position', 'specialization', 'email'];
        $conditions = [];
        foreach ($searchable as $i => $column) {
            $conditions[] = "$column LIKE :q$i";
            $params[":q$i"] = $like;
        }
        $where = 'WHERE ' . implode(' OR ', $conditions);
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM faculty $where");
    $stmt->execute($params);
    $total = (int) $stmt->fetchColumn();
    $pages = max(1, (int) ceil($total / PER_PAGE));
    $page = min($page, $pages);

    $stmt = $pdo->prepare("SELECT id, firstname, middlename, lastname, gender, email, status, department, position
        FROM faculty $where ORDER BY id LIMIT :limit OFFSET :offset");
    foreach ($params as $name => $value) {
        $stmt->bindValue($name, $value);
    }
    $stmt->bindValue(':limit', PER_PAGE, PDO::PARAM_INT);
    $stmt->bindValue(':offset', ($page - 1) * PER_PAGE, PDO::PARAM_INT);
    $stmt->execute();
    $faculty = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('[faculty-system] faculty list failed: ' . $e->getMessage());
    $error = 'Unable to load faculty records. Please try again later.';
}

$pageTitle = 'Faculty Management';
$layout = 'app';
require_once __DIR__ . '/includes/header.php';
$firstShown = $total ? ($page - 1) * PER_PAGE + 1 : 0;
$lastShown = min($page * PER_PAGE, $total);
?>

<div class="page-header">
    <div>
        <h1 class="page-header__title">Faculty Management</h1>
        <p class="page-header__lead">Manage faculty information stored in the database.</p>
    </div>
    <div class="page-header__actions">
        <a href="faculty_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Faculty</a>
    </div>
</div>

<?php if ($message): ?><div class="alert alert-success" role="status"><i class="bi bi-check-circle"></i><div><?= htmlspecialchars($message) ?></div></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-circle"></i><div><?= htmlspecialchars($error) ?></div></div><?php endif; ?>

<section class="card-x">
    <form method="get" action="faculty.php" class="toolbar" role="search">
        <div class="input-icon toolbar__search" id="search">
            <i class="bi bi-search"></i>
            <input type="search" class="form-control" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search faculty" aria-label="Search faculty">
        </div>
        <button class="btn btn-secondary">Search</button>
        <?php if ($search !== ''): ?><a href="faculty.php" class="btn btn-ghost">Clear</a><?php endif; ?>
        <span class="toolbar__meta tabular"><?= $total ?> record<?= $total === 1 ? '' : 's' ?></span>
    </form>

    <?php if (!$faculty): ?>
        <?php if ($search !== ''): ?>
            <div class="empty">
                <span class="empty__icon"><i class="bi bi-search"></i></span>
                <h2 class="empty__title">No matching faculty records</h2>
                <p class="empty__text">Nothing matches “<?= htmlspecialchars($search) ?>”. Check the spelling or try a department name.</p>
                <a href="faculty.php" class="btn btn-secondary">Clear search</a>
            </div>
        <?php else: ?>
            <div class="empty">
                <span class="empty__icon"><i class="bi bi-person-plus"></i></span>
                <h2 class="empty__title">No faculty records yet</h2>
                <p class="empty__text">Add the first faculty member to begin the registry.</p>
                <a href="faculty_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Faculty</a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table-x">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Sex</th>
                        <th scope="col">Email</th>
                        <th scope="col">Department</th>
                        <th scope="col">Position</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($faculty as $member): ?>
                    <tr>
                        <td class="tabular text-muted"><?= (int) $member['id'] ?></td>
                        <td>
                            <div class="person">
                                <?= avatar($member) ?>
                                <div class="min-w-0">
                                    <a class="person__name" href="faculty_view.php?id=<?= (int) $member['id'] ?>"><?= htmlspecialchars(full_name($member)) ?></a>
                                    <div class="person__meta"><?= status_badge($member['status']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($member['gender']) ?></td>
                        <td><?= htmlspecialchars($member['email']) ?></td>
                        <td><?= htmlspecialchars($member['department']) ?></td>
                        <td><?= htmlspecialchars($member['position']) ?></td>
                        <td>
                            <div class="row-actions">
                                <a class="btn btn-sm btn-secondary" href="faculty_view.php?id=<?= (int) $member['id'] ?>"><i class="bi bi-eye"></i> View</a>
                                <a class="btn btn-sm btn-secondary" href="faculty_form.php?id=<?= (int) $member['id'] ?>"><i class="bi bi-pencil"></i> Edit</a>
                                <button type="button" class="btn btn-sm btn-secondary text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= (int) $member['id'] ?>" data-name="<?= htmlspecialchars(full_name($member)) ?>" ><i class="bi bi-trash3"></i> Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pager">
            <span class="tabular">Showing <?= $firstShown ?>–<?= $lastShown ?> of <?= $total ?></span>
            <?php if ($pages > 1): ?>
                <nav class="pager__pages" aria-label="Faculty pages">
                    <a class="pager__link <?= $page <= 1 ? 'is-disabled' : '' ?>" href="<?= htmlspecialchars(list_url(['page' => $page - 1])) ?>" aria-label="Previous page"><i class="bi bi-chevron-left"></i></a>
                    <?php for ($p = 1; $p <= $pages; $p++): ?>
                        <a class="pager__link <?= $p === $page ? 'is-current' : '' ?>" href="<?= htmlspecialchars(list_url(['page' => $p])) ?>" <?= $p === $page ? 'aria-current="page"' : '' ?>><?= $p ?></a>
                    <?php endfor; ?>
                    <a class="pager__link <?= $page >= $pages ? 'is-disabled' : '' ?>" href="<?= htmlspecialchars(list_url(['page' => $page + 1])) ?>" aria-label="Next page"><i class="bi bi-chevron-right"></i></a>
                </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php $deleteAction = list_url(); require_once __DIR__ . '/includes/footer.php'; ?>
