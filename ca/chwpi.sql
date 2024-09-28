-- MySQL dump 8.21

--

-- Host: localhost    Database: chwpi

---------------------------------------------------------

-- Server version	3.23.49-log



--

-- Table structure for table 'accts'

--



CREATE TABLE accts (

  acct_code varchar(16) binary NOT NULL default '',

  acct_desc varchar(250) binary default NULL,

  acct_type enum('as','li','eq','in','cs','ex','mi','me') NOT NULL default 'as',

  acct_preset enum('t','f') default 'f',

  acct_log timestamp(14) NOT NULL,

  UNIQUE KEY acct_code (acct_code),

  KEY acct_desc (acct_desc)

) TYPE=InnoDB;



INSERT INTO accts (acct_code, acct_desc, acct_type) VALUES ('10000','Cash Account','as');

INSERT INTO accts (acct_code, acct_desc, acct_type) VALUES ('11000','Account Receivable','as');

INSERT INTO accts (acct_code, acct_desc, acct_type) VALUES ('21000','Account Payable','li');

INSERT INTO accts (acct_code, acct_desc, acct_type) VALUES ('12000','Inventory','as');

INSERT INTO accts (acct_code, acct_desc, acct_type) VALUES ('40000','Sales','in');

INSERT INTO accts (acct_code, acct_desc, acct_type) VALUES ('52000','Cost of Goods','cs');



--

-- Table structure for table 'custs'

--



CREATE TABLE custs (

  cust_code varchar(16) binary NOT NULL default '',

  cust_name varchar(32) binary NOT NULL default '',

  cust_addr1 varchar(128) binary default NULL,

  cust_addr2 varchar(128) binary default NULL,

  cust_addr3 varchar(128) binary default NULL,

  cust_city varchar(128) binary default NULL,

  cust_state varchar(32) binary default NULL,

  cust_country varchar(32) binary default NULL,

  cust_zip varchar(32) binary default NULL,

  cust_tel varchar(32) binary default NULL,

  cust_fax varchar(32) binary default NULL,

  cust_balance decimal(15,5) default '0.00000',

  cust_sls_acct varchar(16) binary default NULL,

  cust_ar_acct varchar(16) binary default NULL,

  cust_log timestamp(14) NOT NULL,

  UNIQUE KEY cust_code (cust_code),

  KEY cust_sls_acct (cust_sls_acct),

  KEY cust_ar_acct (cust_ar_acct)

) TYPE=InnoDB;



--

-- Table structure for table 'disburdtls'

--



CREATE TABLE disburdtls (

  disburdtl_id int(10) unsigned NOT NULL auto_increment,

  disburdtl_disbur_id int(10) unsigned NOT NULL default '0',

  disburdtl_vend_inv varchar(16) NOT NULL default '',

  disburdtl_ref_id int(10) unsigned default '0',

  disburdtl_acct_code varchar(16) binary NOT NULL default '',

  disburdtl_amt decimal(15,5) NOT NULL default '0.00000',

  disburdtl_desc varchar(250) binary default NULL,

  PRIMARY KEY  (disburdtl_id),

  UNIQUE KEY disburdtl_id (disburdtl_id),

  KEY disburdtl_id_2 (disburdtl_id),

  KEY disburdtl_disbur_id (disburdtl_disbur_id,disburdtl_acct_code),

  KEY disburdtl_ref_id (disburdtl_ref_id),

  KEY disburdtl_cust_inv (disburdtl_vend_inv)

) TYPE=InnoDB;



--

-- Table structure for table 'disburs'

--



CREATE TABLE disburs (

  disbur_id int(10) unsigned NOT NULL auto_increment,

  disbur_date date default NULL,

  disbur_vend_code varchar(16) binary NOT NULL default '',

  disbur_vend_inv varchar(16) binary default NULL,

  disbur_po_no varchar(16) binary NOT NULL default '',

  disbur_ref_id int(11) NOT NULL default '0',

  disbur_check_no varchar(16) binary default NULL,

  disbur_amt decimal(15,5) default '0.00000',

  disbur_desc varchar(250) binary default NULL,

  disbur_user_code varchar(16) binary NOT NULL default '',

  disbur_log timestamp(14) NOT NULL,

  UNIQUE KEY disbur_id (disbur_id),

  KEY disbur_date (disbur_date),

  KEY disbur_vend_code (disbur_vend_code),
["purch_comnt"]
  KEY disbur_user_code (disbur_user_code)

) TYPE=InnoDB;



--

-- Table structure for table 'invtrxs'

--



CREATE TABLE invtrxs (

  invtrx_id int(10) unsigned NOT NULL auto_increment,

  invtrx_ref_code varchar(16) default NULL,

  invtrx_item_code varchar(16) binary default NULL,

  invtrx_styl_code varchar(16) binary default NULL,

  invtrx_acct_code varchar(16) NOT NULL default '',

  invtrx_date date default NULL,

  invtrx_po_no varchar(16) binary NOT NULL default '',

  invtrx_cost decimal(15,5) default '0.00000',

  invtrx_qty decimal(15,5) default '0.00000',

  invtrx_type enum('r','a','s') NOT NULL default 'r',

  invtrx_desc varchar(250) binary default NULL,

  item_log timestamp(14) NOT NULL,

  UNIQUE KEY invtrx_id (invtrx_id),

  KEY invtrx_ref_id (invtrx_ref_code),

  KEY invtrx_item_code (invtrx_item_code),

  KEY invtrx_styl_code (invtrx_styl_code),

  KEY invtrx_date (invtrx_date),

  KEY invtrx_po_no (invtrx_po_no),

  KEY invtrx_acct_code (invtrx_acct_code)

) TYPE=InnoDB;



--

-- Table structure for table 'items'

--



CREATE TABLE items (

  item_code varchar(16) binary NOT NULL default '',

  item_desc varchar(250) binary default NULL,

  item_unit varchar(16) default NULL,

  item_qty_onhnd decimal(15,5) default '0.00000',

  item_ave_cost decimal(15,5) default '0.00000',

  item_inv_acct varchar(16) binary default NULL,

  item_exp_acct varchar(16) binary default NULL,

  item_sls_acct varchar(16) binary default NULL,

  item_adjex_acct varchar(16) binary default NULL,

  item_adjin_acct varchar(16) binary default NULL,

  item_log timestamp(14) NOT NULL,

  UNIQUE KEY item_code (item_code),

  KEY item_inv_acct (item_inv_acct),

  KEY item_exp_acct (item_exp_acct),

  KEY item_sls_acct (item_sls_acct),

  KEY item_adjex_acct (item_adjex_acct),

  KEY item_adjin_acct (item_adjin_acct),

  KEY item_desc (item_desc)

) TYPE=InnoDB;



--

-- Table structure for table 'jrnltrxs'

--



CREATE TABLE jrnltrxs (

  jrnltrx_id int(10) unsigned NOT NULL auto_increment,

  jrnltrx_ref_id int(10) unsigned NOT NULL default '0',

  jrnltrx_date date NOT NULL default '0000-00-00',

  jrnltrx_acct_code varchar(16) binary NOT NULL default '',

  jrnltrx_user_code varchar(16) binary NOT NULL default '',

  jrnltrx_type enum('r','p','g','i','d','c') default 'r',

  jrnltrx_dc enum('d','c') NOT NULL default 'd',

  jrnltrx_amt decimal(15,5) default '0.00000',

  jrnltrx_log timestamp(14) NOT NULL,

  UNIQUE KEY jrnldtl_id (jrnltrx_id),

  KEY jrnltrx_ref_id (jrnltrx_ref_id),

  KEY jrnltrx_acct_code (jrnltrx_acct_code),

  KEY jrnltrx_user_code (jrnltrx_user_code),

  KEY jrnltrx_date (jrnltrx_date)

) TYPE=InnoDB;



--

-- Table structure for table 'purchs'

--



CREATE TABLE purchs (

  purch_id int(10) unsigned NOT NULL auto_increment,

  purch_user_code varchar(16) binary default NULL,

  purch_vend_code varchar(16) binary default NULL,

  purch_vend_inv varchar(16) NOT NULL default '',

  purch_amt decimal(15,5) default NULL,

  purch_date date default NULL,

  purch_tax_amt decimal(15,5) default '0.00000',

  purch_freight_amt decimal(15,5) default '0.00000',

  purch_shipvia varchar(16) default NULL,

  purch_fin enum('t','f') default 'f',

  purch_comnt varchar(250) binary default NULL,

  purch_log timestamp(14) NOT NULL,

  UNIQUE KEY purch_id (purch_id),

  KEY purch_user_code (purch_user_code),

  KEY purch_vend_code (purch_vend_code),

  KEY purch_date (purch_date)

) TYPE=InnoDB;



--

-- Table structure for table 'purdtls'

--



CREATE TABLE purdtls (

  purdtl_id int(10) unsigned NOT NULL auto_increment,

  purdtl_purch_id int(10) unsigned NOT NULL default '0',

  purdtl_po_no varchar(16) binary NOT NULL default '',

  purdtl_item_code varchar(16) binary default NULL,

  purdtl_acct_code varchar(16) binary NOT NULL default '0',

  purdtl_qty decimal(15,5) default '0.00000',

  purdtl_qty_on_hnd decimal(15,5) default '0.00000',

  purdtl_qty_rcvd decimal(15,5) default '0.00000',

  purdtl_cost decimal(15,5) default '0.00000',

  purdtl_unit varchar(16) binary default NULL,

  purdtl_log timestamp(14) NOT NULL,

  UNIQUE KEY purdtl_id (purdtl_id),

  KEY purdtl_po_no (purdtl_po_no),

  KEY purdtl_purch_id (purdtl_purch_id),

  KEY purdtl_item_code (purdtl_item_code)

) TYPE=InnoDB;



--

-- Table structure for table 'rcptdtls'

--



CREATE TABLE rcptdtls (

  rcptdtl_id int(10) unsigned NOT NULL auto_increment,

  rcptdtl_rcpt_id int(10) unsigned NOT NULL default '0',

  rcptdtl_acct_code varchar(16) binary NOT NULL default '',

  rcptdtl_ref_code varchar(16) default NULL,

  rcptdtl_amt decimal(15,5) NOT NULL default '0.00000',

  rcptdtl_desc varchar(250) binary default NULL,

  PRIMARY KEY  (rcptdtl_id),

  UNIQUE KEY rcptdtl_id (rcptdtl_id),

  KEY rcptdtl_id_2 (rcptdtl_id)

) TYPE=MyISAM;



--

-- Table structure for table 'rcpts'

--



CREATE TABLE rcpts (

  rcpt_id int(10) unsigned NOT NULL auto_increment,

  rcpt_date date default NULL,

  rcpt_ref_code varchar(16) NOT NULL default '',

  rcpt_cust_code varchar(16) binary NOT NULL default '',

  rcpt_po_no varchar(16) binary default NULL,

  rcpt_check_no varchar(16) binary default NULL,

  rcpt_amt decimal(15,5) default '0.00000',

  rcpt_desc varchar(250) binary default NULL,

  rcpt_user_code varchar(16) binary NOT NULL default '',

  rcpt_log timestamp(14) NOT NULL,

  UNIQUE KEY rcpt_id (rcpt_id),

  KEY rcpt_date (rcpt_date),

  KEY rcpt_ref_id (rcpt_ref_code),

  KEY rcpt_cust_code (rcpt_cust_code),

  KEY rcpt_user_code (rcpt_user_code)

) TYPE=InnoDB;



--

-- Table structure for table 'sales'

--



CREATE TABLE sales (

  sale_id int(10) unsigned NOT NULL auto_increment,

  sale_code varchar(16) NOT NULL default '',

  sale_user_code varchar(16) binary NOT NULL default '',

  sale_cust_code varchar(16) binary NOT NULL default '',

  sale_cust_po varchar(16) binary NOT NULL default '',

  sale_addr1 varchar(128) binary default NULL,

  sale_addr2 varchar(128) binary default NULL,

  sale_addr3 varchar(128) binary default NULL,

  sale_city varchar(128) binary default NULL,

  sale_state varchar(32) binary default NULL,

  sale_country varchar(32) binary default NULL,

  sale_zip varchar(32) binary default NULL,

  sale_tel varchar(32) binary default NULL,

  sale_amt decimal(15,5) default '0.00000',

  sale_tax_amt decimal(15,5) default '0.00000',

  sale_freight_amt decimal(15,5) default '0.00000',

  sale_date date default NULL,

  sale_shipvia varchar(32) binary default NULL,

  sale_fin enum('t','f') default 'f',

  sale_comnt varchar(250) binary default NULL,

  sale_log timestamp(14) NOT NULL,

  UNIQUE KEY sale_code (sale_code),

  UNIQUE KEY sale_id (sale_id),

  KEY sale_user_code (sale_user_code),

  KEY sale_cust_code (sale_cust_code),

  KEY sale_date (sale_date)

) TYPE=InnoDB;



--

-- Table structure for table 'slsdtls'

--



CREATE TABLE slsdtls (

  slsdtl_id int(10) unsigned NOT NULL auto_increment,

  slsdtl_sale_id int(10) unsigned NOT NULL default '0',

  slsdtl_po_no varchar(16) binary NOT NULL default '',

  slsdtl_styl_code varchar(16) binary NOT NULL default '',

  slsdtl_qty decimal(15,5) default '0.00000',

  slsdtl_cost decimal(15,5) default '0.00000',

  slsdtl_log timestamp(14) NOT NULL,

  UNIQUE KEY slsdtl_id (slsdtl_id),

  KEY slsdtl_sale_id (slsdtl_sale_id),

  KEY slsdtl_po_no (slsdtl_po_no),

  KEY slsdtl_styl_code (slsdtl_styl_code)

) TYPE=InnoDB;



--

-- Table structure for table 'styldtls'

--



CREATE TABLE styldtls (

  styldtl_id int(10) unsigned NOT NULL auto_increment,

  styldtl_styl_code varchar(16) binary default NULL,

  styldtl_item_code varchar(16) binary default NULL,

  styldtl_item_desc varchar(250) binary default NULL,

  styldtl_meter_per_pair decimal(15,5) default '0.00000',

  styldtl_rmb_per_meter decimal(15,5) default '0.00000',

  styldtl_rmb_per_pair decimal(15,5) default '0.00000',

  styldtl_usd_per_pair decimal(15,5) default '0.00000',

  styldtl_unit varchar(16) binary default NULL,

  styldtl_group int(11) default NULL,

  styldtl_log timestamp(14) NOT NULL,

  UNIQUE KEY styldtl_id (styldtl_id),

  KEY styldtl_styl_code (styldtl_styl_code),

  KEY styldtl_item_code (styldtl_item_code)

) TYPE=InnoDB;



--

-- Table structure for table 'styles'

--



CREATE TABLE styles (

  styl_code varchar(16) binary NOT NULL default '',

  styl_po_no varchar(16) binary NOT NULL default '',

  styl_cust_code varchar(16) binary default NULL,

  styl_qty_work decimal(15,5) NOT NULL default '0.00000',

  styl_qty_board decimal(15,5) NOT NULL default '0.00000',

  styl_cost_usd decimal(15,5) NOT NULL default '0.00000',

  styl_cost_rmb decimal(15,5) NOT NULL default '0.00000',

  styl_unit varchar(16) NOT NULL default 'pair',

  styl_date date default NULL,

  styl_onbrd_date date default NULL,

  styl_status enum('o','w','f','h','c') default 'o',

  styl_desc varchar(250) binary default NULL,

  styl_log timestamp(14) NOT NULL,

  UNIQUE KEY styl_code (styl_code),

  KEY styl_date (styl_date),

  KEY styl_po_no (styl_po_no)

) TYPE=InnoDB;



--

-- Table structure for table 'users'

--



CREATE TABLE users (

  user_code varchar(16) binary NOT NULL default '',

  user_passwd varchar(32) binary NOT NULL default '',

  user_name varchar(32) binary default NULL,

  user_desc varchar(250) binary default NULL,

  user_log timestamp(14) NOT NULL,

  UNIQUE KEY user_code (user_code)

) TYPE=InnoDB;



--

-- Table structure for table 'vends'

--



CREATE TABLE vends (

  vend_code varchar(16) binary NOT NULL default '',

  vend_name varchar(32) binary NOT NULL default '',

  vend_addr1 varchar(128) binary default NULL,

  vend_addr2 varchar(128) binary default NULL,

  vend_addr3 varchar(128) binary default NULL,

  vend_city varchar(128) binary default NULL,

  vend_state varchar(32) binary default NULL,

  vend_country varchar(32) binary default NULL,

  vend_zip varchar(32) binary default NULL,

  vend_tel varchar(32) binary default NULL,

  vend_fax varchar(32) binary default NULL,

  vend_balance decimal(15,5) default '0.00000',

  vend_exp_acct varchar(16) binary default NULL,

  vend_ap_acct varchar(16) binary default NULL,

  vend_log timestamp(14) NOT NULL,

  UNIQUE KEY vend_code (vend_code),

  KEY vend_name (vend_name),

  KEY vend_exp_acct (vend_exp_acct),

  KEY vend_ap_acct (vend_ap_acct)

) TYPE=InnoDB;



