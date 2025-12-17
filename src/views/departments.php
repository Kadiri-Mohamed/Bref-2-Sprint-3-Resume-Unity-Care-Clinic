<?php
require_once '../config/database.php';
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
            $message = 'Departement added with succes';
        } else {
            $error = 'Error on creation';
        }
    }

    if ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        if ($departmentModel->update($id, $data)) {
            $message = 'Departement modified with succes';
        } else {
            $error = 'Error on modification';
        }
    }
}

// DELETE
if (isset($_GET['delete'])) {
    if ($departmentModel->delete($_GET['delete'])) {
        $message = 'Departement deleted with succes';
    } else {
        $error = 'Error on deletion';
    }
}

$departments = $departmentModel->getAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Departements - Unity Care Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/style.css">

    <script>
        function editDepartment(department) {
            document.getElementById('departmentId').value = department.id;
            document.getElementById('nom').value = department.nom;
            document.getElementById('description').value = department.description ?? '';

            document.getElementById('action').value = 'edit';
            document.getElementById('departmentModalLabel').innerText = 'Modifier le Departement';
        }

        function resetDepartmentForm() {
            document.getElementById('departmentForm').reset();
            document.getElementById('departmentId').value = '';
            document.getElementById('action').value = 'add';
            document.getElementById('departmentModalLabel').innerText = 'Ajouter un Departement';
        }

    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../public/index.php">
                <i class="fas fa-hospital"></i> Unity Care Clinic
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../public/index.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patients.php">
                            <i class="fas fa-user-injured"></i> Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="departments.php">
                            <i class="fas fa-building"></i> Departments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="medecins.php">
                            <i class="fas fa-user-md"></i> Medecins
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="page-title">
            <i class="fas fa-building"></i> Gestion des Departements
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
            <i class="fas fa-plus"></i> Ajouter un Departement
        </button>


        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departments as $d): ?>
                                <tr>
                                    <td><span class="badge badge-primary"><?= $d['id'] ?></span></td>
                                    <td><?= htmlspecialchars($d['nom']) ?></td>
                                    <td><?= htmlspecialchars($d['description']) ?></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#departmentModal"
                                            onclick='editDepartment(<?= json_encode($d) ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <a href="?delete=<?= $d['id'] ?>" class="btn btn-sm btn-secondary"
                                            onclick="return confirm('Êtes-vous sûr ?')">
                                            <i class="fas fa-trash"></i>
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
                            Ajouter un Departement
                        </h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="departmentId">
                        <input type="hidden" name="action" id="action" value="add">

                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" id="nom" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="../utils/validation.js"></script> -->
</body>

</html>