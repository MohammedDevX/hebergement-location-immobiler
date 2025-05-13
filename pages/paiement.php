<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link href="../assets/css/paiementCSS/output.css" rel="stylesheet">
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
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-lg w-full my-5">
        <h2 class="text-4xl font-[Krylon]  text-gray-800 mb-4">Payment details</h2>
        <form class="font-[Grotesk]">
            <!-- Card Details -->
            <div class="mb-4 text-md">
                <label 
					for="card-number" 
					class="block text-lg font-medium text-gray-700">Card Details</label>
                <input 
					type="text" 
					id="card-number" 
					required 
					placeholder="XXXX XXXX XXXX XXXX" 
					class="mt-1 p-1.5 block w-full border-gray-300 rounded-md shadow-sm bg-[#13868623] focus:outline-none ring-0 focus:ring-2 focus:ring-[#005555] " 
					pattern="\d{4} \d{4} \d{4} \d{4}" 
					title="Card number must be in the format XXXX XXXX XXXX XXXX">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <input 
					type="text" 
					placeholder="MM / YY" 
					required 
					class="block p-1.5 w-full border-gray-300 rounded-md shadow-sm bg-[#13868623] focus:outline-none ring-0 focus:ring-2 focus:ring-[#005555] "
					pattern="^(0[1-9]|1[0-2]) \/ \d{2}$" 
			        title="Expiration date must be in the format MM / YY (e.g., 04 / 25)">
                <input 
					type="text" 
					placeholder="CVV" 
					required 
					class="block p-1.5 w-full border-gray-300 rounded-md shadow-sm bg-[#13868623] focus:outline-none ring-0 focus:ring-2 focus:ring-[#005555}] "
					pattern="^\d{3,4}$" 
			        title="CVV must be 3 or 4 digits (e.g., 123 or 1234)">
            </div>

            <!-- Booking Summary Section -->
        	<div class="bg-gray-50 p-4 rounded-lg shadow-inner">
        	    <div class="flex items-center mb-4">
    		    	<h3 class="text-3xl font-[Krylon] font-semibold w-full border-r-2 border-gray-600 text-left  text-gray-800">Booking Summary</h3>
					<img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1394247922445095202/original/d32a29bf-c3e8-4ed8-84ea-9a13c548fad4.jpeg?im_w=1200" alt="Booking Summary" class="w-72 h-38 ml-2 object-cover rounded-lg">
				</div>
        	    <div class="mb-4">
        	        <p class="text-sm text-gray-700">Property:</p>
        	        <p class="text-base font-medium text-gray-800">Dar Masha Sidi Mimoune Entire Riad </p>
        	    </div>
        	    <div class="mb-4">
        	        <p class="text-sm text-gray-700">Check-in:</p>
        	        <p class="text-base font-medium text-gray-800">May 14, 2025</p>
        	    </div>
        	    <div class="mb-4">
        	        <p class="text-sm text-gray-700">Check-out:</p>
        	        <p class="text-base font-medium text-gray-800">May 23, 2025</p>
        	    </div>
        	    <div class="mb-4">
        	        <p class="text-sm text-gray-700">Guests:</p>
        	        <p class="text-base font-medium text-gray-800">2 Adults, 1 Child</p>
        	    </div>
        	    <div class="border-t border-gray-200 pt-4">
        	        <div class="flex justify-between items-center mb-2">
        	            <span class="text-sm text-gray-700">Subtotal</span>
        	            <span class="text-sm font-medium text-gray-800">$500.00</span>
        	        </div>
        	        <div class="flex justify-between items-center mb-2">
        	            <span class="text-sm text-gray-700">Service Fee</span>
        	            <span class="text-sm font-medium text-gray-800">$50.00</span>
        	        </div>
        	        <div class="flex justify-between items-center text-lg font-semibold text-gray-800">
        	            <span>Total</span>
        	            <span>$580.00</span>
        	        </div>
        	        <p class="text-sm text-gray-500">+ applicable taxes</p>
        	    </div>
        	</div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-between">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-[#0b574fe2] ">Validate</button>
            </div>
        </form>
    </div>
</body>
</html>