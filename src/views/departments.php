<?php
require_once '../config/database.php';
require_once '../models/Departments.php';

$departmentModel = new Department();
$message = '';
$error = '';
$modalAction = '';
$modalDepartment = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $data = [
            ':nom' => $_POST['nom'] ?? '',
            ':description' => $_POST['description'] ?? ''
        ];
        if ($departmentModel->create($data)) {
            $message = 'Departement ajoute avec succes';
        } else {
            $error = 'Erreur lors de l\'ajout du departement';
        }
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $data = [
            ':nom' => $_POST['nom'] ?? '',
            ':description' => $_POST['description'] ?? ''
        ];
        if ($departmentModel->update($id, $data)) {
            $message = 'Departement modifie avec succes';
        } else {
            $error = 'Erreur lors de la modification';
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    if ($departmentModel->delete($_GET['delete'])) {
        $message = 'Departement supprime avec succes';
    } else {
        $error = 'Erreur lors de la suppression';
    }
}

// Load department for editing
if (isset($_GET['edit'])) {
    $modalAction = 'edit';
    $modalDepartment = $departmentModel->getById($_GET['edit']);
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
    <style>
        :root {
            --primary: #134686;
            --secondary: #ED3F27;
            --accent: #FEB21A;
            --light: #FDF4E3;
        }
        body {
            background-color: var(--light);
            color: var(--primary);
        }
        .navbar {
            background-color: var(--primary) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: #0d2652;
            border-color: #0d2652;
        }
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        .btn-secondary:hover {
            background-color: #c82f1f;
            border-color: #c82f1f;
        }
        .btn-warning {
            background-color: var(--accent);
            border-color: var(--accent);
            color: var(--primary);
        }
        .btn-warning:hover {
            background-color: #ffa500;
            border-color: #ffa500;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary);
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(19, 70, 134, 0.05);
        }
        .table thead {
            background-color: var(--primary);
            color: white;
        }
        .alert-success {
            background-color: rgba(254, 178, 26, 0.2);
            border-color: var(--accent);
            color: var(--primary);
        }
        .alert-danger {
            background-color: rgba(237, 63, 39, 0.2);
            border-color: var(--secondary);
            color: var(--secondary);
        }
        .form-control, .form-select {
            border-color: var(--primary);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(254, 178, 26, 0.25);
        }
        .page-title {
            color: var(--primary);
            border-bottom: 3px solid var(--accent);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .badge-primary {
            background-color: var(--primary);
        }
        .action-buttons a, .action-buttons button {
            margin: 0 2px;
        }
        .form-label {
            color: var(--primary);
            font-weight: 500;
        }
        .modal-header {
            background-color: var(--primary);
            color: white;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>

    <script>
        function editDepartment(department) {
            document.getElementById('departmentId').value = department.id;
            document.getElementById('nom').value = department.nom;
            document.getElementById('description').value = department.description;
            document.getElementById('departmentModalLabel').innerText = 'Modifier le Departement';
            document.getElementById('departmentForm').dataset.action = 'edit';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const departmentModal = document.getElementById('departmentModal');
            if (departmentModal) {
                departmentModal.addEventListener('hide.bs.modal', function () {
                    document.getElementById('departmentForm').reset();
                    document.getElementById('departmentId').value = '';
                    document.getElementById('departmentModalLabel').innerText = 'Ajouter un Nouveau Departement';
                    document.getElementById('departmentForm').dataset.action = 'add';
                });
            }

            const departmentForm = document.getElementById('departmentForm');
            if (departmentForm) {
                departmentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const action = this.dataset.action || 'add';
                    const formData = new FormData(this);
                    formData.set('action', action);

                    fetch('?', {
                        method: 'POST',
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            location.reload();
                        }
                    });
                });
            }
        });
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
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#departmentModal">
                <i class="fas fa-plus"></i> Ajouter un Departement
            </button>
        </div>

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
                                    <td><span class="badge badge-primary"><?php echo htmlspecialchars($d['id']); ?></span></td>
                                    <td><?php echo htmlspecialchars($d['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($d['description']); ?></td>
                                    <td class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#departmentModal" onclick="editDepartment(<?php echo htmlspecialchars(json_encode($d)); ?>)">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                        <a href="?delete=<?php echo $d['id']; ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Êtes-vous sûr?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal de formulaire -->
        <div class="modal fade" id="departmentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="departmentModalLabel">Ajouter un Nouveau Departement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="departmentForm" data-action="add" method="POST">
                        <div class="modal-body">
                            <input type="hidden" id="departmentId" name="id">
                            
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom du Departement</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
