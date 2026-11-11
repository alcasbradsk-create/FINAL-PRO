@echo off
title ISPSC Faculty Profiling System Server

rem Use XAMPP's PHP if it is installed, otherwise whatever "php" is on PATH.
set "PHP_EXE=C:\xampp\php\php.exe"
if not exist "%PHP_EXE%" set "PHP_EXE=php"

echo ========================================================
echo Starting ISPSC Faculty Profiling System...
echo URL: http://localhost:8000/
echo.
echo The server only accepts connections from this computer.
echo Make sure MySQL is running (XAMPP Control Panel).
echo Press Ctrl+C to stop the server.
echo ========================================================
"%PHP_EXE%" -S 127.0.0.1:8000 -t "%~dp0." "%~dp0router.php"
pause
