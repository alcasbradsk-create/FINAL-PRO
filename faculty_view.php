<?php
// View Faculty: all details of one faculty record.
require_once __DIR__ . '/includes/auth.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$member = false;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM faculty WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $member = $stmt->fetch();
}
if (!$member) {
    http_response_code(404);
}

/** "12 years, 3 months" between a date and today. */
function length_of_time(?string $from): string
{
    $start = $from ? DateTimeImmutable::createFromFormat('!Y-m-d', $from) : false;
    if (!$start) {
        return '';
    }
    $diff = $start->diff(new DateTimeImmutable('today'));
    if ($diff->invert) {
        return 'Starts ' . $start->format('M j, Y');
    }
    $parts = [];
    if ($diff->y) $parts[] = $diff->y . ' year' . ($diff->y === 1 ? '' : 's');
    if ($diff->m) $parts[] = $diff->m . ' month' . ($diff->m === 1 ? '' : 's');
    return $parts ? implode(', ', $parts) : 'Less than a month';
}

$pageTitle = $member ? full_name($member) : 'Faculty not found';
$layout = 'app';
require_once __DIR__ . '/includes/header.php';
?>

<a href="faculty.php" class="back-link"><i class="bi bi-arrow-left"></i> Back to Faculty Management</a>

<?php if (!$member): ?>
    <div class="card-x mt-3">
        <div class="empty">
            <span class="empty__icon"><i class="bi bi-person-x"></i></span>
            <h1 class="empty__title">Faculty record not found</h1>
            <p class="empty__text">It may have been deleted, or the link is incorrect.</p>
            <a href="faculty.php" class="btn btn-secondary">Back to Faculty Management</a>
        </div>
    </div>
<?php else: ?>
    <section class="card-x mt-3 mb-3">
        <div class="profile-head">
            <?= avatar($member, 'avatar-lg') ?>
            <div class="min-w-0">
                <h1 class="profile-head__name"><?= htmlspecialchars(full_name($member)) ?></h1>
                <p class="profile-head__role"><?= htmlspecialchars($member['position']) ?> · <?= htmlspecialchars($member['department']) ?></p>
                <div class="d-flex flex-wrap gap-2">
                    <?= status_badge($member['status']) ?>
                    <span class="badge-soft no-dot"><?= htmlspecialchars($member['employment_status']) ?></span>
                    <span class="badge-soft no-dot tabular">Record #<?= (int) $member['id'] ?></span>
                </div>
            </div>
            <div class="profile-head__actions">
                <button type="button" class="btn btn-secondary text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= (int) $member['id'] ?>" data-name="<?= htmlspecialchars(full_name($member)) ?>" ><i class="bi bi-trash3"></i> Delete</button>
                <a href="faculty_form.php?id=<?= (int) $member['id'] ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </section>

    <div class="row g-3">
        <div class="col-lg-7">
            <section class="card-x h-100">
                <div class="card-x__head"><h2 class="card-x__title">Appointment</h2></div>
                <div class="card-x__body">
                    <dl class="details">
                        <div><dt>Position</dt><dd><?= htmlspecialchars($member['position']) ?></dd></div>
                        <div><dt>Department</dt><dd><?= htmlspecialchars($member['department']) ?></dd></div>
                        <div><dt>Specialization</dt><dd><?= htmlspecialchars($member['specialization'] ?: '—') ?></dd></div>
                        <div><dt>Employment</dt><dd><?= htmlspecialchars($member['employment_status']) ?></dd></div>
                        <div>
                            <dt>Date hired</dt>
                            <dd><?= htmlspecialchars(format_date($member['date_hired'], 'F j, Y')) ?><span class="sub"><?= htmlspecialchars(length_of_time($member['date_hired'])) ?> of service</span></dd>
                        </div>
                        <div><dt>Status</dt><dd><?= htmlspecialchars($member['status']) ?></dd></div>
                    </dl>
                </div>
            </section>
        </div>
        <div class="col-lg-5">
            <section class="card-x h-100">
                <div class="card-x__head"><h2 class="card-x__title">Personal &amp; contact</h2></div>
                <div class="card-x__body">
                    <dl class="details">
                        <div><dt>Email</dt><dd><a href="mailto:<?= htmlspecialchars($member['email']) ?>"><?= htmlspecialchars($member['email']) ?></a></dd></div>
                        <div><dt>Contact number</dt><dd><a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $member['contact_num'])) ?>"><?= htmlspecialchars($member['contact_num']) ?></a></dd></div>
                        <div><dt>Sex</dt><dd><?= htmlspecialchars($member['gender']) ?></dd></div>
                        <div><dt>Birthday</dt><dd><?= htmlspecialchars(format_date($member['birthday'], 'F j, Y')) ?></dd></div>
                    </dl>
                </div>
            </section>
        </div>
    </div>

    <p class="text-muted small mt-3 mb-0">
        Created <?= htmlspecialchars(format_date($member['created_at'])) ?>
        <?php if (!empty($member['updated_at']) && $member['updated_at'] !== $member['created_at']): ?>
            · Last updated <?= htmlspecialchars(format_date($member['updated_at'])) ?>
        <?php endif; ?>
    </p>

    <div class="mt-4">
        <a href="faculty.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
