@echo off
REM Change directory to the location of this script to ensure paths are correct.
cd /d "%~dp0"

REM Run PHPUnit tests.
vendor\bin\phpunit

REM Pause the console to see the output before it closes.
pause
