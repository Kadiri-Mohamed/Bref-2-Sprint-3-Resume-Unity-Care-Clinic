<?php
require_once '../config/database.php';
require_once '../models/Medecins.php';
require_once '../models/Departments.php';
require_once '../models/Patients.php';

// Create instances
$patientModel = new Patient();
$departmentModel = new Department();
$medecinModel = new Medecin();

// Get statistics
$totalPatients = $patientModel->count();
$totalDepartments = $departmentModel->count();
$totalMedecins = $medecinModel->count();

// Get recent patients
$recentPatients = $patientModel->getAll();
$recentPatients = array_slice($recentPatients, 0, 5);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Care Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card i {
            font-size: 3rem;
            opacity: 0.8;
        }
        .card-patients { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-departments { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .card-medecins { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-hospital"></i> Unity Care Clinic
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
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
                            <i class="fas fa-building"></i> Départements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="medecins.php">
                            <i class="fas fa-user-md"></i> Médecins
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">
            <i class="fas fa-chart-line"></i> Tableau de Bord
        </h1>

        <!-- Cartes de Statistiques -->
        <div class="row">
            <!-- Carte Patients -->
            <div class="col-md-4">
                <div class="stat-card card-patients">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Patients</h5>
                            <h2 class="fw-bold"><?php echo $totalPatients; ?></h2>
                            <p class="mb-0">
                                <a href="patients.php" class="text-white text-decoration-none">
                                    Voir tous <i class="fas fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                        <div>
                            <i class="fas fa-user-injured"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte Départements -->
            <div class="col-md-4">
                <div class="stat-card card-departments">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Départements</h5>
                            <h2 class="fw-bold"><?php echo $totalDepartments; ?></h2>
                            <p class="mb-0">
                                <a href="departments.php" class="text-white text-decoration-none">
                                    Gérer <i class="fas fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                        <div>
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte Médecins -->
            <div class="col-md-4">
                <div class="stat-card card-medecins">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Médecins</h5>
                            <h2 class="fw-bold"><?php echo $totalMedecins; ?></h2>
                            <p class="mb-0">
                                <a href="medecins.php" class="text-white text-decoration-none">
                                    Voir tous <i class="fas fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                        <div>
                            <i class="fas fa-user-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patients Récents -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clock"></i> Patients Récents
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom Complet</th>
                                        <th>Date de Naissance</th>
                                        <th>Téléphone</th>
                                        <th>Email</th>
                                        <th>Date d'ajout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentPatients)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                Aucun patient enregistré
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>