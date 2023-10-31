# Tests

## Docker ubuntu

```bash
docker pull ubuntu:latest
docker run -it --rm ubuntu:latest
```

```bash
cd /home
apt update
apt install -y git software-properties-common
apt install -y flac vorbis-tools
add-apt-repository ppa:ondrej/php
apt update
apt upgrade -y
apt -y install php8.2-fpm php8.2-curl php8.2-xml php8.2-zip
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

```bash
git clone -b develop https://github.com/kiwilan/php-audio.git
cd php-audio
/home/composer.phar install
/home/composer.phar test
```

## Docker windows

```bash
docker pull mcr.microsoft.com/windows/server
docker run -it --rm mcr.microsoft.com/windows/servercore:ltsc2019
```

```bash
powershell
```

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://get.scoop.sh'))
scoop install git
scoop install php
scoop install composer
```

```bash
git clone -b develop
cd php-audio
composer install
composer test
```
