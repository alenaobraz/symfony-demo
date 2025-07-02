CREATE DATABASE IF NOT EXISTS symfony_demo;
DROP USER IF EXISTS 'user'@'%';
CREATE USER 'symfony_demo_user'@'%' IDENTIFIED BY 'symfony_demo_password';
GRANT ALL ON *.* TO 'symfony_demo_user'@'%';
FLUSH PRIVILEGES;