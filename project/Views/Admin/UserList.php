<?php
require_once("../../../vendor/autoload.php");

use App\Controllers\AuthContrRegistre;
use App\Controllers\UserControlle;

if (isset($_POST["status"])) {
    $id = $_POST["id"];
    $newstatus = $_POST["status"];

    $statusController = new AuthContrRegistre();
    $statusController->updateStatus($id, $newstatus);
}
$userController = new UserControlle();
$results = $userController->getUser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tags Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100">
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
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <header class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800">Users Management</h1>
                <p class="text-gray-600 text-lg mt-2">Gérez vos Utilisateurs facilement. Activer, Suspenser ou supprimez des Utilisateurs en toute simplicité.</p>
            </header>


            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Users List</h2>
                <table class="table-auto w-full border-collapse bg-white">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-3 text-left text-sm font-medium">Nom d'utilisateur</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">E-mail d'utilisateur</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Role d'utilisateur</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php foreach ($results as $result) { ?>
                            <tr class="hover:bg-gray-100 border-t border-gray-200">
                                <td class="px-6 py-4"><?= $result['name']; ?></td>
                                <td class="px-6 py-4"><?= $result['email']; ?></td>
                                <td class="px-6 py-4"><?= $result['role']; ?></td>
                                <td class="px-6 py-4"><?= $result['status']; ?></td>
                                <td class="px-6 py-4 space-x-2">
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="<?= $result['id']; ?>">
                                        <?php if ($result['status'] === "Activation") { ?>
                                            <button type="submit" name="status" value="suspension" class="px-3 py-2 text-sm font-semibold text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                                Suspendre
                                            </button>
                                            <button type="submit" name="status" value="Not Active" class="px-3 py-2 text-sm font-semibold text-white bg-red-500 rounded-lg hover:bg-red-600">
                                                Désactiver
                                            </button>
                                        <?php } elseif ($result['status'] === "suspension") { ?>
                                            <button type="submit" name="status" value="Activation" class="px-3 py-2 text-sm font-semibold text-white bg-green-500 rounded-lg hover:bg-green-600">
                                                Activer
                                            </button>
                                        <?php } elseif ($result['status'] === "Not Active") { ?>
                                            <button type="submit" name="status" value="Activation" class="px-3 py-2 text-sm font-semibold text-white bg-green-500 rounded-lg hover:bg-green-600">
                                                Activer
                                            </button>
                                        <?php } ?>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>