<?php

use App\Controllers\CourController;

require_once("../../../vendor/autoload.php");
$staticFetch = new CourController();
$results = $staticFetch->numbreCours();
$plusEtudiant = $staticFetch->courPlusEtudiant();
$TopEnseignant = $staticFetch->TopEnseignant();
$repartitionCategorie = $staticFetch->categorieNumbre();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans bg-gray-100">

    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-blue-600 text-white w-64 min-h-screen p-6 shadow-lg">
            <h1 class="text-2xl font-bold text-center mb-6">YouDemy</h1>
            <nav>
                <ul class="space-y-4">
                    <li><a href="./dashboard.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-tachometer-alt"></i><span class="ml-2">Tableau de bord</span></a></li>
                    <li><a href="./tag.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-tags"></i><span class="ml-2">Tags Utilisés</span></a></li>
                    <li><a href="./categorie.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-tags"></i><span class="ml-2">Categorie Utilisés</span></a></li>
                    <li><a href="./UsersList.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-chart-line"></i><span class="ml-2">Users List</span></a></li>
                    <li><a href="./cours.php" class="flex items-center bg-blue-700 p-3 rounded transition duration-300"><i class="fas fa-chart-line"></i><span class="ml-2">Enseignant Cours</span></a></li>
                    <li><a href="./statistique.php" class="flex items-center hover:bg-blue-500 p-3 rounded transition duration-300"><i class="fas fa-chart-line"></i><span class="ml-2">Statistique</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h2 class="text-3xl font-semibold text-gray-900 mb-6">Statistiques des Cours</h2>

            <!-- Table -->
            <table class="min-w-full table-auto border-collapse bg-white rounded-lg shadow-lg overflow-hidden">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium">Nombre total de cours</th>
                        <th class="px-6 py-4 text-left text-sm font-medium">Répartition par catégorie</th>
                        <th class="px-6 py-4 text-left text-sm font-medium">Le cours avec le plus d'étudiants</th>
                        <th class="px-6 py-4 text-left text-sm font-medium">Les Top 3 enseignants</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr class="hover:bg-gray-100 border-t border-gray-200">
                        <td class="px-6 py-4"><?= $results; ?></td>
                        <td class="px-6 py-4">
                            <ul>
                                <?php foreach ($repartitionCategorie as $categorie) :?>
                                    <li><?= $categorie["category_name"]?> : <?= $categorie["numbre_categorie"]?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td class="px-6 py-4"><?= $plusEtudiant ?></td>
                        <td class="px-6 py-4">
                            <ul>
                                <?php foreach ($TopEnseignant as $enseignant): ?>
                                    <li><?= $enseignant["name"] ?> : <?= ($enseignant["topEnseignant"]) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>

</body>

</html>