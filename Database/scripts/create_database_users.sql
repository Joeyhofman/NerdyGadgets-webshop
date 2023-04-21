-- create visitor user
CREATE USER IF NOT EXISTS 'visitor'@'localhost' IDENTIFIED BY 'bMMNSqCd';
GRANT SELECT ON Stockitems TO visitor;
GRANT SELECT ON Stockgroups TO visitor;
GRANT SELECT ON StockitemImages TO visitor; 
GRANT SELECT, UPDATE ON stockitemholdings TO visitor;
GRANT SELECT, INSERT ON orderlines TO visitor;
GRANT SELECT, INSERT ON orders TO visitor;
GRANT SELECT ON coldroomtemperatures TO visitor;
GRANT SELECT ON stockitems_archive TO visitor;

-- create customer user
CREATE USER IF NOT EXISTS 'customer'@'localhost' IDENTIFIED BY 'RAgFgCH0';
GRANT SELECT ON Stockitems TO customer;
GRANT SELECT ON Stockgroups TO customer;
GRANT SELECT ON StockitemImages TO customer; 
GRANT SELECT, UPDATE ON stockitemholdings TO customer;
GRANT SELECT, INSERT ON orderlines TO customer;
GRANT SELECT, INSERT ON orders TO customer;
GRANT SELECT ON coldroomtemperatures TO customer;
GRANT SELECT ON stockitems_archive TO customer;
GRANT SELECT, UPDATE, INSERT ON Customers TO customer;

-- create backoffice user
CREATE USER IF NOT EXISTS 'backoffice'@'localhost' IDENTIFIED BY ' LDladw9r';
GRANT SELECT, UPDATE, INSERT, DELETE ON Customers TO backoffice;
GRANT SELECT ON orderliness  TO backoffice;
GRANT SELECT ON Orders TO backoffice;
GRANT SELECT ON stockitems TO backoffice;
GRANT SELECT ON Roles TO backoffice;
GRANT SELECT, UPDATE  ON People TO backoffice;
GRANT SELECT, INSERT ON Cities TO backoffice;

-- create temperaturesensor user
CREATE USER IF NOT EXSISTS 'temperaturesensor'@'localhost' IDENTIFIED BY 'CmdwhSY7';
GRANT SELECT, UPDATE, INSERT, DELETE ON coldroomtemperatures TO 'temperaturesensor';
GRANT SELECT, UPDATE, INSERT, DELETE ON stockitems TO 'temperaturesensor';
GRANT SELECT, UPDATE, INSERT, DELETE ON stockitems_archive TO 'temperaturesensor';
