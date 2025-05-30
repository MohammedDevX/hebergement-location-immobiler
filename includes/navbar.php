<?php
// session_start();

$isLoggedIn = isset($_SESSION['user_id']);

require "../libraries/phpmailer/mail.php";
require "../includes/connection.php";

if (isset($_POST["valide"])) {
    $email = $_POST['email'] ?? '';

    $query = "SELECT * FROM locataire WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Créer le lien avec l'email
        $resetLink = "http://localhost/hebergement-entreparticulier/pages/updatePassword.php?email=" . urlencode($email);

        // Configuration de l'envoi de mail
        $mail->setFrom("itsbakhtaouimohammed@gmail.com", "Bakhtaoui Mohammed");
        $mail->addAddress($email); 
        $mail->Subject = "Lien de réinitialisation du mot de passe";
        $mail->Body = "Cliquez ici pour réinitialiser votre mot de passe : <a href='$resetLink'>Réinitialiser</a>";
        $mail->AltBody = "Cliquez ici pour réinitialiser votre mot de passe : $resetLink";

        if ($mail->send()) {
            setcookie("notif", "Un lien de réinitialisation a été envoyé à votre email.", time() + 10*24*60*60);
            header("Location:acceuille.php");
            die;
        } else {
            setcookie("notif", "Erreur d'envoi de l'email.", time() + 10*24*60*60);
            header("Location:acceuille.php");
            die;
        }
    } else {
        setcookie("notif", "Email introuvable dans notre base de données.", time() + 10*24*60*60);
        header("Location:acceuille.php");
        die;
    }
}

// Le reste de votre code HTML reste inchangé
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
	    <script src="https://kit.fontawesome.com/94d90b0a3f.js" crossorigin="anonymous"></script>

	<style>
		.custom-overlay {
		  background-color: rgba(0, 0, 0, 0.3); /* Adjust the opacity as needed */
		}
		@font-face {
			font-family: 'Krylon';
			src: url("../assets/fonts/Krylon-Regular.otf") format("opentype");
			font-weight: 900;
		}
		@font-face {
			font-family: 'Grotesk';
			src: url("../assets/fonts/Grotesk-Regular.ttf") format("truetype");
		}
		.hide-scrollbar::-webkit-scrollbar {
    		display: none; /* Chrome, Safari, and Opera */
		}

		.hide-scrollbar {
		    -ms-overflow-style: none; /* Internet Explorer 10+ */
		    scrollbar-width: none; /* Firefox */
		}
	  </style>
	<title>NovaNook - Accueil</title>
</head>
<body class="font-[Grotesk]">

	<input type="radio" id="hide" name="Modal" class="hidden"/>
	
	<!-- navbar -->
	<div class="flex  justify-center">
        <div class="bg-[#005555] w-90/100 h-20 m-5 rounded-lg flex items-center shadow-xl">
            <a href="../pages/acceuille.php" class=" font-[Krylon] text-2xl text-white duration-100 hover:text-[#ffffffcc] px-5">NovaNook</a>
			
            <ul class="flex justify-end w-full">
				<?php if ($isLoggedIn) { ?>
				<div class="flex mr-5 ">
					<div class="text-white text-2xl hover:cursor-pointer">
						<label for="LoggedMenu"><i class="fa-solid fa-user hover:cursor-pointer"></i></label>
					</div>
				</div>
					<input type="radio" id="LoggedMenu" name="Modal" class="hidden peer/LoggedMenu"/>
					<label for="hide" class="hidden peer-checked/LoggedMenu:block fixed inset-0 z-40 custom-overlay"></label>
					<div  class="hidden peer-checked/LoggedMenu:flex items-center mr-[10/100]  justify-center bg-[#005555] text-white p-4 absolute  rounded-lg shadow-lg z-50 pointer-events-none">
						<ul class="flex flex-col text-sm pointer-events-auto">
							<li class="hover:cursor-pointer mb-5">
								<label class="text-[#EFE9E7] text-center hover:cursor-pointer block font-[Krylon] text-[1.2em] duration-400 hover:text-[#888098]">
										<a href="../pages/profile.php?id_locataire=<?php echo $_SESSION["user_id"]["id_locataire"] ?>" class="text-center font-[Grotesk] text-[1em] text-white hover:text-[#ffffffcc]">Profile</a>
								</label>
							</li>
							<li class="hover:cursor-pointer">
								<label class="text-[#EFE9E7] hover:cursor-pointer block  font-[Krylon] text-[1.2em] duration-400 hover:text-[#888098]">
										<!-- se deconnecter -->
										<a href="../pages/logout.php" class=" font-[Grotesk] text-[1em] text-white hover:text-[#ffffffcc]">LogOut</a>
										<!-- se deconnecter -->
								</label>
							</li>
						</ul>
					</div>
                <?php } else { ?>
                
		<!-- partie li khasha tkhb3 -->
				
				<div class="">
					<!-- Button pour menu -->
					<div class="sm:hidden flex mr-5">
						<label for="DropDown" class="text-white text-2xl hover:cursor-pointer">
							<i class="fas fa-bars hover:cursor-pointer"></i>
						</label>
					</div>
					<!-- Button pour menu -->
					<!-- Login & inscription Button  -->
					<div class=" hidden sm:block">
						<label for="signin" class="text-[#EFE9E7] hover:cursor-pointer font-[Krylon] text-[1.2em] px-2.5 duration-400 hover:text-[#888098]">Sign in</label>
						<label for="signup" class="text-[#EFE9E7] hover:cursor-pointer font-[Krylon] border text-[1.2em] p-3 mr-5 rounded-md duration-500 hover:text-[#888098] hover:border-[#888098]">Sign up</label>
					</div>
					<!-- Login & inscription Button  -->
					<!-- Drop down menu for mobile -->
					<input type="radio" id="DropDown" name="Modal" class="hidden peer/DropDown"/>
					<label for="hide" class="hidden peer-checked/DropDown:block fixed inset-0 z-40 custom-overlay"></label>
					<div  class="hidden sm:hidden peer-checked/DropDown:flex items-center justify-center bg-[#005555] text-white p-4 absolute  top-20 right-5 rounded-lg shadow-lg z-50 pointer-events-none">
						<ul class="justify-center pointer-events-auto">
							<li class="hover:cursor-pointer	mb-5">
								<label for="signin" class="text-[#EFE9E7] hover:cursor-pointer block px-5 font-[Krylon] text-[1.2em] duration-400 hover:text-[#888098]">Sign in</label>
							</li>
							<li>
								<label for="signup" class="text-[#EFE9E7] hover:cursor-pointer px-5 font-[Krylon] border text-[1.2em] p-3 rounded-md duration-500 hover:text-[#888098] hover:border-[#888098]">Sign up</label>
							</li>
						</ul>
					</div>
					<!-- Drop down menu for mobile -->
					
				</div>
				<?php } ?>
		<!-- partie li khasha tkhb3 -->
            </ul>
        </div>
		
    </div>
	
	
	<!-- navbar -->
	<!-- connexion -->
	 
	<input type="radio" id="signin" name="Modal" class="hidden peer/signin"/>
	<label for="hide" class="hidden peer-checked/signin:block fixed inset-0 z-40 custom-overlay "></label>
	<div class=" font-[Grotesk]  hidden fixed inset-0 peer-checked/signin:flex items-center justify-center min-h-screen z-50 pointer-events-none">
		<div class="bg-white shadow-md rounded-lg p-6 overflow-y-auto hide-scrollbar max-h-[90vh] w-[90%] max-w-lg pointer-events-auto">
			<h1 class="Krylon text-4xl text-gray-800 mb-4 text-center">Sign In?</h1>
			<p class="text-gray-600 text-sm mb-6 text-center">
				Enter your Email and your password.
			</p>
			<form action="testLogin.php" method="post" class="space-y-4">
				<div class="mb-6">
					<label 
					for="email" 
					class="block font-medium text-lg text-gray-700">Email Address</label>
					<input 
					type="email" 
					id="email" 
					placeholder="Enter your email" 
					name="email"
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-6">
					<label 
					for="password" 
					class="block font-medium text-lg text-gray-700">Password</label>
					<input 
					type="password" 
					id="password" 
					placeholder="Enter your email"
					name="password" 
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-4">
					<button type="submit" class="w-full px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-[#0b574fe2] ">Connect</button>
				</div>
			</form>
			<div class="flex justify-center items-center">
				<label for="signup" class="text-center text-gray-700 mb-5 text-md text-nova  hover:text-[#0b574fe2] px-9 hover:opacity-80 hover:cursor-pointer">Sign Up</label>
			</div>
			<div class=" flex justify-center items-center">
				<label for="recover" class="border-t-1 pt-4 text-center text-gray-700 text-sm text-nova  hover:underline px-9 hover:opacity-80 hover:cursor-pointer">Forgot your Password ?</label>
			</div>
		</div>
	</div>
	<!-- Connexion -->

	<!-- inscription -->
	
	<input type="radio" id="signup" name="Modal" class="hidden peer/signup"  />
	<label for="hide" class="hidden peer-checked/signup:block fixed inset-0 z-40 custom-overlay""></label>
	<div class=" font-[Grotesk]  hidden fixed inset-0 peer-checked/signup:flex items-center justify-center min-h-screen z-50 pointer-events-none">
		<div class="bg-white shadow-md rounded-lg p-6 w-[90%] overflow-y-auto hide-scrollbar max-w-lg max-h-[90vh] pointer-events-auto">
			<h1 class="Krylon text-4xl text-gray-800 mb-4 text-center">Sign Up</h1>
			<p class="text-gray-600 text-sm mb-6 text-center">
				Enter your informations.
			</p>
			<form action="testSignup.php" method="POST" enctype="multipart/form-data" class="space-y-4">
				<div class="mb-6">
					<div class="flex flex-col items-center text-center">
				<!-- Image with Label -->
				<label for="profileImageInput" class="cursor-pointer relative mb-4 block">
					<!-- Image -->
					<img
					src="https://placehold.co/80x80"
					alt="Profile Picture"
					class="size-20 rounded-full object-cover peer"
					id="profileImage"
					/>
					<!-- Overlay that appears on hover -->
					<div class="absolute inset-0 hidden peer-hover:flex hover:flex bg-black/50 rounded-full items-center justify-center text-white text-sm font-medium">
					Add
					</div>
					
					<!-- Hidden File Input -->
					<input
					type="file"
					id="profileImageInput"
					name="photo_profil" 
					accept="image/*"
					class="hidden"
					/>
				</label>
				</div>
				</div>
				<div class="mb-6">
					<label 
					for="nom" 
					class="block font-medium text-lg text-gray-700">First Name</label>
					<input 
					type="text" 
					id="nom" 
					placeholder="Enter your First Name" 
					name="prenom"
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-6">
					<label 
					for="nom" 
					class="block font-medium text-lg text-gray-700">Last Name</label>
					<input 
					type="text" 
					id="nom" 
					placeholder="Enter your Last Name" 
					name="nom"
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-6">
					<label 
					for="nom" 
					class="block font-medium text-lg text-gray-700">Phone Number</label>
					<input 
					type="text" 
					id="nom" 
					placeholder="Phone Number" 
					name="tel"
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-6">
					<label 
					for="email" 
					class="block font-medium text-lg text-gray-700">Email Address</label>
					<input 
					type="email" 
					id="email" 
					placeholder="Enter your email" 
					name="email"
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-6">
					<label 
					for="username" 
					class="block font-medium text-lg text-gray-700">Username</label>
					<input 
					type="username" 
					id="username" 
					placeholder="Enter your username"
					name="nom_user" 
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-6">
					<label 
					for="password" 
					class="block font-medium text-lg text-gray-700">Password</label>
					<input 
					type="text"
					id="password" 
					placeholder="Enter a password"
					name="mot_passe" 
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-6">
					<label 
					for="Confirm_password" 
					class="block font-medium text-lg text-gray-700">Confirm Password</label>
					<input 
					type="text"
					id="Confirm_password" 
					placeholder="Repeat your password"
					name="Confirm_password" 
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-4">
					<button type="submit" class="w-full px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-opacity-60 hover:cursor-pointer">Join</button>
				</div>
			</form>
			<div class="flex justify-center items-center">
				<div class="inline border-t-1 px-20 pb-4 mt-5" ></div>
			</div>
			<div class="flex justify-center items-center">
				<label for="signin" class="fle text-center text-gray-700 text-md text-nova  hover:underline px-5 hover:opacity-80 hover:cursor-pointer">Sign In</Sign></label>
			</div>
		</div>
	</div>

	<!-- inscription -->

	<!-- recover mot de passe -->
	<input type="radio" id="recover" name="Modal" class="peer/recover hidden" />
	<label for="hide" class="hidden peer-checked/recover:block fixed inset-0 z-40 custom-overlay"></label>
	<div class="hidden peer-checked/recover:flex fixed inset-0 items-center justify-center z-50 pointer-events-none">
	    <div class="bg-white shadow-md rounded-lg p-6 w-[90%] max-w-lg overflow-y-auto hide-scrollbar max-h-[90vh] pointer-events-auto">
	        <h1 class="Krylon text-4xl text-gray-800 mb-4 text-center">Forgot Password?</h1>
	        <p class="text-gray-600 text-sm mb-6 text-center">
	            Enter your email address below and we'll send you a link to reset your password.
	        </p>
	        <form action="acceuille.php" method="POST" class="space-y-4">
	            <div class="mb-6">
	                <label for="email" class="block font-medium text-lg text-gray-700">Email Address</label>
	                <input
	                    type="email"
	                    id="email"
	                    name="email"
	                    placeholder="Enter your email"
	                    required
	                    class="mt-1 text-md p-1.5 block w-full border-gray-300 bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
	                />
	            </div>
	            <div class="mb-4">
	                <button type="submit" name="valide" class="w-full px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-[#0b574fe2]">Validate</button>
	            </div>
	        </form>
	        <div class="flex justify-center items-center">
	            <label for="signin" class="border-t-1 pt-4 mt-5 text-center text-gray-700 text-sm text-nova hover:underline px-9 hover:opacity-80 hover:cursor-pointer">Back to Sign In</label>
	        </div>
	    </div>
	</div>

	<script src="../assets/js/navbar.js"></script>
</body>
</html>
<!-- Le reste de votre code HTML reste inchangé -->