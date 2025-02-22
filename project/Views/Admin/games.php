<?php
require_once("../../../vendor/autoload.php");

use App\Controllers\CourController;

$coursFetch = new CourController();
if (isset($_POST["delete"])) {
    $id = $_POST["cour_id"];

    $deleteCour = new CourController();
    $deleteCour->deleteCour($id);
}
$resultFetch = $coursFetch->fetchCourEtudiant();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cours Management</title>
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
                <h1 class="text-4xl font-bold text-gray-800">cours Management</h1>
                <p class="text-gray-600 text-lg mt-2">Gérez vos Cours facilement. supprimez des cours en toute simplicité.</p>
            </header>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Cours Existants</h2>
                <section class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($resultFetch as $cour) { ?>
                            <div class="bg-white shadow-md border border-gray-200 rounded-lg overflow-hidden transform transition-transform hover:scale-105 hover:shadow-xl">
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 truncate">Instructor: <?= $cour['enseignant_name'] ?></h3>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 truncate">Title: <?= $cour['titre'] ?></h3>
                                    <iframe width="330" height="280" src="<?= $cour["contenu"] ?>" frameborder="0"></iframe>
                                    <p class="text-sm text-gray-500 mb-1">
                                        <i class="fas fa-calendar-alt mr-2"></i>Date: <span class="font-medium"><?= $cour['created_at'] ?></span>
                                    </p>
                                    <p class="text-sm text-gray-500 mb-1">
                                        <i class="fas fa-folder mr-2"></i>Category: <span class="font-medium"><?= $cour['category_name'] ?></span>
                                    </p>

                                    <p class="text-sm text-gray-500 mb-1">
                                        <i class="fas fa-tag mr-2"></i>Tag: <span class="font-medium"><?= $cour['tag_name'] ?></span>
                                    </p>

                                    <p class="text-sm text-gray-600 mt-4 mb-4 leading-relaxed"><?= substr($cour['descrption'], 0, 100) ?>...</p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex space-x-2">
                                            <form action="" method="POST" onsubmit="return confirm('Etes-vous sûr de vouloir supprimer ce cours ?');">
                                                <input type="hidden" name="cour_id" value="<?= $cour['id']; ?>" />
                                                <button type="submit" name="delete"
                                                    class="inline-flex items-center bg-red-500 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-md hover:bg-red-600 transition">
                                                    <i class="fas fa-trash-alt mr-2"></i>Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </section>
            </div>
        </div>
    </div>
</body>

</html>