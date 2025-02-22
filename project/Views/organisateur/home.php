<?php
require_once("../../../vendor/autoload.php");
session_start();

use App\Controllers\CourController;
use App\Controllers\catcontroller;
use App\Controllers\tagController;

$fetchCats = new catcontroller();
$resultsCats = $fetchCats->getCat();

$fetchTags = new tagController();
$resultsTags = $fetchTags->getTag();

$coursFetch = new CourController();
if (isset($_POST['delete'])) {
    $id = $_POST['cour_id'];

    $courControlle = new CourController();
    $courControlle->deleteCour($id);
}
$searchInput = "";
if (isset($_POST["search"])) {
    $searchInput = $_POST["searchInput"];
    $resultsCours = $coursFetch->searchCourEnseignant($searchInput);
} else {
    $resultsCours = $coursFetch->fetchCour();
}

if (isset($_POST["modifier"])) {
    if (isset($_POST["contenuSelect"]) && $_POST["contenuSelect"] === "VIDEO") {
        if (empty($_POST["titre"]) || empty($_POST["discription"]) || empty($_POST["contenuVideo"]) || empty($_POST["categorie"]) || empty($_POST["tag"])) {
            echo "please ensure your input have the correct values";
        } else {
            $id = $_POST["id_cour"];
            $titre = $_POST["titre"];
            $description = $_POST["discription"];
            $fileUrl = $_POST["contenuVideo"];
            $categoryId = $_POST["categorie"];
            $tagId = $_POST["tag"];
            $enseignant_id = $_SESSION["user_id"];
            $courModifier = new CourController();
            $newVersionCour = $courModifier->editCourVideo($id, $titre, $description, $fileUrl, $enseignant_id, $categoryId, $tagId);
        }
    } else {
        if (isset($_POST["contenuSelect"]) && $_POST["contenuSelect"] === "PDF") {
            if (empty($_POST["titre"]) || empty($_POST["discription"]) || empty($_FILES["contenuPDF"]) || empty($_POST["categorie"]) || empty($_POST["tag"])) {
                echo "please ensure your input have the correct values";
            } else {
                $uploadDir = "../../../public/uploads";
                $fileName = basename($_FILES["contenuPDF"]["name"]);
                $targetFile = $uploadDir . $fileName;
                $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

                if ($fileType !== "pdf") {
                    die("only PDF files are allowed");
                }

                $id = $_POST["id_cour"];
                $titre = $_POST["titre"];
                $description = $_POST["discription"];
                $fileUrl = "/public/uploads/" . $fileName;
                $categoryId = $_POST["categorie"];
                $tagId = $_POST["tag"];
                $enseignant_id = $_SESSION["user_id"];
                $courModifier = new CourController();
                $newVersionCour = $courModifier->editCourPdf($id, $titre, $description, $fileUrl, $enseignant_id, $categoryId, $tagId);
            }
        }
    }
}

$countCour = new CourController();
$resultCount = $countCour->NombreDeCoursEnseignant();

$countCourInscrit = new CourController();
$resultCountInscription = $countCourInscrit->CountInscription();
$pattern = '/^.*\.pdf$/i';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enseignant - YouDemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-gray-100 via-blue-100 to-gray-200 font-sans">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-8 shadow-lg">
        <div class="max-w-screen-xl mx-auto text-center">
            <h1 class="text-5xl font-extrabold mb-4 animate-pulse">YouDemy - Enseignant</h1>
            <p class="text-lg font-medium">Trouvez les talents qui feront la différence dans votre entreprise.</p>
            <nav class="mt-6">
                <ul class="flex justify-center space-x-8 text-lg font-medium">
                    <li><a href="./cours.php" class="hover:underline">Cours</a></li>
                    <li><a href="./inscription.php" class="hover:underline">Inscriptions</a></li>
                </ul>
            </nav>
        </div>
        <div class="absolute top-4 right-8">
            <a href="../auth/logout.php">
                <button class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:ring focus:ring-red-300 focus:outline-none shadow-md transition">
                    Logout
                </button>
            </a>
        </div>
    </header>


    <main class="container mx-auto mt-12 p-8">

        <section class="text-center">
            <h2 class="text-4xl font-semibold mb-4 text-gray-800">Bienvenue sur YouDemy <?= $_SESSION["email"] ?></h2>
            <p class="text-xl text-gray-600 mb-4">Connectez-vous avec des étudiants talentueux et passionnés.</p>
            <p class="text-lg text-gray-500">Utilisez notre plateforme pour publier et gérer vos cours facilement.</p>
        </section>

        <form method="POST" class="mb-8">
            <div class="flex justify-center">
                <input type="text" name="searchInput" class="border border-gray-300 rounded-l-lg p-2 w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Rechercher un cours par titre, enseignant ou tag..." value="<?= $searchInput ?>">
                <button type="submit" name="search" class="bg-blue-500 text-white px-4 rounded-r-lg hover:bg-blue-600">
                    Rechercher
                </button>
            </div>
        </form>

        <div class="flex justify-center mt-8">
            <a href="./cours.php" class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 px-6 rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-800 focus:outline-none transform transition-transform hover:scale-110">
                Ajouter un Cours
            </a>
        </div>

        <section class="mt-16">
            <h2 class="text-3xl font-semibold mb-8 text-center text-gray-800">Cours Récents</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($resultsCours as $cour) { ?>
                    <div class="bg-white shadow-md border border-gray-200 rounded-lg overflow-hidden transform transition-transform hover:scale-105 hover:shadow-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 truncate">Title: <?= $cour['titre'] ?></h3> 
                            <iframe width="430" height="315" src="<?= $cour["contenu"] ?>" frameborder="0"></iframe>
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
                                <?php if (preg_match($pattern, $cour["contenu"])) { ?>
                                    <a href="<?= $cour['contenu']; ?>" download
                                        class="inline-flex items-center bg-green-500 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-md hover:bg-green-600 transition">
                                        <i class="fas fa-download mr-2"></i>Télécharger PDF
                                    </a>
                                <?php } else { ?>
                                    <a href="<?= $cour['contenu']; ?>" download
                                        class="inline-flex items-center bg-green-500 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-md hover:bg-green-600 transition">
                                        <i class="fas fa-download mr-2"></i>Watch VIDEO
                                    </a>
                                <?php } ?>
                                <div class="flex space-x-2">
                                    <button onclick="toggleFields(<?= $cour['id']; ?>,  `<?= htmlspecialchars($cour['titre'], ENT_QUOTES); ?>`, `<?= htmlspecialchars($cour['descrption'],ENT_QUOTES); ?>`, '<?= $cour['tag_name']; ?>', '<?= $cour['category_name']; ?>')" type="button" class="px-3 py-2 text-sm font-semibold text-white bg-yellow-500 rounded-lg hover:bg-yellow-600" id="modifier">
                                       Modifier
                                    </button>
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

        <div class="max-w-4xl mx-auto mt-10 p-8 bg-white shadow-lg rounded-lg" id="formModifier">
            <form id="cour-form" class="space-y-6" action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_cour" value="<?= $cour["id"] ?>">
                <div class="mb-6">
                    <h3 class="text-gray-800 text-4xl font-extrabold">Add Offres</h3>
                    <p class="text-gray-500 text-sm mt-2 leading-relaxed">Add your offre here, and explore more passionate candidates in our platform.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div>
                        <label for="tag" class="block text-gray-700 font-medium mb-2">Tags</label>
                        <select name="tag[]" id="tag" multiple required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a Tag</option>
                            <?php
                            foreach ($resultsTags as $resultTag) {
                                echo "<option value='{$resultTag['id']}'>{$resultTag['tag_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="categorie" class="block text-gray-700 font-medium mb-2">Categories</label>
                        <select name="categorie" id="categorie" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a Category</option>
                            <?php
                            foreach ($resultsCats as $resultCat) {
                                echo "<option value='{$resultCat['id']}'>{$resultCat['category_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="titre" class="block text-gray-700 font-medium mb-2">Titre</label>
                    <input name="titre" id="titre" type="text" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter post title">
                </div>
                <div>
                    <label for="contenuSelect" class="block text-gray-700 font-medium mb-2">Choice The Type Of Your Content</label>
                    <select name="contenuSelect" id="contenuSelect" required onchange="toggleFields()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Choisir un Choix</option>
                        <option value="PDF">PDF</option>
                        <option value="VIDEO">VIDEO</option>
                    </select>
                </div>

                <div id="PdfFields" style="display:none;">
                    <div>
                        <label for="contenuPDF" class="block text-gray-700 font-medium mb-2">Contenu PDF</label>
                        <input name="contenuPDF" type="file" id="contenu" accept=".pdf" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter Contenu"></textarea>
                    </div>
                </div>
                <div id="VideoFields" style="display:none;">
                    <div>
                        <label for="contenuVideo" class="block text-gray-700 font-medium mb-2">Contenu VIDEO</label>
                        <input name="contenuVideo" type="text" id="contenu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter Contenu https//:...."></textarea>
                    </div>
                </div>
                <div>
                    <label for="discription" class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="discription" id="discription" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter description"></textarea>
                </div>

                <div class="mt-6">
                    <button type="submit" name="modifier" class="w-full py-3 px-6 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        Modifier
                    </button>
                </div>
            </form>
        </div>

        <section class="mt-16 bg-gray-50 shadow-xl rounded-lg p-8 max-w-md mx-auto">
            <h1 class="text-3xl font-semibold mb-8 text-center text-gray-900">
                Statistiques
            </h1>


            <div class="flex flex-col items-center justify-center space-y-6">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-8 px-10 rounded-lg shadow-lg w-full">
                    <h3 class="text-xl font-semibold uppercase tracking-wide mb-2">
                        Nombre de cours
                    </h3>
                    <p class="text-5xl font-extrabold">
                        <?= $resultCount ?>
                    </p>
                </div>
                <p class="text-base text-gray-600 text-center px-6">
                    Ce chiffre reflète le nombre total de cours que vous avez publiés sur la plateforme.
                </p>
            </div>


            <hr class="my-8 border-gray-200 w-3/4 mx-auto">


            <div class="flex flex-col items-center justify-center space-y-6">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 text-white py-8 px-10 rounded-lg shadow-lg w-full">
                    <h3 class="text-xl font-semibold uppercase tracking-wide mb-2">
                        Nombre d'étudiants inscrits
                    </h3>
                    <p class="text-5xl font-extrabold">
                        <?= $resultCountInscription ?>
                    </p>
                </div>
                <p class="text-base text-gray-600 text-center px-6">
                    Ce chiffre reflète le nombre total d'étudiants inscrits dans vos cours.
                </p>
            </div>
        </section>




        <section class="mt-16">
            <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">Contactez-Nous</h2>
            <form class="bg-white shadow-xl rounded-lg p-8 max-w-lg mx-auto">
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2" for="name">Nom</label>
                    <input type="text" id="name" class="mt-1 p-4 w-full border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Votre nom" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2" for="email">Email</label>
                    <input type="email" id="email" class="mt-1 p-4 w-full border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Votre email" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2" for="message">Message</label>
                    <textarea id="message" class="mt-1 p-4 w-full border rounded-lg focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Votre message" required></textarea>
                </div>
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 px-6 rounded-lg shadow-md hover:from-blue-600 hover:to-blue-800 focus:outline-none w-full">
                    Envoyer
                </button>
            </form>
        </section>


    </main>

    <footer class="bg-gray-900 text-white text-center py-6 mt-16">
        <p>&copy; 2025 YouDemy. Tous droits réservés.</p>
    </footer>

</body>
<script>
    formModifier.style.display = 'none';

    function toggleFields(id, title = "", description = "", tagName = "", categoryName = "") {  
        const contenuSelect = document.getElementById('contenuSelect').value;
        const candidatFields = document.getElementById('PdfFields');
        const recruteurFields = document.getElementById('VideoFields');

        PdfFields.style.display = 'none';
        VideoFields.style.display = 'none';

        if (contenuSelect === 'PDF') {
            PdfFields.style.display = 'block';
        } else if (contenuSelect === 'VIDEO') {
            VideoFields.style.display = 'block';
        }
        const formModifier = document.getElementById("formModifier");
        const titreInput = document.getElementById("titre");
        const descriptionInput = document.getElementById("discription");
        const tagSelect = document.getElementById("tag");
        const categorySelect = document.getElementById("categorie");

        formModifier.style.display = "block";

        if (titreInput) titreInput.value = title;
        if (descriptionInput) descriptionInput.value = description;

    }
</script>

</html>