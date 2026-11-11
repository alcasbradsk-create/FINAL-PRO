<?php
// Add Faculty / Edit Faculty form (one page for both).
require_once __DIR__ . '/includes/auth.php';

const STATUS_OPTIONS     = ['Active', 'Inactive'];
const GENDER_OPTIONS     = ['Male', 'Female', 'Other'];
const EMPLOYMENT_OPTIONS = ['Permanent', 'Temporary', 'Contractual', 'Part-time'];

/** Maximum lengths, matching the faculty table columns. */
const FIELD_LIMITS = [
    'firstname' => 100, 'middlename' => 100, 'lastname' => 100, 'status' => 50,
    'position' => 100, 'department' => 100, 'contact_num' => 30, 'gender' => 20,
    'email' => 100, 'employment_status' => 50, 'specialization' => 150,
    'date_hired' => 10, 'birthday' => 10,
];

const FIELD_LABELS = [
    'firstname' => 'First name', 'middlename' => 'Middle name', 'lastname' => 'Last name',
    'status' => 'Status', 'position' => 'Position', 'department' => 'Department',
    'contact_num' => 'Contact number', 'gender' => 'Sex', 'email' => 'Email',
    'employment_status' => 'Employment status', 'specialization' => 'Specialization',
    'date_hired' => 'Date hired', 'birthday' => 'Birthday',
];

/** Parse a strict YYYY-MM-DD date, or return null. */
function parse_date(string $value): ?DateTimeImmutable
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    return ($date && $date->format('Y-m-d') === $value) ? $date : null;
}

$fields = array_keys(FIELD_LIMITS);
$optionalFields = ['middlename'];

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT) ?: null;
$values = array_fill_keys($fields, '');
$values['status'] = 'Active';
$values['employment_status'] = 'Permanent';
$errors = [];      // problems not tied to one field
$fieldErrors = []; // field name => message
$original = null;

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM faculty WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $original = $stmt->fetch();
    if (!$original) {
        redirect('faculty.php?msg=notfound');
    }
    foreach ($fields as $field) {
        $values[$field] = (string) ($original[$field] ?? '');
    }
}

// Records created before the option lists existed may hold other values; keep them selectable.
$employmentOptions = EMPLOYMENT_OPTIONS;
if ($original && $original['employment_status'] !== '' && !in_array($original['employment_status'], $employmentOptions, true)) {
    $employmentOptions[] = $original['employment_status'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $field) {
        $values[$field] = trim((string) ($_POST[$field] ?? ''));
    }

    if (!csrf_valid()) {
        $errors[] = 'Your session expired. Please submit the form again.';
    }

    foreach ($fields as $field) {
        if ($values[$field] === '' && !in_array($field, $optionalFields, true)) {
            $fieldErrors[$field] ??= FIELD_LABELS[$field] . ' is required.';
        } elseif (mb_strlen($values[$field]) > FIELD_LIMITS[$field]) {
            $fieldErrors[$field] ??= FIELD_LABELS[$field] . ' must be ' . FIELD_LIMITS[$field] . ' characters or fewer.';
        }
    }

    if ($values['status'] !== '' && !in_array($values['status'], STATUS_OPTIONS, true)) {
        $fieldErrors['status'] ??= 'Please choose a valid status.';
    }
    if ($values['gender'] !== '' && !in_array($values['gender'], GENDER_OPTIONS, true)) {
        $fieldErrors['gender'] ??= 'Please choose a valid option for sex.';
    }
    if ($values['employment_status'] !== '' && !in_array($values['employment_status'], $employmentOptions, true)) {
        $fieldErrors['employment_status'] ??= 'Please choose a valid employment status.';
    }
    if ($values['email'] !== '' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] ??= 'Please provide a valid email address.';
    }
    if ($values['contact_num'] !== '' && !preg_match('/^\+?[0-9][0-9\s()-]{6,28}$/', $values['contact_num'])) {
        $fieldErrors['contact_num'] ??= 'Contact number may only contain digits, spaces, dashes, brackets and a leading +.';
    }

    $today = new DateTimeImmutable('today');
    $birthday = $values['birthday'] !== '' ? parse_date($values['birthday']) : null;
    $hired = $values['date_hired'] !== '' ? parse_date($values['date_hired']) : null;
    if ($values['birthday'] !== '' && (!$birthday || $birthday->format('Y') < 1900 || $birthday >= $today)) {
        $fieldErrors['birthday'] ??= 'Please enter a valid birthday in the past.';
    }
    if ($values['date_hired'] !== '' && (!$hired || $hired->format('Y') < 1900)) {
        $fieldErrors['date_hired'] ??= 'Please enter a valid hiring date.';
    }
    if ($birthday && $hired && $hired <= $birthday) {
        $fieldErrors['date_hired'] ??= 'Date hired must be after the birthday.';
    }

    if (!$errors && !$fieldErrors) {
        try {
            $params = array_intersect_key($values, array_flip($fields));
            $params['middlename'] = $params['middlename'] === '' ? null : $params['middlename'];
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
            redirect('faculty.php?msg=' . ($id ? 'updated' : 'added'));
        } catch (PDOException $e) {
            error_log('[faculty-system] faculty save failed: ' . $e->getMessage());
            $errors[] = 'Unable to save the faculty record. Please try again.';
        }
    }
}

/** Render <option> tags, marking the current value as selected. */
function options(array $choices, string $current): string
{
    $html = '';
    foreach ($choices as $choice) {
        $selected = $choice === $current ? ' selected' : '';
        $html .= '<option value="' . htmlspecialchars($choice) . '"' . $selected . '>' . htmlspecialchars($choice) . '</option>';
    }
    return $html;
}

$pageTitle = $id ? 'Edit Faculty' : 'Add Faculty';
$layout = 'app';
require_once __DIR__ . '/includes/header.php';

/** Validation message under a field, if it has one. */
function feedback(string $name): string
{
    global $fieldErrors;
    return isset($fieldErrors[$name])
        ? '<div class="invalid-feedback" id="' . $name . '-error">' . htmlspecialchars($fieldErrors[$name]) . '</div>'
        : '';
}

/** Attributes marking a field invalid for styling and screen readers. */
function invalid_attrs(string $name): string
{
    global $fieldErrors;
    return isset($fieldErrors[$name]) ? ' aria-invalid="true" aria-describedby="' . $name . '-error"' : '';
}

/** Text input with label and inline error, keeping the submitted value. */
function field(string $name, string $label, array $values, array $attrs = []): string
{
    global $fieldErrors;
    $required = !in_array($name, ['middlename'], true);
    $attrs += ['type' => 'text', 'maxlength' => FIELD_LIMITS[$name] ?? null];
    $html = '<label class="form-label" for="' . $name . '">' . htmlspecialchars($label)
        . ($required ? '' : ' <span class="optional">Optional</span>') . '</label>'
        . '<input class="form-control' . (isset($fieldErrors[$name]) ? ' is-invalid' : '') . '" id="' . $name . '" name="' . $name . '" value="' . htmlspecialchars($values[$name]) . '"';
    foreach ($attrs as $key => $value) {
        if ($value !== null) {
            $html .= ' ' . $key . '="' . htmlspecialchars((string) $value) . '"';
        }
    }
    return $html . ($required ? ' required' : '') . invalid_attrs($name) . '>' . feedback($name);
}

/** Select with label and inline error. */
function select_field(string $name, string $label, string $optionsHtml): string
{
    global $fieldErrors;
    return '<label class="form-label" for="' . $name . '">' . htmlspecialchars($label) . '</label>'
        . '<select class="form-select' . (isset($fieldErrors[$name]) ? ' is-invalid' : '') . '" id="' . $name . '" name="' . $name . '" required' . invalid_attrs($name) . '>'
        . $optionsHtml . '</select>' . feedback($name);
}
$backUrl = $id ? 'faculty_view.php?id=' . $id : 'faculty.php';
?>

<a href="<?= $backUrl ?>" class="back-link"><i class="bi bi-arrow-left"></i> <?= $id ? 'Back to Profile' : 'Back to Faculty Management' ?></a>
<div class="page-header mt-2">
    <div>
        <h1 class="page-header__title"><?= $id ? 'Edit Faculty' : 'Add Faculty' ?></h1>
        <p class="page-header__lead"><?= $id ? 'Editing the record of ' . htmlspecialchars(full_name($original)) . '.' : 'Enter the faculty member\'s information.' ?></p>
    </div>
</div>

<?php if ($errors || $fieldErrors): ?>
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-circle"></i>
        <div>
            <strong>The record was not saved.</strong>
            <?php if ($fieldErrors): ?>
                Please correct the <?= count($fieldErrors) === 1 ? 'highlighted field' : count($fieldErrors) . ' highlighted fields' ?> below.
            <?php endif; ?>
            <?php if ($errors): ?>
                <ul class="mt-1"><?php foreach (array_unique($errors) as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?></ul>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<form method="post" class="card-x" novalidate>
    <?= csrf_field() ?>

    <div class="form-section">
        <div>
            <h2 class="form-section__title">Personal details</h2>
            <p class="form-section__text">Name as it appears on official college records.</p>
        </div>
        <div class="row g-3">
            <div class="col-md-4"><?= field('firstname', 'First name', $values, ['autocomplete' => 'off']) ?></div>
            <div class="col-md-4"><?= field('middlename', 'Middle name', $values, ['autocomplete' => 'off']) ?></div>
            <div class="col-md-4"><?= field('lastname', 'Last name', $values, ['autocomplete' => 'off']) ?></div>
            <div class="col-md-4"><?= select_field('gender', 'Sex', '<option value="">Select…</option>' . options(GENDER_OPTIONS, $values['gender'])) ?></div>
            <div class="col-md-4"><?= field('birthday', 'Birthday', $values, ['type' => 'date', 'max' => date('Y-m-d', strtotime('yesterday')), 'maxlength' => null]) ?></div>
        </div>
    </div>

    <div class="form-section">
        <div>
            <h2 class="form-section__title">Appointment</h2>
            <p class="form-section__text">Current position, assignment and employment terms.</p>
        </div>
        <div class="row g-3">
            <div class="col-md-6"><?= field('position', 'Position', $values, ['placeholder' => 'e.g. Instructor I']) ?></div>
            <div class="col-md-6"><?= field('department', 'Department', $values, ['placeholder' => 'e.g. Information Technology']) ?></div>
            <div class="col-12"><?= field('specialization', 'Specialization', $values, ['placeholder' => 'e.g. Database Systems']) ?></div>
            <div class="col-md-4"><?= select_field('status', 'Status', options(STATUS_OPTIONS, $values['status'])) ?></div>
            <div class="col-md-4"><?= select_field('employment_status', 'Employment', options($employmentOptions, $values['employment_status'])) ?></div>
            <div class="col-md-4"><?= field('date_hired', 'Date hired', $values, ['type' => 'date', 'maxlength' => null]) ?></div>
        </div>
    </div>

    <div class="form-section">
        <div>
            <h2 class="form-section__title">Contact</h2>
            <p class="form-section__text">How the college reaches this faculty member.</p>
        </div>
        <div class="row g-3">
            <div class="col-md-7"><?= field('email', 'Email', $values, ['type' => 'email', 'autocomplete' => 'off']) ?></div>
            <div class="col-md-5"><?= field('contact_num', 'Contact number', $values, ['type' => 'tel', 'placeholder' => '+63 912 345 6789']) ?></div>
        </div>
    </div>

    <div class="form-footer">
        <a href="<?= $backUrl ?>" class="btn btn-secondary">Cancel</a>
        <button class="btn btn-primary"><i class="bi bi-check2"></i> Save Faculty</button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
