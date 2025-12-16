<?php
require_once '../config/database.php';
require_once '../models/Medecins.php';
require_once '../models/Departments.php';

$medecinModel = new Medecin();
$departmentModel = new Department();
$message = '';
$error = '';
$modalAction = '';
$modalMedecin = null;

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
            $message = 'Medecin added with success';
        } else {
            $error = 'Error on creation';
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
            $message = 'Medecin modified with success';
        } else {
            $error = 'Error on modification';
        }
    }
}

//  delete
if (isset($_GET['delete'])) {
    if ($medecinModel->delete($_GET['delete'])) {
        $message = 'Medecin deleted with success';
    } else {
        $error = 'Error on deletion';
    }
}

// Load medecin for editing
if (isset($_GET['edit'])) {
    $modalAction = 'edit';
    $modalMedecin = $medecinModel->getById($_GET['edit']);
}

$medecins = $medecinModel->getAll();
$departments = $departmentModel->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Medecins - Unity Care Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/style.css">
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
                        <a class="nav-link" href="departments.php">
                            <i class="fas fa-building"></i> Departments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="medecins.php">
                            <i class="fas fa-user-md"></i> Medecins
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="page-title">
            <i class="fas fa-user-md"></i> Gestion des Medecins
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
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#medecinModal">
                <i class="fas fa-plus"></i> Add Medecin
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
                                    <th>Prenom</th>
                                    <th>Specialite</th>
                                    <th>Departement</th>
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medecins as $m): ?>
                                    <tr>
                                        <td><span class="badge badge-primary"><?php echo htmlspecialchars($m['id']); ?></span></td>
                                        <td><?php echo htmlspecialchars($m['nom']); ?></td>
                                        <td><?php echo htmlspecialchars($m['prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($m['specialite']); ?></td>
                                        <td><?php echo htmlspecialchars($m['department_nom'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($m['email']); ?></td>
                                        <td><?php echo htmlspecialchars($m['telephone']); ?></td>
                                        <td class="action-buttons">
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#medecinModal" onclick="editMedecin(<?php echo htmlspecialchars(json_encode($m)); ?>)">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                            <a href="?delete=<?php echo $m['id']; ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Êtes-vous sûr?')">
                                                <i class="fas fa-trash"></i> Delete
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
                    <h5 class="modal-title" id="modalTitle">Add un Medecin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="medecinForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="medecinId">

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>

                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prenom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>

                        <div class="mb-3">
                            <label for="specialite" class="form-label">Specialite</label>
                            <input type="text" class="form-control" id="specialite" name="specialite" required>
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Departement</label>
                            <select class="form-select" id="department_id" name="department_id">
                                <option value="">Selectionner un departement</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo htmlspecialchars($dept['id']); ?>">
                                        <?php echo htmlspecialchars($dept['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const medecinModal = document.getElementById('medecinModal');
        
        medecinModal.addEventListener('show.bs.modal', function(e) {
            if (e.relatedTarget.textContent.includes('Add')) {
                document.getElementById('medecinForm').reset();
                document.getElementById('modalTitle').textContent = 'Add un Medecin';
                document.getElementById('formAction').value = 'add';
                document.getElementById('medecinId').value = '';
            }
        });

        function editMedecin(medecin) {
            document.getElementById('modalTitle').textContent = 'Update Medecin';
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
