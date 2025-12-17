<?php
require_once '../config/database.php';
require_once '../config/Language.php';
require_once '../models/Medecins.php';
require_once '../models/Departments.php';

$medecinModel = new Medecin();
$departmentModel = new Department();
$message = '';
$error = '';

// Handle submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $data = [
            ':nom' => $_POST['nom'] ?? '',
            ':prenom' => $_POST['prenom'] ?? '',
            ':specialite' => $_POST['specialite'] ?? '',
            ':department_id' => $_POST['department_id'] ?? '',
            ':email' => $_POST['email'] ?? '',
            ':telephone' => $_POST['telephone'] ?? ''
        ];
        if ($medecinModel->create($data)) {
            $message = 'Médecin ' . $t('added_success');
        } else {
            $error = $t('error_creation');
        }
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $data = [
            ':nom' => $_POST['nom'] ?? '',
            ':prenom' => $_POST['prenom'] ?? '',
            ':specialite' => $_POST['specialite'] ?? '',
            ':department_id' => $_POST['department_id'] ?? '',
            ':email' => $_POST['email'] ?? '',
            ':telephone' => $_POST['telephone'] ?? ''
        ];
        if ($medecinModel->update($id, $data)) {
            $message = 'Médecin ' . $t('modified_success');
        } else {
            $error = $t('error_modification');
        }
    }
}

// delete
if (isset($_GET['delete'])) {
    if ($medecinModel->delete($_GET['delete'])) {
        $message = 'Médecin ' . $t('deleted_success');
    } else {
        $error = $t('error_deletion');
    }
}

$medecins = $medecinModel->getAll();
$departments = $departmentModel->getAll();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang == 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Care Clinic - <?= $t('medecins_title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/style.css">
    
    <?php if ($lang == 'ar'): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <?php endif; ?>
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
                        <a class="nav-link" href="departments.php">
                            <i class="fas fa-building"></i> <?= $t('departments') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="medecins.php">
                            <i class="fas fa-user-md"></i> <?= $t('medecins') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="page-title">
            <i class="fas fa-user-md"></i> <?= $t('gestion_medecins') ?>
        </h1>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#medecinModal">
                <i class="fas fa-plus"></i> <?= $t('add') ?> <?= $t('medecins') ?>
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $t('id') ?></th>
                                <th><?= $t('name') ?></th>
                                <th><?= $t('first_name') ?></th>
                                <th><?= $t('speciality') ?></th>
                                <th><?= $t('department') ?></th>
                                <th><?= $t('email') ?></th>
                                <th><?= $t('phone') ?></th>
                                <th><?= $t('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medecins as $m): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($m['id']) ?></span></td>
                                    <td><?= htmlspecialchars($m['nom']) ?></td>
                                    <td><?= htmlspecialchars($m['prenom']) ?></td>
                                    <td><?= htmlspecialchars($m['specialite']) ?></td>
                                    <td><?= htmlspecialchars($m['department_nom'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($m['email']) ?></td>
                                    <td><?= htmlspecialchars($m['telephone']) ?></td>
                                    <td class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#medecinModal" 
                                            onclick="editMedecin(<?= htmlspecialchars(json_encode($m)) ?>)">
                                            <i class="fas fa-edit"></i> <?= $t('edit') ?>
                                        </button>
                                        <a href="?delete=<?= $m['id'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('<?= $t('confirm_delete_medecin') ?>')">
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
    </div>

    <!-- Modal -->
    <div class="modal fade" id="medecinModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><?= $t('add_medecin') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="medecinForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="medecinId">

                        <div class="mb-3">
                            <label for="nom" class="form-label"><?= $t('name') ?> *</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>

                        <div class="mb-3">
                            <label for="prenom" class="form-label"><?= $t('first_name') ?> *</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>

                        <div class="mb-3">
                            <label for="specialite" class="form-label"><?= $t('speciality') ?> *</label>
                            <input type="text" class="form-control" id="specialite" name="specialite" required>
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label"><?= $t('department') ?></label>
                            <select class="form-select" id="department_id" name="department_id">
                                <option value=""><?= $t('select_department') ?></option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= htmlspecialchars($dept['id']) ?>">
                                        <?= htmlspecialchars($dept['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label"><?= $t('email') ?></label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label"><?= $t('phone') ?></label>
                            <input type="tel" class="form-control" id="telephone" name="telephone">
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
        
        const medecinModal = document.getElementById('medecinModal');
        
        medecinModal.addEventListener('show.bs.modal', function(e) {
            if (e.relatedTarget && e.relatedTarget.textContent.includes('<?= $t('add') ?>')) {
                document.getElementById('medecinForm').reset();
                document.getElementById('modalTitle').textContent = '<?= $t('add_medecin') ?>';
                document.getElementById('formAction').value = 'add';
                document.getElementById('medecinId').value = '';
            }
        });

        function editMedecin(medecin) {
            document.getElementById('modalTitle').textContent = '<?= $t('edit_medecin') ?>';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('medecinId').value = medecin.id;
            document.getElementById('nom').value = medecin.nom;
            document.getElementById('prenom').value = medecin.prenom;
            document.getElementById('specialite').value = medecin.specialite;
            document.getElementById('department_id').value = medecin.department_id;
            document.getElementById('email').value = medecin.email;
            document.getElementById('telephone').value = medecin.telephone;
        }
    </script>
</body>
</html>