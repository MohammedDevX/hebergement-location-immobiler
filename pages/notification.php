<?php
// Cette fonction génère le HTML pour les notifications
function generateNotification($message, $type = 'success') {
    $bgColor = $type === 'success' ? 'bg-green-500' : 'bg-red-500';
    $icon = $type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    return "
    <div id='notification' class='fixed top-5 right-5 z-50 flex items-center p-4 mb-4 {$bgColor} text-white rounded-lg shadow-lg transition-all duration-300 transform translate-x-0' role='alert'>
        <div class='inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg'>
            <i class='fas {$icon}'></i>
        </div>
        <div class='ml-3 text-sm font-normal'>{$message}</div>
        <button type='button' class='ml-auto -mx-1.5 -my-1.5 text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex h-8 w-8 hover:bg-opacity-25' onclick='dismissNotification()'>
            <span class='sr-only'>Fermer</span>
            <i class='fas fa-times'></i>
        </button>
    </div>";
}
?>