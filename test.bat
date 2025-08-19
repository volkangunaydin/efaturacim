@echo off
REM Change directory to the location of this script to ensure paths are correct.
cd /d "%~dp0"

REM Check if a parameter was passed
if "%1"=="ServisTest" (
    echo Running ServisTest only...
    vendor\bin\phpunit tests\EfaturacimServisTest\ServisTest.php
) else (
    echo Running all tests...
    vendor\bin\phpunit
)

REM Pause the console to see the output before it closes.
pause
