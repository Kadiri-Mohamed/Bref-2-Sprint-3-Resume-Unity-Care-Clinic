<?php
require_once '../config/database.php';
require_once '../models/Patients.php';

$patientModel = new Patient();
$message = '';
$error = '';
$modalAction = '';
$modalPatient = null;

// Handle submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $data = [
            ':nom' => $_POST['nom'] ?? '',
            ':prenom' => $_POST['prenom'] ?? '',
            ':date_naissance' => $_POST['date_naissance'] ?? '',
            ':telephone' => $_POST['telephone'] ?? '',
            ':email' => $_POST['email'] ?? '',
            ':adresse' => $_POST['adresse'] ?? ''
        ];
        if ($patientModel->create($data)) {
            $message = 'Patient added with succes';
        } else {
            $error = 'Error on creation';
        }
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $data = [
            ':nom' => $_POST['nom'] ?? '',
            ':prenom' => $_POST['prenom'] ?? '',
            ':date_naissance' => $_POST['date_naissance'] ?? '',
            ':telephone' => $_POST['telephone'] ?? '',
            ':email' => $_POST['email'] ?? '',
            ':adresse' => $_POST['adresse'] ?? ''
        ];
        if ($patientModel->update($id, $data)) {
            $message = 'Patient modified with succes';
        } else {
            $error = 'Error on modification';
        }
    }
}

// delete
if (isset($_GET['delete'])) {
    if ($patientModel->delete($_GET['delete'])) {
        $message = 'Patient deleted with succes';
    } else {
        $error = 'Error on deletion';
    }
}

// Load patient for edit
if (isset($_GET['edit'])) {
    $modalAction = 'edit';
    $modalPatient = $patientModel->getById($_GET['edit']);
}

$patients = $patientModel->getAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Patients - Unity Care Clinic</title>
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
                        <a class="nav-link active" href="patients.php">
                            <i class="fas fa-user-injured"></i> Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="departments.php">
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
            <i class="fas fa-user-injured"></i> Gestion des Patients
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
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#patientModal">
                <i class="fas fa-plus"></i> Add Patient
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
                                <th>Date de Naissance</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>Adresse</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $p): ?>
                                <tr>
                                    <td><span class="badge badge-primary"><?php echo htmlspecialchars($p['id']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($p['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($p['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($p['date_naissance']); ?></td>
                                    <td><?php echo htmlspecialchars($p['telephone']); ?></td>
                                    <td><?php echo htmlspecialchars($p['email']); ?></td>
                                    <td><?php echo htmlspecialchars($p['adresse']); ?></td>
                                    <td class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#patientModal"
                                            onclick="editPatient(<?php echo htmlspecialchars(json_encode($p)); ?>)">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                        <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-secondary"
                                            onclick="return confirm('U sure?')">
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
    <div class="modal fade" id="patientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Ajouter un Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="patientForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="patientId">

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>

                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prenom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>

                        <div class="mb-3">
                            <label for="date_naissance" class="form-label">Date de Naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance">
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <textarea class="form-control" id="adresse" name="adresse" rows="3"></textarea>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const patientModal = document.getElementById('patientModal');

        patientModal.addEventListener('show.bs.modal', (e)=> {
            if (e.relatedTarget.textContent.includes('Add') ) {
                document.getElementById('patientForm').reset();
                document.getElementById('modalTitle').textContent = 'Add Patient';
                document.getElementById('formAction').value = 'add';
                document.getElementById('patientId').value = '';
            }
        });

        function editPatient(patient) {
            document.getElementById('modalTitle').textContent = 'Update Patient';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('patientId').value = patient.id;
            document.getElementById('nom').value = patient.nom;
            document.getElementById('prenom').value = patient.prenom;
            document.getElementById('date_naissance').value = patient.date_naissance;
            document.getElementById('telephone').value = patient.telephone;
            document.getElementById('email').value = patient.email;
            document.getElementById('adresse').value = patient.adresse;
        }
    </script>
</body>

</html>