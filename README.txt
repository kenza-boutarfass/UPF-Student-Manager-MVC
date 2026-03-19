Nom de la base de données : gestion_upf

Comptes de test :
- Admin
  login : admin
  mot de passe : admin123

- Étudiant (user)
  login : e001
  mot de passe : user12345
  (lié à l'étudiant Code = E001)

Exécution (XAMPP / PHP 7+ / MySQL) :
1) Copier le dossier du projet dans XAMPP :
   C:\xampp\htdocs\gestion_upf_mvc\
   (le dossier doit contenir index.php, controllers/, models/, views/, config/, assets/, uploads/, etc.)

2) Importer la base de données :
   - Ouvrir phpMyAdmin : http://localhost/phpmyadmin
   - Onglet Importer
   - Choisir le fichier : gestion_upf_mvc.sql
   - Exécuter

3) Vérifier la configuration BDD :
   - Ouvrir : config/config.php
   - Adapter si besoin : DB_HOST, DB_USER, DB_PASS, DB_NAME

4) Lancer l'application :
   - URL : http://localhost/gestion_upf_mvc/index.php
   - Login :
     * admin / admin123  -> Dashboard admin + CRUD étudiants + filières
     * e001 / user12345  -> Profil + Notes + Documents + Changement mot de passe

Routage :
Toutes les URLs suivent :
index.php?ctrl={controleur}&action={methode}

Exemples :
- Login : index.php?ctrl=auth&action=login
- Dashboard admin : index.php?ctrl=etudiant&action=dashboard
- Liste étudiants : index.php?ctrl=etudiant&action=liste
- Liste filières : index.php?ctrl=filiere&action=liste
- Profil user : index.php?ctrl=user&action=profil

