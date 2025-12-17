<?php
require_once '../config/database.php';
require_once '../config/Language.php';

// Instancier les modèles
require_once '../models/Patients.php';
require_once '../models/Departments.php';
require_once '../models/Medecins.php';

$patientModel = new Patient();
$departmentModel = new Department();
$medecinModel = new Medecin();

// Compter les éléments
$totalPatients = $patientModel->count();
$totalDepartments = $departmentModel->count();
$totalMedecins = $medecinModel->count();

// Récents patients (5 derniers)
$recentPatients = $patientModel->getAll();
$recentPatients = array_slice($recentPatients, 0, 5);
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang == 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Care Clinic - <?= $t('dashboard_title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    
    <?php if ($lang == 'ar'): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <?php endif; ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
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
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home"></i> <?= $t('dashboard') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../views/patients.php">
                            <i class="fas fa-user-injured"></i> <?= $t('patients') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../views/departments.php">
                            <i class="fas fa-building"></i> <?= $t('departments') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../views/medecins.php">
                            <i class="fas fa-user-md"></i> <?= $t('medecins') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">
            <i class="fas fa-tachometer-alt"></i> <?= $t('dashboard_title') ?>
        </h1>
        
        <!-- Statistiques -->
        <div class="row">
            <!-- Patients -->
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5><?= $t('total_patients') ?></h5>
                                <h2><?= $totalPatients ?></h2>
                                <p class="mb-0">
                                    <a href="../views/patients.php" class="text-white text-decoration-none">
                                        <?= $t('view_all') ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                </p>
                            </div>
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Médecins -->
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5><?= $t('total_medecins') ?></h5>
                                <h2><?= $totalMedecins ?></h2>
                                <p class="mb-0">
                                    <a href="../views/medecins.php" class="text-white text-decoration-none">
                                        <?= $t('view_all') ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                </p>
                            </div>
                            <i class="fas fa-user-md fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Départements -->
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5><?= $t('total_departments') ?></h5>
                                <h2><?= $totalDepartments ?></h2>
                                <p class="mb-0">
                                    <a href="../views/departments.php" class="text-dark text-decoration-none">
                                        <?= $t('view_all') ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                </p>
                            </div>
                            <i class="fas fa-hospital fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Patients récents -->
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-history"></i> <?= $t('recent_patients') ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $t('id') ?></th>
                                <th><?= $t('name') ?></th>
                                <th><?= $t('first_name') ?></th>
                                <th><?= $t('email') ?></th>
                                <th><?= $t('phone') ?></th>
                                <th><?= $t('admission_date') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentPatients)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Aucun patient enregistré
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($recentPatients as $patient): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= $patient['id'] ?></span></td>
                                    <td><?= htmlspecialchars($patient['nom']) ?></td>
                                    <td><?= htmlspecialchars($patient['prenom']) ?></td>
                                    <td><?= htmlspecialchars($patient['email']) ?></td>
                                    <td><?= htmlspecialchars($patient['telephone']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($patient['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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