<?php
require_once '../config/database.php';
require_once '../config/Language.php';
require_once '../models/Departments.php';

$departmentModel = new Department();
$message = '';
$error = '';

// ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';

    $data = [
        ':nom' => $_POST['nom'] ?? '',
        ':description' => $_POST['description'] ?? ''
    ];

    if ($action === 'add') {
        if ($departmentModel->create($data)) {
            $message = 'Département ' . $t('added_success');
        } else {
            $error = $t('error_creation');
        }
    }

    if ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        if ($departmentModel->update($id, $data)) {
            $message = 'Département ' . $t('modified_success');
        } else {
            $error = $t('error_modification');
        }
    }
}

// DELETE
if (isset($_GET['delete'])) {
    if ($departmentModel->delete($_GET['delete'])) {
        $message = 'Département ' . $t('deleted_success');
    } else {
        $error = $t('error_deletion');
    }
}

$departments = $departmentModel->getAll();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang == 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Care Clinic - <?= $t('departments_title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/style.css">
    
    <?php if ($lang == 'ar'): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <?php endif; ?>

    <script>
        function editDepartment(department) {
            document.getElementById('departmentId').value = department.id;
            document.getElementById('nom').value = department.nom;
            document.getElementById('description').value = department.description ?? '';
            document.getElementById('action').value = 'edit';
            document.getElementById('departmentModalLabel').innerText = '<?= $t('edit_department') ?>';
        }

        function resetDepartmentForm() {
            document.getElementById('departmentForm').reset();
            document.getElementById('departmentId').value = '';
            document.getElementById('action').value = 'add';
            document.getElementById('departmentModalLabel').innerText = '<?= $t('add_department') ?>';
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../public/index.php">
                <i class="fas fa-hospital"></i> Unity Care Clinic
            </a>
            
            <!-- Sélecteur de langue -->
            <div class="language-selector me-3">
                <select class="form-select form-select-sm" onchange="changeLanguage(this.value)">
                    <option value="fr" <?= $lang == 'fr' ? 'selected' : '' ?>><?= $t('french') ?></option>
                    <option value="en" <?= $lang == 'en' ? 'selected' : '' ?>><?= $t('english') ?></option>
                    <option value="ar" <?= $lang == 'ar' ? 'selected' : '' ?>><?= $t('arabic') ?></option>
                </select>
            </div>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../public/index.php">
                            <i class="fas fa-home"></i> <?= $t('dashboard') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patients.php">
                            <i class="fas fa-user-injured"></i> <?= $t('patients') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="departments.php">
                            <i class="fas fa-building"></i> <?= $t('departments') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="medecins.php">
                            <i class="fas fa-user-md"></i> <?= $t('medecins') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="page-title">
            <i class="fas fa-building"></i> <?= $t('gestion_departements') ?>
        </h1>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> <?= $message ?>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#departmentModal"
            onclick="resetDepartmentForm()">
            <i class="fas fa-plus"></i> <?= $t('add') ?> <?= $t('department') ?>
        </button>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $t('id') ?></th>
                                <th><?= $t('name') ?></th>
                                <th><?= $t('description') ?></th>
                                <th><?= $t('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departments as $d): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= $d['id'] ?></span></td>
                                    <td><?= htmlspecialchars($d['nom']) ?></td>
                                    <td><?= htmlspecialchars($d['description']) ?></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#departmentModal"
                                            onclick='editDepartment(<?= json_encode($d) ?>)'>
                                            <i class="fas fa-edit"></i> <?= $t('edit') ?>
                                        </button>
                                        <a href="?delete=<?= $d['id'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('<?= $t('confirm_delete_department') ?>')">
                                            <i class="fas fa-trash"></i> <?= $t('delete') ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="departmentModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" id="departmentForm" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="departmentModalLabel">
                            <?= $t('add_department') ?>
                        </h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="departmentId">
                        <input type="hidden" name="action" id="action" value="add">

                        <div class="mb-3">
                            <label class="form-label"><?= $t('name') ?> *</label>
                            <input type="text" name="nom" id="nom" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?= $t('description') ?></label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $t('cancel') ?></button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?= $t('save') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeLanguage(lang) {
            window.location.href = '?lang=' + lang;
        }
    </script>
</body>
</html>