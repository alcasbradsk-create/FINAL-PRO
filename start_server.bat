@echo off
title ISPSC Faculty Profiling System Server
echo ========================================================
echo Starting ISPSC Faculty Profiling System...
echo URL: http://localhost:8000/
echo.
echo Press Ctrl+C to stop the server.
echo ========================================================
C:\xampp\php\php.exe -S 0.0.0.0:8000 -t "%~dp0"
pause
