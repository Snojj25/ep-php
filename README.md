# ep-php

## Setup Apache server

```bash
sudo mkdir /etc/apache2/ssl

# Copy the certificates from the /certs folder
sudo cp -r certs/*.{pem,crt,key} /etc/apache2/ssl

sudo a2enmod ssl

```

Copy the contents of the two .conf files in the `apache-conf` folder into `/etc/apache2/sites-available/default-ssl.conf` and `/etc/apache2/sites-available/000-default-ssl.conf`. You will need to update the values of `/home/<USER>/.../ep-seminarska` to where the actual project is located.

```bash
sudo a2ensite default-ssl.conf

sudo service apache2 restart
```

## Setup MySQL Docker

Pull and run the docker container.

```bash
# pull the latest image
docker pull mysql:latest

# Run the container
docker run -d \
  --name ecommerce-sql \
  -e MYSQL_ROOT_PASSWORD=password \
  -e MYSQL_DATABASE=ecommerce \
  -e MYSQL_USER=php-server \
  -e MYSQL_PASSWORD=password \
  -p 3306:3306 \
  mysql:latest

```

Copy and execute the SQL file.

```bash
# Copy the local file into the container
docker cp ./sql/ecommerce.sql ecommerce-sql:/ecommerce.sql

# Execute the file inside container
docker exec ecommerce-sql mysql -uroot -ppassword ecommerce -e "source /ecommerce.sql"
```

You can test that it works by viewing the container:

```bash
# exec into the container
docker exec -it ecommerce-sql mysql -uroot -ppassword

# Once inside MySQL, you can run any sql command:
SHOW DATABASES;

USE ecommerce;

SHOW TABLES;

```

## Users and Client Certificates

Emails and passwords:

```
customer@example.com : password
seller@example.com : password
admin@example.com : password
```

There are two certificates in the `/certs` folder, one for bob and one for ana that you can import into your browser. To login as admin you need Ana's certificate and to login as a Seller you need Bob's. The password to decrypt the certificates are simply "ep".


