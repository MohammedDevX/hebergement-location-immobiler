<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter_nous</title>
    <style>
        @font-face {
			font-family: 'Krylon';
			src: url("../assets/fonts/Krylon-Regular.otf") format("opentype");
			font-weight: 900;
		}
		@font-face {
			font-family: 'Grotesk';
			src: url("../assets/fonts/Grotesk-Regular.ttf") format("truetype");
		}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/94d90b0a3f.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="../assets/css/contacter_nous.css"> -->
</head>
<body class="bg-[#fafafa]">
    <div class="navbar-container">
        <?php include "../includes/navbar.php" ?>
    </div>
    
        <!-- Hero Section -->
        <div class=" font-[Grotesk] flex min-h-[900px] items-center justify-center">
		<div class="bg-white shadow-md rounded-lg p-6 w-[90%] max-w-5xl ">
			<h1 class="font-[Krylon] text-6xl text-gray-800 mb-4 text-center">Contact Us</h1>
			<p class="text-gray-600 text-sm mb-6 text-center">
				We'd love to hear from you! Please fill out the form below, and we'll get back to you as soon as possible.
			</p>
			<form action="contactNous" method="POST" class="space-y-4">
                <div class="mb-6 flex-auto flex flex-row gap-4">
                    <div class="w-full">
                        <label 
                        for="firstName" 
                        class="block font-medium text-lg text-gray-700">First Name</label>
                        <input 
                        type="text" 
                        id="firstName" 
                        placeholder="Enter your first name" 
                        name="firstName"
                        required 
                        class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                        >
                    </div>
                    <div class="w-full">
                        <label 
                        for="lastName" 
                        class="block font-medium text-lg text-gray-700">Last Name</label>
                        <input 
                        type="text" 
                        id="lastName" 
                        placeholder="Enter your last name" 
                        name="lastName"
                        required 
                        class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                        >
                    </div>
                </div>
				<div class="mb-6">
					<label 
					for="email" 
					class="block font-medium text-lg text-gray-700">Your Email</label>
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
					for="message" 
					class="block font-medium text-lg text-gray-700">Your Message</label>
					<textarea 
					id="message" 
					placeholder="Enter your message"
					name="message" 
					required 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					></textarea>
				</div>
				<div class="mb-4">
					<button type="submit" class="w-full px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-[#0b574fe2] ">Send</button>
				</div>
			</form>
			<div class=" flex justify-center items-center">
				<label for="recover" class="border-t-1 pt-4 text-center text-gray-700 text-sm text-nova  hover:underline px-9 hover:opacity-80 hover:cursor-pointer">Go back to Sign In</label>
			</div>
		</div>
	</div>
    <div><?php include "../includes/footer.html" ?></div>

    <script src="contacter_nous.js"></script>
</body>
</html>