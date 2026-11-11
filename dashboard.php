<?php
// Dashboard shown after login, with the Faculty Management menu and a summary of the records.
require_once __DIR__ . '/includes/auth.php';

$stats = $pdo->query("SELECT
            COUNT(*) AS total,
            COALESCE(SUM(status = 'Active'), 0) AS active,
            COUNT(DISTINCT department) AS departments,
            COALESCE(SUM(created_at >= NOW() - INTERVAL 30 DAY), 0) AS recent
        FROM faculty")->fetch();

    $recent = $pdo->query("SELECT id, firstname, middlename, lastname, position, department, status, updated_at
        FROM faculty ORDER BY updated_at DESC, id DESC LIMIT 6")->fetchAll();

    $departments = $pdo->query("SELECT department, COUNT(*) AS total
        FROM faculty GROUP BY department ORDER BY total DESC, department LIMIT 6")->fetchAll();

$pageTitle = 'Dashboard';
$layout = 'app';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div>
        <span class="eyebrow">Dashboard · <?= date('l, F j') ?></span>
        <h1 class="page-header__title">Welcome, <?= htmlspecialchars($currentUser['firstname']) ?></h1>
        <p class="page-header__lead">Signed in as <strong><?= htmlspecialchars($currentUser['username']) ?></strong></p>
    </div>
    <div class="page-header__actions">
        <a href="faculty.php" class="btn btn-secondary"><i class="bi bi-people"></i> View Faculty</a>
        <a href="faculty_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Faculty</a>
    </div>
</div>

    <section class="card-x mb-4 fm-menu">
        <div class="card-x__head">
            <h2 class="card-x__title"><i class="bi bi-person-vcard me-2"></i>Faculty Management</h2>
        </div>
        <div class="fm-menu__grid">
            <a class="fm-menu__item" href="faculty.php">
                <i class="bi bi-list-ul"></i>
                <span><strong>Faculty List</strong><small>View, edit and delete faculty records</small></span>
            </a>
            <a class="fm-menu__item" href="faculty_form.php">
                <i class="bi bi-person-plus"></i>
                <span><strong>Add Faculty</strong><small>Create a new faculty record</small></span>
            </a>
            <a class="fm-menu__item" href="faculty.php#search">
                <i class="bi bi-search"></i>
                <span><strong>Search Faculty</strong><small>Find a record by name, department or position</small></span>
            </a>
        </div>
    </section>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat">
                <div class="stat__label"><i class="bi bi-person-vcard"></i> Total Faculty</div>
                <div class="stat__value"><?= number_format((int) $stats['total']) ?></div>
                <div class="stat__hint"><?= (int) $stats['recent'] ?> added in the last 30 days</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat">
                <div class="stat__label"><i class="bi bi-check2-circle"></i> Active</div>
                <div class="stat__value"><?= number_format((int) $stats['active']) ?></div>
                <div class="stat__hint">
                    <?= $stats['total'] ? round($stats['active'] / $stats['total'] * 100) . '% of all faculty' : 'No records yet' ?>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat">
                <div class="stat__label"><i class="bi bi-building"></i> Departments</div>
                <div class="stat__value"><?= number_format((int) $stats['departments']) ?></div>
                <div class="stat__hint">With at least one faculty member</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat">
                <div class="stat__label"><i class="bi bi-pause-circle"></i> Inactive</div>
                <div class="stat__value"><?= number_format((int) $stats['total'] - (int) $stats['active']) ?></div>
                <div class="stat__hint">On leave, retired or separated</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <section class="card-x h-100">
                <div class="card-x__head">
                    <h2 class="card-x__title">Recently Updated Records</h2>
                    <a href="faculty.php" class="card-x__link">View all</a>
                </div>
                <?php if (!$recent): ?>
                    <div class="empty">
                        <span class="empty__icon"><i class="bi bi-person-plus"></i></span>
                        <h3 class="empty__title">No faculty records yet</h3>
                        <p class="empty__text">Add the first faculty member to start the registry.</p>
                        <a href="faculty_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Faculty</a>
                    </div>
                <?php else: ?>
                    <ul class="list-rows">
                        <?php foreach ($recent as $member): ?>
                            <li>
                                <a class="list-row" href="faculty_view.php?id=<?= (int) $member['id'] ?>">
                                    <?= avatar($member) ?>
                                    <div class="list-row__main">
                                        <div class="list-row__title"><?= htmlspecialchars(full_name($member)) ?></div>
                                        <div class="list-row__meta"><?= htmlspecialchars($member['position']) ?> · <?= htmlspecialchars($member['department']) ?></div>
                                    </div>
                                    <span class="list-row__aside d-none d-sm-inline"><?= htmlspecialchars(format_date($member['updated_at'])) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        </div>

        <div class="col-lg-5">
            <section class="card-x h-100">
                <div class="card-x__head">
                    <h2 class="card-x__title">Faculty per Department</h2>
                </div>
                <div class="card-x__body">
                    <?php if (!$departments): ?>
                        <p class="text-muted mb-0">Departments appear here once records are added.</p>
                    <?php else: $max = max(array_column($departments, 'total')); ?>
                        <ul class="bar-list">
                            <?php foreach ($departments as $dept): ?>
                                <li>
                                    <div class="bar-list__top">
                                        <a class="bar-list__name text-reset text-decoration-none" href="faculty.php?q=<?= urlencode($dept['department']) ?>"><?= htmlspecialchars($dept['department']) ?></a>
                                        <span class="bar-list__value"><?= (int) $dept['total'] ?></span>
                                    </div>
                                    <div class="bar-list__track"><div class="bar-list__fill" style="width: <?= round($dept['total'] / $max * 100, 1) ?>%"></div></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
