<?php
require_once("../../../vendor/autoload.php");
use App\Controllers\AuthContrRegistre;

if(isset($_POST["role"]) && $_POST["role"] === "etudiant"){
    if(isset($_POST["submit"])){
        if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["diplomat"])){
            echo "Check your inputs name ...";
        } else {
            $username = $_POST["name"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $role = $_POST["role"];
            $diplomat = $_POST["diplomat"];
            $registre = new AuthContrRegistre();
            $registre->Signup($username , $email , $password ,$role ,$diplomat);
        }
    }
} else if(isset($_POST["role"]) && $_POST["role"] === "enseignant"){
    if(isset($_POST["submit"])){
      if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])){
        echo "Check your inputs name ...";
      } else {
        $username = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $role = $_POST["role"];
        $registre = new AuthContrRegistre();
        $registre->Signup($username , $email , $password ,$role );
      }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function toggleFields() {
        const role = document.getElementById('role').value;
        const candidatFields = document.getElementById('etudiantFields');
        
        // Hide all fields initially
        candidatFields.style.display = 'none';
        
        // Show fields based on selected role
        if (role === 'etudiant') {
            candidatFields.style.display = 'block';
        }
    }
    // Ensure fields are toggled on page load
    window.onload = function() {
        toggleFields();
    }
  </script>
</head>
<body>
  <div class="font-[sans-serif]">
    <div class="min-h-screen flex fle-col items-center justify-center py-6 px-4">
      <div class="grid md:grid-cols-2 items-center gap-4 max-w-6xl w-full">
        <div class="border border-gray-300 rounded-lg p-6 max-w-md shadow-[0_2px_22px_-4px_rgba(93,96,127,0.2)] max-md:mx-auto">
          <form id="login-form" class="space-y-4" action="" method="POST">
            <div class="mb-8">
              <h3 class="text-gray-800 text-3xl font-extrabold">Sign in</h3>
              <p class="text-gray-500 text-sm mt-4 leading-relaxed">Create your account and explore a world of possibilities. Your Career begins here.</p>
            </div>
            <div class="form-group">
                <select name="role" id="role" required onchange="toggleFields()">
                    <option value="">Choisir un rôle</option>
                    <option value="etudiant">étudiant</option>
                    <option value="enseignant">Enseignant</option>
                </select>
            </div>
            
            <div>
              <label class="text-gray-800 text-sm mb-2 block" >Name</label>
              <div class="relative flex items-center">
                <input name="name" type="text" required class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600" placeholder="Enter username" />
              </div>
            </div>
            
            <div>
              <label class="text-gray-800 text-sm mb-2 block">Email</label>
              <div class="relative flex items-center">
                <input name="email" type="email" required class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600" placeholder="Enter email" />
              </div>
            </div>
            
            <div>
              <label class="text-gray-800 text-sm mb-2 block">Password</label>
              <div class="relative flex items-center">
                <input name="password" type="password" required class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600" placeholder="Enter password" />
              </div>
            </div>
            <!-- Candidat Specific Fields -->
            <div id="etudiantFields" style="display:none;">
                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Diplomat</label>
                    <div class="relative flex items-center">
                        <input name="diplomat" type="text" class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600" placeholder="Enter diploma" />
                    </div>
                </div>
            </div>
            <div class="!mt-8">
              <button type="submit" name="submit" class="w-full shadow-xl py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                Create account
              </button>
            </div>
          </form>
        </div>
        <div class="lg:h-[400px] md:h-[300px] max-md:mt-8">
          <img src="https://readymadeui.com/login-image.webp" class="w-full h-full max-md:w-4/5 mx-auto block object-cover" alt="Login or Register" />
        </div>
      </div>
    </div>
  </div>
</body>
</html>