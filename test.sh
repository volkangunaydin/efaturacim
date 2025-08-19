#!/bin/bash

# Change directory to the location of this script to ensure paths are correct.
cd "$(dirname "$0")"

# Check if a parameter was passed
if [ "$1" = "ServisTest" ]; then
    echo "Running ServisTest only..."
    ./vendor/bin/phpunit tests/EfaturacimServisTest/ServisTest.php
else
    echo "Running all tests..."
    ./vendor/bin/phpunit
fi

# Pause the console to see the output before it closes.
read -p "Press any key to continue..."
