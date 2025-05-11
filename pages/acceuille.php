<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		.background {
			height: 1000px;
			background-image: url('../assets/images/Bg.jpg');
			background-repeat: no-repeat;
			background-position: center;
			background-size: cover;
		}

		@keyframes moveUp {
		from {
			transform: translateY(100px); /* Start 100px below */
			opacity: 0; /* Start invisible */
		}
		to {
			transform: translateY(0); /* End at its original position */
			opacity: 1; /* Fully visible */
		}
		}

		.search-bar {
			animation: moveUp 1s ease-out; /* Apply the animation */
		}
	</style>
	<script src="https://kit.fontawesome.com/5300af1428.js" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="h-[2000px]">
	<div class="background">
		<!-- NavBar -->
            <div class="navbar-container">
                <?php include "../includes/navbar.html" ?>
            </div>
		<!-- NavBar -->
		<!-- Search Bar -->
		<div class="mx-auto font-[Krylon] text-md max-w-7xl sm:px-6 lg:px-8 search-bar">
	    	<div class="relative isolate overflow-hidde px-6 py-20 text-center sm:px-16">

	    	    <form action="/search">
	    	        <label
	    	            class="mx-auto mt-[300px]  relative bg-white  max-w-2xl flex flex-col md:flex-row items-center justify-center py-2 px-2 rounded-2xl gap-2  focus-within:border-gray-300"
	    	            for="search-bar">

	    	            <input id="search-bar" placeholder="your keyword here" name="q"
	    	                class="px-6 py-2 w-full rounded-md flex-1 outline-none bg-white" required="">
	    	            <button type="submit"
	    	                class="w-full md:w-auto px-6 py-3 bg-[#005555] text-white fill-white active:scale-95 duration-100 border will-change-transform overflow-hidden relative rounded-xl transition-all">
	    	                <div class="flex items-center transition-all ">
	    	                    <span class="text-lg font-semibold whitespace-nowrap truncate mx-auto">
	    	                        Search
	    	                    </span>
	    	                </div>
	    	            </button>
	    	        </label>
	    	    </form>
	    	</div>
		</div>
		<!-- Search Bar -->
	</div>


	<div class="bg-white w-full font-[Grotesk]">
		<div class="mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:max-w-8xl">
		<h2 class="text-6xl pl-10  font-[Krylon]  tracking-tight text-gray-900 ">Proprietes</h2>

        <div class=" mt-10 grid px- grid-cols-1  gap-x-6 gap-y-10 sm:grid-cols-1 xl:grid-cols-4 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5">
			<!-- Start Propr... -->
			<div class="group relative ">
			    <img src="../assets/images/Propriete1.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			    <div class="mt-4 flex justify-between">
			<div>
				<h3 class="text-lg font-bold text-gray-700">
				<a href="detaille.php">
				    <span aria-hidden="true" class="absolute inset-0"></span>
                    Sedella, Spain
				</a>
				</h3>
				<p class="mt-1 text-md text-gray-500">Hosted By Manon</p>
				<p class="text-sm font-bold text-gray-900">MAD 1,220 night</p>
			</div>
    </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr2.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Antequera, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Peter & Monique</p>
				  <p class="text-sm font-bold text-gray-900">MAD1,159 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr3.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Lagunas, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Katrina</p>
				  <p class="text-sm font-bold text-gray-900">MAD674 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr4.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Boulemane, Morocco
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Houda</p>
				  <p class="text-sm font-bold text-gray-900">MAD373 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr5.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Fes, Morocco
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Catherine</p>
				  <p class="text-sm font-bold text-gray-900">MAD561 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative ">
			  <img src="../assets/images/Propriete1.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Sedella, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Manon</p>
				  <p class="text-sm font-bold text-gray-900">MAD 1,220 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr2.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Antequera, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Peter & Monique</p>
				  <p class="text-sm font-bold text-gray-900">MAD1,159 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr3.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Lagunas, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Katrina</p>
				  <p class="text-sm font-bold text-gray-900">MAD674 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr4.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Boulemane, Morocco
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Houda</p>
				  <p class="text-sm font-bold text-gray-900">MAD373 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr5.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Fes, Morocco
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Catherine</p>
				  <p class="text-sm font-bold text-gray-900">MAD561 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative ">
			  <img src="../assets/images/Propriete1.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Sedella, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Manon</p>
				  <p class="text-sm font-bold text-gray-900">MAD 1,220 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr2.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Antequera, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Peter & Monique</p>
				  <p class="text-sm font-bold text-gray-900">MAD1,159 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr3.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Lagunas, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Katrina</p>
				  <p class="text-sm font-bold text-gray-900">MAD674 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr4.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Boulemane, Morocco
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Houda</p>
				  <p class="text-sm font-bold text-gray-900">MAD373 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr5.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Fes, Morocco
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Catherine</p>
				  <p class="text-sm font-bold text-gray-900">MAD561 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative ">
			  <img src="../assets/images/Propriete1.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Sedella, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Manon</p>
				  <p class="text-sm font-bold text-gray-900">MAD 1,220 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr2.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Antequera, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Peter & Monique</p>
				  <p class="text-sm font-bold text-gray-900">MAD1,159 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr3.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Lagunas, Spain
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Katrina</p>
				  <p class="text-sm font-bold text-gray-900">MAD674 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr4.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Boulemane, Morocco
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Houda</p>
				  <p class="text-sm font-bold text-gray-900">MAD373 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			<!-- Start Propr... -->
			<div class="group relative">
			  <img src="../assets/images/Propr5.png"  class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300 ">
			  <div class="mt-4 flex justify-between">
				<div>
				  <h3 class="text-lg font-bold text-gray-700">
					<a href="detaille.php">
					  <span aria-hidden="true" class="absolute inset-0"></span>
					  Fes, Morocco
					</a>
				  </h3>
				  <p class="mt-1 text-md text-gray-500">Hosted By Catherine</p>
				  <p class="text-sm font-bold text-gray-900">MAD561 night</p>
				</div>
			  </div>
			</div>
			<!-- End Propr... -->
			
		  </div>
		</div>
	  </div>

	<!-- Footer -->
	<div><?php include "../includes/footer.html" ?></div>
	<!-- Footer -->
	 <script src="../assets/js/script.js"></script>
</body>
</html>