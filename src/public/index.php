<?php
require_once '../config/database.php';
require_once '../config/Language.php';
require_once '../models/Medecins.php';
require_once '../models/Departments.php';
require_once '../models/Patients.php';

Session::requireLogin();

// instances
$patientModel = new Patient();
$departmentModel = new Department();
$medecinModel = new Medecin();

// counts
$totalPatients = $patientModel->count();
$totalDepartments = $departmentModel->count();
$totalMedecins = $medecinModel->count();

// recent patients
$recentPatients = $patientModel->getAll();
$recentPatients = array_slice($recentPatients, 0, 5);

// departments
$Departments = $departmentModel->getAll();
$recentDepartments = array_slice($Departments, 0, 5);

$departmentLabels = [];
$departmentMedecinsCount = [];

foreach ($Departments as $department) {
    $departmentLabels[] = $department['nom'];
    $departmentMedecinsCount[] = count(
        $medecinModel->getByDepartment($department['id'])
    );
}

// medecins
$recentMedecins = $medecinModel->getAll();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Care Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <?php if ($lang == 'ar'): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <?php endif; ?>
</head>

<body>
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <i class="fas fa-hospital"></i> Unity Care Clinic
                </a>

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

                        <?php if (Session::isLoggedIn()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i>
                                    <?= htmlspecialchars(Session::get('user_username')) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../auth/logout.php">
                                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                                        </a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="main">

        <div class="container mt-4">
            <h1 class="mb-4">
                <i class="fas fa-chart-line"></i> <?= $t('dashboard_title') ?>
            </h1>

            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card card-patients">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5><?= $t('total_patients') ?></h5>
                                <h2 class="fw-bold"><?php echo $totalPatients; ?></h2>
                                <p class="mb-0">
                                    <a href="../views/patients.php" class="text-white text-decoration-none">
                                        <?= $t('view_all') ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                </p>
                            </div>
                            <div>
                                <i class="fas fa-user-injured"></i>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Medecins -->
                <div class="col-md-4">
                    <div class="stat-card card-medecins">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5><?= $t('total_medecins') ?></h5>
                                <h2 class="fw-bold"><?php echo $totalMedecins; ?></h2>
                                <p class="mb-0">
                                    <a href="../views/medecins.php" class="text-white text-decoration-none">
                                        <?= $t('view_all') ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                </p>
                            </div>
                            <div>
                                <i class="fas fa-user-md"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Departements -->
                <div class="col-md-4">
                    <div class="stat-card card-departments">
                        <h5><?= $t('total_departments') ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patients -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-clock"></i> <?= $t('recent_patients') ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><?= $t('full_name') ?></th>
                                            <th><?= $t('birth_date') ?></th>
                                            <th><?= $t('phone') ?></th>
                                            <th><?= $t('email') ?></th>
                                            <th><?= $t('admission_date') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($recentPatients)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    <?= $t('no_patients') ?>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($recentPatients as $patient): ?>
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-user-circle text-primary"></i>
                                                        <?php echo htmlspecialchars($patient['nom'] . ' ' . $patient['prenom']); ?>
                                                    </td>
                                                    <td><?php echo date('d/m/Y', strtotime($patient['date_naissance'])); ?></td>
                                                    <td><?php echo htmlspecialchars($patient['telephone']); ?></td>
                                                    <td><?php echo htmlspecialchars($patient['email']); ?></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($patient['created_at'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-6">
                    <!-- deparetemenets -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-clock"></i> <?= $t('recent_departments') ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><?= $t('name') ?></th>
                                            <th><?= $t('doctors_count') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($recentDepartments)): ?>
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">
                                                    <?= $t('no_departments') ?>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($recentDepartments as $departement): ?>
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-user-circle text-primary"></i>
                                                        <?php echo htmlspecialchars($departement['nom']); ?>
                                                    </td>
                                                    <td><?php echo count($medecinModel->getByDepartment($departement['id'])) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <!-- medecins -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-clock"></i> <?= $t('recent_medecins') ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><?= $t('name') ?></th>
                                            <th><?= $t('department') ?></th>
                                            <th><?= $t('speciality') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($recentMedecins)): ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    <?= $t('no_medecins') ?>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($recentMedecins as $medecin): ?>
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-user-circle text-primary"></i>
                                                        <?php echo htmlspecialchars($medecin['nom']); ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($medecin['department_nom']); ?></td>
                                                    <td><?php echo htmlspecialchars($medecin['specialite']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($departmentLabels); ?>,
                datasets: [{
                    label: '<?= $t("doctors_count") ?>',
                    data: <?php echo json_encode($departmentMedecinsCount); ?>,
                    backgroundColor: 'rgba(255, 255, 255, 1)',
                    borderColor: 'rgba(212, 212, 212, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    },
                    tooltip: {
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff'
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#ffffff',
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        })

        function changeLanguage(lang) {
            window.location.href = '?lang=' + lang;
        }
    </script>
</body>

</html>