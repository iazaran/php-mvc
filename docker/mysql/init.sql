SET @password = '${MYSQL_PASSWORD}';

ALTER USER 'mvc_user'@'%' IDENTIFIED BY @password;
FLUSH PRIVILEGES;
