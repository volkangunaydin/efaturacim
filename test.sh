#!/bin/bash

cd "$(dirname "$0")"
#Macbookda çalışması için yapıldı .bat dosyası çalışmıyor macbookda windows için ayarlanmış
./vendor/bin/phpunit
