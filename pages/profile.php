<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/94d90b0a3f.js" crossorigin="anonymous"></script>
</head>
<body class="font-[Grotesk] bg-[#fafafa]">
    <!-- PopUp edit Profile -->
    <input type="radio" id="editPrfl" name="Modal" class="peer/editPrfl hidden">
    <label for="hide" class="hidden peer-checked/editPrfl:block fixed inset-0 z-40 custom-overlay"></label>
    <div class="font-[Grotesk] hidden fixed inset-0 peer-checked/editPrfl:flex items-center justify-center min-h-screen z-50 overflow-y-auto pointer-events-none">
		<div class="bg-white shadow-md rounded-lg p-6 w-[90%] max-w-lg pointer-events-auto overflow-y-auto max-h-screen">
			<h1 class="font-[Krylon] text-4xl text-gray-800 mb-4 text-center">Modifie Your Info</h1>
			<p class="text-gray-600 text-sm mb-6 text-center">
				Enter the new informations.
			</p>
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
      Change
    </div>
    
    <!-- Hidden File Input -->
    <input
      type="file"
      id="profileImageInput"
      accept="image/*"
      class="hidden"
    />
  </label>


</div>
			<form action="/recover-password" method="POST" class="space-y-4">
				<div class="mb-6">
					<label 
					for="nom" 
					class="block font-medium text-lg text-gray-700">First Name</label>
					<input 
					type="text" 
					id="nom" 
					placeholder="Enter your First Name" 
					name="nom"
          pattern="[A-za-z]{3,30}"
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-6">
					<label 
					for="prenom" 
					class="block font-medium text-lg text-gray-700">Last Name</label>
					<input 
					type="text" 
					id="prenom" 
					placeholder="Enter your Last Name" 
					name="nom"
          pattern="[A-za-z]{3,30}"
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
					name="username" 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				
				<div class="mb-6">
					<label 
					for="phoneNumber" 
					class="block font-medium text-lg text-gray-700">Phone Number</label>
					<input 
					type="tel"
					id="phoneNumber" 
					placeholder="Enter your phone number"
          pattern="^\+?[1-9]\d{1,14}$"
					name="phoneNumber" 
					class="mt-1 text-md p-1.5 block w-full border-gray-300  bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
					>
				</div>
				<div class="mb-4">
					<button type="submit" class="w-full px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-opacity-60 hover:cursor-pointer">Confirm</button>
				</div>
			</form>
			<div class=" flex justify-center items-center">
				<label for="recover" class="border-t-1 pt-4 text-center text-gray-700 text-sm text-nova  hover:underline px-9 hover:opacity-80 hover:cursor-pointer">change your password</label>
			</div>
		</div>
	</div>
    <!-- PopUp edit Profile -->
    <div class="navbar-container">
        <?php include "../includes/navbar.html" ?>
    </div>
    <div class="container mx-auto px-4 py-8 my-30">
     <div class="grid grid-cols-1 md:grid-cols-[300px_1fr] gap-8">
      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- User Profile Card -->
        <div class="rounded-xl shadow-sm bg-white p-6">
          <div class="flex flex-col items-center text-center">
            <img
              src="https://placehold.co/80x80"
              alt="Profile Picture"
              class="size-20 rounded-full object-cover mb-4"
            />
            <h2 class="text-xl font-semibold">Prenom Nom</h2>
            <span class="text-sm mb-2 text-gray-500">Guest</span>
        <!---------------------------------------->
            <label for="editPrfl" class="bg-[#005555] text-white px-4 py-2 rounded-md hover:bg-[#0b574fe2] cursor-pointer">
                Edit Profile
            </label>
        </div>
          <div class="flex justify-center gap-6 mt-6">
            <div class="text-center">
              <span class="block text-lg font-bold">3</span>
              <span class="text-xs text-gray-500">Reviews</span>
            </div>
            <div class="text-center">
              <span class="block text-lg font-bold">5</span>
              <span class="text-xs text-gray-500">Months on platform</span>
            </div>
          </div>
        </div>

        <!-- Confirmed Information Card -->
        <div class="rounded-xl shadow-md bg-white p-6">
          <h2 class="text-lg font-semibold mb-4">Prenom Nom's confirmed information</h2>
          <div class="space-y-3">
            <div class="flex items-center gap-2">
              <!-- Chek iccon -->
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 text-green-500">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span class="text-sm">Identity</span>
            </div>
            <div class="flex items-center gap-2">
              <!-- Check icon -->
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 text-green-500">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span class="text-sm">Phone number</span>
            </div>
          </div>
        </div>

      </div>

      <!-- Main Content -->
      <div class="space-y-10">
        <!-- About Section -->
        <section>
          <h1 class="text-2xl font-bold mb-6">About Prenom Nom</h1>
          <div class="mb-4">
            <div class="flex items-center gap-2 mb-2">
              <!-- Languages icon -->
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500">
                <path d="m5 8 6 6"></path>
                <path d="m4 14 6-6 2-3"></path>
                <path d="M2 5h12"></path>
                <path d="M7 2h1"></path>
                <path d="m22 22-5-10-5 10"></path>
                <path d="M14 18h6"></path>
              </svg>
              <span class="text-sm">Speaks:</span>
            </div>
          </div>
          <p class="text-gray-600">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil sequi velit saepe. Corrupti, perferendis
            deleniti. Voluptatum ratione autem aspernatur, dolorum beatae maiores quasi, officia architecto, amet
            reprehenderit repudiandae deleniti quidem.
          </p>
        </section>


        <!-- Reviews Section -->
        <section>
          <h2 class="text-xl font-semibold mb-4">What Hosts are saying about Prenom Nom</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div class="rounded-xl shadow-md bg-white p-4">
              <p class="text-sm mb-4">
                Prenom was a very friendly and formal host, he left everything organized, very good communication
              </p>
              <div class="flex items-center gap-3">
                <img
                  src="https://placehold.co/40x40"
                  alt="Reviewer"
                  class="size-10 rounded-full object-cover"
                />
                <div>
                  <span class="block text-sm font-medium">Prenom Nom</span>
                  <span class="text-xs text-gray-500">April 2025</span>
                </div>
              </div>
            </div>
            <div class="rounded-xl shadow-md bg-white p-4">
              <p class="text-sm mb-4">
                Prenom was a very friendly and formal host, he left everything organized, very good communication
              </p>
              <div class="flex items-center gap-3">
                <img
                  src="https://placehold.co/40x40"
                  alt="Reviewer"
                  class="size-10 rounded-full object-cover"
                />
                <div>
                  <span class="block text-sm font-medium">Prenom Nom</span>
                  <span class="text-xs text-gray-500">April 2025</span>
                </div>
              </div>
            </div>
          </div>
          <button class="text-sm font-semibold hover:underline">
            Show all <span>Number</span> reviews
          </button>
        </section>
      </div>
    </div>
    </div>

    <div>
      <?php include "../includes/footer.html" ?> 
    </div>

    <script src="../assets/js/profile.js"></script>
</body>
</html>