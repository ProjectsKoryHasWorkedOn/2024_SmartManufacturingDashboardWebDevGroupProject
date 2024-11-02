GRANT SELECT, INSERT, UPDATE, DELETE, ALTER ON factory_db.* TO 'localuser'@'localhost';
GRANT FILE ON *.* TO 'localuser'@'localhost';
SHOW GRANTS FOR 'localuser'@'localhost';