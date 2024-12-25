<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title><?= $pageTitle ?? 'JS-EP Store' ?></title>  
    
    <!-- Tailwind CSS via CDN -->  
    <script src="https://cdn.tailwindcss.com"></script>  
    
    <!-- Optional: Configure Tailwind -->  
    <script>  
        tailwind.config = {  
            theme: {  
                extend: {  
                    colors: {  
                        primary: '#3b82f6',  // blue-500  
                    }  
                }  
            }  
        }  
    </script>  

    <!-- Optional: Add custom styles -->  
    <style type="text/tailwindcss">  
        @layer components {  
            .nav-link {  
                @apply px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200;  
            }  
            .nav-link-default {  
                @apply text-gray-600 hover:text-primary hover:bg-gray-50;  
            }  
            .nav-link-active {  
                @apply text-primary bg-blue-50;  
            }  
        }  
    </style>  
</head>  
<body>