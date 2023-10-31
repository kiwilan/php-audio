# Tests

## Docker ubuntu

```bash
docker pull ubuntu:latest
docker run -it --rm ubuntu:latest
```

```bash
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
composer install
composer test
```
