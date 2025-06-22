<?php
session_start();

// Vérifier si un message d'erreur existe dans la session
$hasNotification = false;
$type = "";
$message = "";

if (isset($_SESSION['notification'])) {
    $hasNotification = true;
    $type = $_SESSION['notification']['type'];
    $message = $_SESSION['notification']['message'];
    
    // Supprimer la notification après l'avoir récupérée
    unset($_SESSION['notification']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password</title>
    <script src="https://kit.fontawesome.com/5300af1428.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* Styles pour les notifications */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white;
            max-width: 400px;
            width: 350px;
            transform: translateY(100px);
            transition: transform 0.3s ease-in-out;
        }
        
        .notification.show {
            transform: translateY(0);
        }
        
        .notification-success {
            background-color: #10b981;
        }
        
        .notification-error {
            background-color: #ef4444;
        }
        
        .notification-icon {
            margin-right: 12px;
            font-size: 20px;
        }
        
        .notification-message {
            flex-grow: 1;
            font-size: 14px;
        }
        
        .notification-close {
            margin-left: 12px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        
        .notification-close:hover {
            opacity: 1;
        }
        
        /* Responsive pour petits écrans */
        @media (max-width: 480px) {
            .notification {
                width: calc(100% - 40px);
                right: 20px;
                left: 20px;
            }
        }
    </style>
</head>
<body class="bg-[#fafafa]">
    <?php if ($hasNotification): ?>
    <!-- Notification -->
    <div id="notification" class="notification notification-<?php echo $type; ?>">
        <div class="notification-icon">
            <?php if ($type === 'success'): ?>
                <i class="fas fa-check-circle"></i>
            <?php else: ?>
                <i class="fas fa-exclamation-circle"></i>
            <?php endif; ?>
        </div>
        <div class="notification-message"><?php echo $message; ?></div>
        <div class="notification-close" onclick="closeNotification()">
            <i class="fas fa-times"></i>
        </div>
    </div>
    <?php endif; ?>

    <div class="navbar-container">
        <?php include "../includes/navbar.php" ?>
    </div>
    <div class="font-[Grotesk] flex mt-20 items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-6 w-[90%] max-w-lg">
            <h1 class="Krylon text-4xl text-gray-800 mb-4 text-center">Recover Your Password</h1>
            <p class="text-gray-600 text-sm mb-6 text-center">
                Enter your new password.
            </p>
            <form action="resetPassword.php" method="POST" class="space-y-4">
                <input type="hidden" name="email" value="<?= $_GET['email'] ?? '' ?>">
                <div class="mb-6">
                    <label 
                    for="password" 
                    class="block font-medium text-lg text-gray-700">New Password</label>
                    <input 
                    type="password" 
                    id="password" 
                    placeholder="Enter a new password" 
                    name="password"
                    required 
                    class="mt-1 text-md p-1.5 block w-full border-gray-300 bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                    >
                </div>
                <div class="mb-6">
                    <label 
                    for="Confrm_password" 
                    class="block font-medium text-lg text-gray-700">Confirm Password</label>
                    <input 
                    type="password" 
                    id="Confrm_password" 
                    placeholder="Re-Enter the password"
                    name="Confrm_password" 
                    required 
                    class="mt-1 text-md p-1.5 block w-full border-gray-300 bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                    >
                </div>
                <div class="mb-4">
                    <button type="submit" class="w-full px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-[#0b574fe2]">Validate</button>
                </div>
            </form>
            <div class="flex justify-center items-center">
                <a href="acceuille.php" class="border-t-1 pt-4 text-center text-gray-700 text-sm text-nova hover:underline px-9 hover:opacity-80 hover:cursor-pointer">Go back to Sign In</a>
            </div>
        </div>
    </div>

    <!-- Script pour les notifications -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const notification = document.getElementById('notification');
        
        if (notification) {
            // Afficher la notification avec animation
            setTimeout(function() {
                notification.classList.add('show');
            }, 100);
            
            // Fermer automatiquement après 5 secondes
            setTimeout(function() {
                closeNotification();
            }, 5000);
        }
    });
    
    function closeNotification() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.classList.remove('show');
            
            // Supprimer l'élément après la fin de l'animation
            setTimeout(function() {
                notification.remove();
            }, 300);
        }
    }
    </script>
</body>
</html>
