<?php
require_once("../../../vendor/autoload.php");
use App\Controllers\CourController;

$Membres = new CourController();
$countEtudiant = $Membres->CountEtudiant();
$countEnseignant = $Membres->CountEnseignant();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - YouDemy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-blue-600 text-white w-64 min-h-screen p-6">
            <h1 class="text-2xl font-bold text-center mb-6">YouDemy</h1>
            <nav>
                <ul class="space-y-4">
                <li><a href="./dashboard.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-tachometer-alt"></i><span class="ml-2">Tableau de bord</span></a></li>
                    <li><a href="./tag.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-tags"></i><span class="ml-2">Tags Utilisés</span></a></li>
                    <li><a href="./categorie.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-tags"></i><span class="ml-2">Categorie Utilisés</span></a></li>
                    <li><a href="./UsersList.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-chart-line"></i><span class="ml-2">Users List</span></a></li>
                    <li><a href="./cours.php" class="flex items-center bg-blue-700 p-3 rounded transition duration-300"><i class="fas fa-chart-line"></i><span class="ml-2">Enseignant Cours</span></a></li>
                    <li><a href="./statistique.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-chart-line"></i><span class="ml-2">Statistique</span></a></li>
                    <li><a href="../auth/logout.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-chart-line"></i><span class="ml-2">Déconnexione</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h2 class="text-3xl font-semibold mb-6">Bienvenue sur votre tableau de Bord</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <h3 class="font-bold text-xl">Numbre Des étudiant </h3>
                    <p class="text-2xl mt-2"><?= $countEtudiant ?></p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <h3 class="font-bold text-xl">Numbre D'Enseignant</h3>
                    <p class="text-2xl mt-2"><?= $countEnseignant?></p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
