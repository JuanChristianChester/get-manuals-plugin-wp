DROP TABLE IF EXISTS tblJoin;
DROP TABLE IF EXISTS tblManuals;
DROP TABLE IF EXISTS tblProduct;
DROP TABLE IF EXISTS tblDate;

CREATE TABLE tblDate (
  DateID varchar(6) PRIMARY KEY,
  Date date
);

CREATE TABLE tblProduct (
  ProductID VARCHAR(30) PRIMARY KEY,
  ProductName VARCHAR(255),
  ProductDescription TEXT
);

CREATE TABLE tblManuals (
  ManualID varchar(15) PRIMARY KEY,
  filename VARCHAR(255),
  pdf BLOB
);

CREATE TABLE tblJoin (
  DateID VARCHAR(6),
  ProductID VARCHAR(30),
  ManualID varchar(15),
  PRIMARY KEY (DateID, ProductID)
);

/* Two dates from the past 20 years */
INSERT INTO tblDate (DateID, Date)
VALUES
  ('010501', '2001-05-01'),
  ('011201', '2001-12-01'),
  ('020501', '2002-05-01'),
  ('021201', '2002-12-01'),
  ('030501', '2003-05-01'),
  ('031201', '2003-12-01'),
  ('040501', '2004-05-01'),
  ('041201', '2004-12-01'),
  ('050501', '2005-05-01'),
  ('051201', '2005-12-01'),
  ('060501', '2006-05-01'),
  ('061201', '2006-12-01'),
  ('070501', '2007-05-01'),
  ('071201', '2007-12-01'),
  ('080501', '2008-05-01'),
  ('081201', '2008-12-01'),
  ('090501', '2009-05-01'),
  ('091201', '2009-12-01'),
  ('100501', '2010-05-01'),
  ('101201', '2010-12-01'),
  ('110501', '2011-05-01'),
  ('111201', '2011-12-01'),
  ('120501', '2012-05-01'),
  ('121201', '2012-12-01'),
  ('130501', '2013-05-01'),
  ('131201', '2013-12-01'),
  ('140501', '2014-05-01'),
  ('141201', '2014-12-01'),
  ('150501', '2015-05-01'),
  ('151201', '2015-12-01'),
  ('160501', '2016-05-01'),
  ('161201', '2016-12-01'),
  ('170501', '2017-05-01'),
  ('171201', '2017-12-01'),
  ('180501', '2018-05-01'),
  ('181201', '2018-12-01'),
  ('190501', '2019-05-01'),
  ('191201', '2019-12-01'),
  ('200501', '2020-05-01'),
  ('201201', '2020-12-01');

INSERT INTO tblProduct (ProductID, ProductName, ProductDescription)
VALUES
  ('C08-001-001-01-1-1', 'Air 5 Oxygen & Carbon Dioxide', '0-100% O2 + 0-5% CO2 ambient monitor with diffusion sensor (surface mount)'),
  ('C05-35-02', 'Air 5 Hypobaric Oxygen & Pressure', '0-40% O2 & 10-1300mbar (A) (Panel Mount)'),
  ('SAT-35-14', 'Air 5 Converter', 'USB to RS485 Converter');


INSERT INTO tblManuals (ManualID, filename, pdf)
VALUES ('MAN001', 'Man1.pdf', LOAD_FILE('/public_html/pdfs.Man1.pdf')),
	   ('MAN002', 'Man2.pdf', LOAD_FILE('/public_html/pdfs.Man2.pdf'));


INSERT INTO tblJoin (DateID, ProductID, ManualID)
VALUES
  ('010501', 'C08-001-001-01-1-1', 'MAN-0001'),
  ('020501', 'C05-35-02', 'MAN-0002');