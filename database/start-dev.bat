@echo off 
title Laravel + Vite Dev Server 
echo Menjalankan Laravel server... 
start cmd /k "cd /d C:\xampp\htdocs\marketplace-persikindo && php artisan serve" 
timeout /t 2 
echo Menjalankan Vite (npm run dev)... 
start cmd /k "cd /d C:\xampp\htdocs\marketplace-persikindo && npm run dev" 
exit 
