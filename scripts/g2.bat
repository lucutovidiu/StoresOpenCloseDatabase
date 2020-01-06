CONNECT firstdb.fdb USER 'SYSDBA' PASSWORD 'masterkey';
SELECT * FROM sales_catalog;
INSERT INTO sales_catalog VALUES('005','Aluminium Wok', 'Chinese wok used for stir fry dishes');
exit;