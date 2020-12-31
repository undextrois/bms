CREATE DATABASE IF NOT EXISTS bms;

GRANT SELECT,DELETE,INSERT,UPDATE ON bms.* TO bmsu@localhost IDENTIFIED BY 'password';

SET PASSWORD FOR bmsu@localhost = OLD_PASSWORD('password');

use bms;

DROP TABLE IF EXISTS BMS_Module_List;
DROP TABLE IF EXISTS BMS_Clients;
DROP TABLE IF EXISTS BMS_Client_Modules;
DROP TABLE IF EXISTS BMS_Active_Modules;
DROP TABLE IF EXISTS BMS_Users;
DROP TABLE IF EXISTS BMS_Sessions;

CREATE TABLE BMS_Module_List
(
   m_ModuleName VARCHAR(32),
   m_ModuleCode VARCHAR(12),
   m_CCPC INT,
   m_CCHL INT,
   m_MA VARCHAR (16),
   m_MAC VARCHAR(10),
   m_HLIDMinVal INT,
   m_HLIDMaxVal INT,
   m_Increment INT
);

INSERT INTO BMS_Module_List VALUES('Bizerba BMS',     '098101104107',    '7',       '7000',    '7-19-31-43',      'b-e-h-k', '50',      '7000',    '75');
INSERT INTO BMS_Module_List VALUES('Reports Module',  '080083086089',    '14',      '7250',    '8-20-32-44',      'P-S-V-Y', '50',      '7250',    '75');
INSERT INTO BMS_Module_List VALUES('Service Module',  '067070073076',    '21',      '7500',    '9-21-33-45',      'C-F-I-L', '50',      '7500',    '75');
INSERT INTO BMS_Module_List VALUES('Module 4',        '112115118121',    '28',      '7750',    '10-22-34-46',     'p-s-v-y', '50',      '7750',    '75');
INSERT INTO BMS_Module_List VALUES('Module 5',        '991021051080',    '35',      '8000',    '11-23-35-47',     'c-f-i-l', '50',      '8000',    '75');
INSERT INTO BMS_Module_List VALUES('Module 6',        '081084087090',    '42',      '8250',    '12-24-36-48',     'Q-T-W-Z', '50',      '8250',    '75');
INSERT INTO BMS_Module_List VALUES('Module 7',        '068071074077',    '49',      '8500',    '13-25-37-49',     'D-G-J-M', '50',      '8500',    '75');
INSERT INTO BMS_Module_List VALUES('Module 8',        '113116119122',    '56',      '8750',    '14-26-38-50',     'q-t-w-z', '50',      '8750',    '75');
INSERT INTO BMS_Module_List VALUES('Module 9',        '100103106109',    '63',      '9000',    '15-27-39-51',     'd-g-j-m', '50',      '9000',    '75');
INSERT INTO BMS_Module_List VALUES('Module 10',       '082085088049',    '70',      '9250',    '16-28-40-52',     'R-U-X-1', '50',      '9250',    '75');
INSERT INTO BMS_Module_List VALUES('Module 11',       '069072075078',    '77',      '9500',    '17-29-41-53',     'E-H-K-N', '50',      '9500',    '75');
INSERT INTO BMS_Module_List VALUES('Module 12',       '114117120500',    '84',      '9750',    '18-30-42-54',     'r-u-x-2', '50',      '9750',    '75');

CREATE TABLE BMS_Clients
(
   m_ClientID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   m_CPUID VARCHAR (16),
   m_ContactPerson VARCHAR (64),
   m_CompanyName VARCHAR(64),
   m_CreationDate DATETIME,
   m_Country VARCHAR(16),
   m_Email VARCHAR (32),
   m_Phone VARCHAR (32),
   m_LastLogin DATETIME
);

CREATE TABLE BMS_Client_Modules
(
   m_CPUID VARCHAR (16),
   m_Module1 CHAR(1) DEFAULT 'N',
   m_Module2 CHAR(1) DEFAULT 'N',
   m_Module3 CHAR(1) DEFAULT 'N',
   m_Module4 CHAR(1) DEFAULT 'N',
   m_Module5 CHAR(1) DEFAULT 'N',
   m_Module6 CHAR(1) DEFAULT 'N',
   m_Module7 CHAR(1) DEFAULT 'N',
   m_Module8 CHAR(1) DEFAULT 'N',
   m_Module9 CHAR(1) DEFAULT 'N',
   m_Module10 CHAR(1) DEFAULT 'N'
);

INSERT INTO BMS_Clients(m_CPUID,m_ContactPerson,m_CompanyName,m_Country,m_CreationDate,m_Email,m_Phone,m_LastLogin)
   VALUES('BFEBFBFF000006F6','Manong Guard','BMS','Philippines',NOW(),'user@bms.com','7654321',NOW());

CREATE TABLE BMS_Active_Modules
(
   m_ModuleID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   m_CPUID VARCHAR (16),
   m_ModuleName VARCHAR (32),
   m_ModuleCode VARCHAR (12),
   m_Active CHAR(1) DEFAULT 'N',
   m_ActivationCode VARCHAR (80),
   m_RequestCode VARCHAR (80),
   m_ActivationDate DATETIME,
   m_Type VARCHAR (2),
   m_ActivatedBy VARCHAR (16)
);

CREATE TABLE BMS_Users
(
   m_UserNo INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   m_UserID VARCHAR (16),
   m_Passwd VARCHAR (32),
   m_Name VARCHAR(80),
   m_CreationDate DATETIME,
   m_LastLogin DATETIME
);

INSERT INTO BMS_Users (m_UserID,m_Passwd,m_Name,m_CreationDate,m_LastLogin)
   VALUES("BMSA01","password","BMS Administrator",CURRENT_DATE,CURRENT_DATE);

CREATE TABLE BMS_Sessions
(
   m_SessionID VARCHAR (32),
   m_UserID VARCHAR (16),
   m_Key1 VARCHAR (16),
   m_Type VARCHAR (3),
   m_DateCreated DATETIME,
   m_TimeCheck DATETIME,
   m_EndTime DATETIME,
   m_Flag INT
);
