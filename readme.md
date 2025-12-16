# Unity Care Clinic - Système de Gestion de Clinique

## 📋 Description
Système de gestion simple pour une clinique médicale développé en PHP procédural et MySQL.

## 🎯 Fonctionnalités
- ✅ Gestion complète des patients (CRUD)
- ✅ Gestion des départements médicaux
- ✅ Gestion des médecins et leurs spécialités
- ✅ Dashboard avec statistiques en temps réel
- ✅ Interface moderne avec Bootstrap 5

## 🛠️ Technologies Utilisées
- PHP 8.5 (Procédural)
- MySQL 8.0
- Bootstrap 5.3
- Docker & Docker Compose
- PDO pour la sécurité des requêtes

## 📁 Structure du Projet
```
unity-care-clinic/
│
├── src/
│   ├── config/
│   │   └── database.php          # Connexion à la base de données
│   │
│   ├── functions/
│   │   ├── patients.php          # Fonctions CRUD patients
│   │   ├── departments.php       # Fonctions CRUD départements
│   │   └── medecins.php          # Fonctions CRUD médecins
│   │
│   └── public/
│       ├── index.php             # Dashboard principal
│       ├── patients.php          # Gestion des patients
│       ├── departments.php       # Gestion des départements
│       └── medecins.php          # Gestion des médecins
│
├── docker-compose.yml            # Configuration Docker
├── .env                          # Variables d'environnement
├── init.sql                      # Script d'initialisation de la BD
└── README.md                     # Ce fichier
```

## 🚀 Installation

### Prérequis
- Docker Desktop installé
- Git (optionnel)

### Étape 1 : Télécharger le Projet
```bash
# Cloner ou télécharger le projet
cd unity-care-clinic
```

### Étape 2 : Configurer les Fichiers
Assurez-vous que ces 3 fichiers ont les **mêmes identifiants** :

**1. docker-compose.yml**
```yaml
DB_NAME=unity_clinic_db
DB_USER=clinic_user
DB_PASSWORD=SecurePass123
```

**2. .env**
```
DB_NAME=unity_clinic_db
DB_USER=clinic_user
DB_PASSWORD=SecurePass123
```

**3. src/config/database.php**
```php
$dbname = 'unity_clinic_db';
$username = 'clinic_user';
$password = 'SecurePass123';
```

### Étape 3 : Démarrer les Conteneurs Docker
```bash
# Démarrer les services
docker-compose up -d

# Vérifier que tout fonctionne
docker-compose ps
```

Vous devriez voir 3 conteneurs actifs :
- `unity-care-web` (serveur web)
- `unity-care-db` (base de données)
- `unity-care-phpmyadmin` (interface MySQL)

### Étape 4 : Accéder à l'Application

🌐 **Application principale** : http://localhost:8080

🗄️ **phpMyAdmin** : http://localhost:8081
- Utilisateur : `clinic_user`
- Mot de passe : `SecurePass123`

## 📊 Base de Données

### Tables Créées Automatiquement
1. **patients** - Informations des patients
2. **departments** - Départements médicaux
3. **medecins** - Médecins et leurs spécialités

### Données de Test Incluses
- 4 départements (Cardiologie, Pédiatrie, Chirurgie, Radiologie)
- 3 patients
- 4 médecins

## 🔧 Commandes Utiles

```bash
# Démarrer les conteneurs
docker-compose up -d

# Arrêter les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f

# Redémarrer après modification
docker-compose restart

# Tout supprimer (données incluses)
docker-compose down -v
```

## 🐛 Dépannage

### Problème 1 : "Connection refused"
**Solution** : Vérifier que les 3 fichiers ont les mêmes credentials
```bash
docker-compose down
docker-compose up -d
```

### Problème 2 : Port déjà utilisé
**Solution** : Modifier les ports dans `docker-compose.yml`
```yaml
ports:
  - "8082:80"  # Au lieu de 8080
```

### Problème 3 : Base de données vide
**Solution** : Réinitialiser la base
```bash
docker-compose down -v
docker-compose up -d
```

## 📝 Utilisation

### Ajouter un Patient
1. Aller sur http://localhost:8080
2. Cliquer sur "Patients" dans le menu
3. Cliquer sur "Ajouter un Patient"
4. Remplir le formulaire
5. Soumettre

### Voir les Statistiques
Le dashboard affiche automatiquement :
- Nombre total de patients
- Nombre de départements
- Nombre de médecins
- Liste des patients récents

## 🔐 Sécurité

✅ **Requêtes préparées (PDO)** - Protection contre les injections SQL
✅ **Validation des données** - Vérification côté serveur
✅ **Échappement HTML** - Protection XSS avec `htmlspecialchars()`

## 🎓 Pour les Débutants

### Comment fonctionne le CRUD ?

**CREATE (Ajouter)** → Fonction `addPatient()`
```php
addPatient([
    'nom' => 'Alami',
    'prenom' => 'Hassan',
    // ...
]);
```

**READ (Lire)** → Fonction `getAllPatients()`
```php
$patients = getAllPatients();
```

**UPDATE (Modifier)** → Fonction `updatePatient()`
```php
updatePatient($id, ['nom' => 'Nouveau nom']);
```

**DELETE (Supprimer)** → Fonction `deletePatient()`
```php
deletePatient($id);
```

## 📚 Prochaines Étapes

1. ✅ Créer les pages `patients.php`, `departments.php`, `medecins.php`
2. ✅ Ajouter la validation des formulaires
3. ⬜ Implémenter la recherche
4. ⬜ Ajouter l'internationalisation (i18n)
5. ⬜ Intégrer Chart.js pour les graphiques
6. ⬜ Ajouter AJAX pour plus de fluidité

## 🆘 Besoin d'Aide ?

Si vous rencontrez un problème :
1. Vérifiez les logs : `docker-compose logs`
2. Vérifiez phpMyAdmin : http://localhost:8081
3. Assurez-vous que les 3 fichiers ont les mêmes credentials

## 📄 Licence
Projet éducatif - Libre d'utilisation

---
**Développé avec ❤️ pour Unity Care Clinic**