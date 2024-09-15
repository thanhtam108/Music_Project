create database if not exists `AudioStoreDB` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use `AudioStoreDB`;

-- Table structure for category
drop table if exists `T_CATEGORY`;
create table if not exists `T_CATEGORY` (
	`CATE_ID` int(10) not null auto_increment,
	`CATE_NAME` varchar(64)  DEFAULT NULL,
    `CATE_DESC` varchar(255)  DEFAULT NULL,
  PRIMARY KEY (`CATE_ID`)
) ;

-- Table structure for brand

drop table if exists `T_BRAND`;
create table if not exists `T_BRAND` (
	`BRAND_ID` INT(10) not null auto_increment,
    `BRAND_NAME` varchar(64)  NOT NULL,
    `BRAND_DESCRIPTION` varchar(255)  DEFAULT NULL,
    `BRAND_IMAGEURL` varchar(255)  DEFAULT NULL,
    PRIMARY KEY (`BRAND_ID`)
) ;

-- Table structure for device table

drop table if exists `T_DEVICE`;
create table if not exists `T_DEVICE` (
	`DEV_ID` INT(10) NOT NULL AUTO_INCREMENT,
    `DEV_NAME` VARCHAR(255)  NOT NULL,
    `CATE_ID` int(10) not null ,
    `BRAND_ID` INT(10) not null,
    `DEV_IMAGEURL` VARCHAR(255)  NOT NULL,
    `DEV_PRICE` DECIMAL(12,2) NOT NULL,
    primary key (`DEV_ID`),
    foreign key (`CATE_ID`) references `T_CATEGORY` (`CATE_ID`),
    foreign key (`BRAND_ID`) references `T_BRAND` (`BRAND_ID`)
) ;

-- Table structure for employee table

drop table if exists `T_EMPLOYEE`;
create table if not exists `T_EMPLOYEE` (
	`EMP_ID` char (8) not null,
    `EMP_NAME` varchar(255)  NOT NULL,
    `EMP_PHONE` varchar(11)  NOT NULL UNIQUE,
    `EMP_EMAIL` varchar(255)  NOT NULL UNIQUE,
    `EMP_ACCOUNT` varchar(255)  NOT NULL UNIQUE,
    `EMP_PASSWORD` varchar(255)  NOT NULL,
    PRIMARY KEY (`EMP_ID`)
) ;

-- Table structure for Customer

drop table if exists `T_CUSTOMER`;
create table if not exists `T_CUSTOMER` (
    `CUSTOMER_ID` int(10)  NOT NULL auto_increment,
	`CUSTOMER_PHONE` varchar(11)  NOT NULL UNIQUE,
    `CUSTOMER_NAME` VARCHAR(255)  NOT NULL,
    `CUSTOMER_EMAIL` VARCHAR(255)  NOT NULL UNIQUE,
    `CUSTOMER_ADDRESS` VARCHAR(255)  NOT NULL,
    `CUSTOMER_ACCOUNT` VARCHAR(255)  NOT NULL unique,
    `CUSTOMER_PASSWORD` VARCHAR(255)  NOT NULL,
    primary key(`CUSTOMER_ID`)
) ;

CREATE TABLE T_CART (
    id INT AUTO_INCREMENT ,
    CUSTOMER_ID INT(10) NOT NULL,
    DEV_ID INT(10) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    primary key (id),
    foreign key (`CUSTOMER_ID`) references `T_CUSTOMER` (`CUSTOMER_ID`),
    foreign key (`DEV_ID`) references `T_DEVICE` (`DEV_ID`)
);


-- table structure for order table

drop table if exists `T_ORDER`;
create table if not exists `T_ORDER` (
	`ORDER_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`CUSTOMER_PHONE` VARCHAR(11)  NOT NULL,
    `ORDER_DATETIME` timestamp default CURRENT_TIMESTAMP,
    `ORDER_TOTAL_AMOUNT` decimal(12,2) not null,
    `ORDER_NOTES` varchar(255) ,
    primary key (`ORDER_ID`),
    foreign key (`CUSTOMER_PHONE`) references `T_CUSTOMER` (`CUSTOMER_PHONE`)
) ;

-- table structure for table order-items

drop table if exists `T_ORDER_DEVICES`;
CREATE TABLE IF NOT EXISTS `T_ORDER_DEVICES` (
    `ORDER_ID` INT(10)  NOT NULL,
    `DEV_ID` INT(10) NOT NULL,
    FOREIGN KEY (ORDER_ID) REFERENCES T_ORDER(ORDER_ID),
    FOREIGN KEY (DEV_ID) REFERENCES T_DEVICE(DEV_ID)
);

-- table structure for table temporary order

DROP TABLE IF EXISTS `T_TEMPORARY_ORDER`;
CREATE TABLE IF NOT EXISTS `T_TEMPORARY_ORDER` (
    `TEMP_ORDER_ID` INT(10)  NOT NULL AUTO_INCREMENT,
    `CUSTOMER_PHONE` VARCHAR(11) NOT NULL,
    `Order_DateTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `Total_amount` INT(12),
    `ORDER_NOTES` varchar(255),
    PRIMARY KEY (`TEMP_ORDER_ID`),
    FOREIGN KEY (`CUSTOMER_PHONE`) REFERENCES `T_CUSTOMER`(`CUSTOMER_PHONE`)
);

-- Table structure for table temporary order-devices

DROP TABLE IF EXISTS `T_TEMPORARY_ORDER_DEVICES`;
CREATE TABLE IF NOT EXISTS `T_TEMPORARY_ORDER_DEVICES` (
    `TEMP_ORDER_ID` int(10) NOT NULL,
    `DEV_ID` int(10) NOT NULL,
    FOREIGN KEY (`TEMP_ORDER_ID`) REFERENCES `T_TEMPORARY_ORDER`(`TEMP_ORDER_ID`),
    FOREIGN KEY (`DEV_ID`) REFERENCES `T_DEVICE`(`DEV_ID`)
);


-- dumping data for t_category

insert into `T_CATEGORY`(`CATE_NAME`,`CATE_DESC`)
values
('Tai nghe có dây nhét tai', 'Âm thanh chất lượng cao, đáng tin cậy mà không cần sạc.'),
('Tai nghe có dây chụp tai', 'Vừa vặn thoải mái, âm thanh sống động cho việc nghe kéo dài.'),
('Tai nghe không dây nhét tai', 'Nghe tiện lợi, không bị rối dây khi di chuyển.'),
('Tai nghe không dây chụp tai', 'Trải nghiệm không dây liền mạch, pin lâu dài.'),
('Máy nghe nhạc số', 'Nhỏ gọn, âm thanh trung thực cao khi di chuyển.'),
('Loa có dây', 'Âm thanh rõ nét cho giải trí tại nhà.'),
('Loa không dây', 'Phát âm thanh di động, kết nối Bluetooth.'),
('Phụ kiện âm thanh', 'Nâng cao hệ thống của bạn với cáp, bộ chuyển đổi và nhiều hơn nữa.');



-- Dumping data for t_brand

insert into `T_BRAND` (`BRAND_NAME`, `BRAND_DESCRIPTION`, `BRAND_IMAGEURL`)
values
('SONY', 'Sony is a major Japanese manufacturer of consumer electronics products. It was established in 1946 as Tokyo Tsushin Kogyo by Masaru Ibuka and Akio Morita. 
Sony is known for creating products such as the transistor radio TR-55, the home video tape recorder CV-2000, the portable audio player Walkman, and the compact disc player CDP-101', 'Sony_Logo.jpg'),
('JBL', 'JBL is an American audio equipment manufacturer headquartered in Los Angeles, California, United States. JBL serves the customer home and professional market.', 'JBL_Logo.png'),
('BOSE', 'Bose Corporation is an American manufacturing company that predominantly sells audio equipments. The company was established by Amar Bose in 1964 and is based in Framingham, Massachusetts1. 
It is best known for its home audio systems and speakers, noise cancelling headphones, professional audio products and automobile sound systems', 'Bose_Logo.jpg'),
('MARSHALL', 'Marshall, a legendary name in the world of music amplification, has been synonymous with powerful sound and iconic design for over 60 years. Founded by Jim Marshall, a shop owner and drummer, the company has left an indelible mark on the music industry.', 'Marshall_Logo.png'),
('YAMAHA', "As the world's leading manufacturer of musical instruments and audio equipment, Yamaha is uniquely positioned to express every sound as the artist intended. Sound that delivers incredibly detailed and accurate timbre in each note. 
Sound experienced by the emotive contrast between stillness and motion.", 'Yamaha_Logo.png');

-- Dumping data into T_DEVICE

-- Devices for SONY
INSERT INTO `T_DEVICE` (`DEV_NAME`, `CATE_ID`, `BRAND_ID`, `DEV_IMAGEURL`, `DEV_PRICE`)
VALUES
('Tai nghe không dây In-ear WF-1000XM5', 3, 1, 'WF-1000XM5.jpg', 4699775),
('Tai nghe không dây In-ear WF-1000XM4', 3, 1, 'WF-1000XM4.png', 4229775),
('Tai nghe không dây On-ear WH-1000XM5', 4, 1, 'WH-1000XM5.jpg', 8229775),
('Tai nghe không dây On-ear WH-1000XM4', 4, 1, 'WH-1000XM4.jpg', 7049775),
('Tai nghe có dây On-ear MDR-Z1R', 2, 1, 'MDR-Z1R.jpg', 39999775),
('Tai nghe có dây On-ear MDR-Z7M2', 2, 1, 'MDR-Z7M2.jpg', 16499775),
('Tai nghe có dây On-ear MDR-MV1', 2, 1, 'MDR-MV1.jpg', 7049775),
('Tai nghe có dây In-ear MDR-EX15AP', 1, 1, 'MDR-EX15AP.jpg', 469775),
('Tai nghe có dây In-ear IER-M9', 1, 1, 'IER-M9.jpg', 21199775),
('Máy nghe nhạc số Walkman NW-WM1ZM2', 5, 1, 'Walkman NW-WM1ZM2.jpg', 75199775),
('Máy nghe nhạc số Walkman NW-WM1AM2', 5, 1, 'Walkman NW-WM1AM2.jpg', 51699775);

-- Devices for JBL
INSERT INTO `T_DEVICE` (`DEV_NAME`, `CATE_ID`, `BRAND_ID`, `DEV_IMAGEURL`, `DEV_PRICE`)
VALUES
('Loa không dây Charge 5', 7, 2, 'Charge 5.jpg', 4228325),
('Loa không dây Flip 6', 7, 2, 'Flip 6.jpg', 3053325),
('Loa không dây Xtreme 3', 7, 2, 'Xtreme 3.jpg', 8228325),
('Loa không dây Boombox 3', 7, 2, 'Boombox 3.jpg', 11748225),
('Loa không dây Flip Essential 2', 7, 2, 'Essential 2.jpg', 2343325),
('Loa không dây Wind 3S', 7, 2, 'Wind 3S.jpg', 2343325),
('Tai nghe không dây In-ear Live Pro 2 TWS', 3, 2, 'Live Pro 2 TWS.jpg', 4228325),
('Tai nghe không dây In-ear Wave Beam', 3, 2, 'Wave Beam.jpg', 1878325),
('Tai nghe không dây In-ear Tune Beam', 3, 2, 'Tune Beam.jpg', 2343325),
('Tai nghe không dây On-ear Tune 520BT', 4, 2, 'Tune 520BT.jpg', 1403325),
('Tai nghe không dây On-ear Tour One M2', 4, 2, 'Tour One M2.jpg', 4693325),
('Phụ kiện âm thanh Stadium GTO 600C', 8, 2, 'Stadium GTO 600C.png', 4228325),
('Phụ kiện âm thanh Club 605CSQ', 8, 2, 'Club 605CSQ.jpg', 2818325),
('Phụ kiện âm thanh Stage3 607C', 8, 2, 'Stage3 607C.jpg', 1648325);

-- Các thiết bị cho BOSE
INSERT INTO `T_DEVICE` (`DEV_NAME`, `CATE_ID`, `BRAND_ID`, `DEV_IMAGEURL`, `DEV_PRICE`)
VALUES
('Tai nghe không dây In-ear Bose Ultra Open Earbuds', 3, 3, 'Bose Ultra Open Earbuds.jpg', 4682500),
('Tai nghe không dây In-ear Bose QuietComfort Ultra Earbuds', 3, 3, 'Bose QuietComfort Ultra Earbuds.jpg', 6565000),
('Tai nghe không dây On-ear Bose QuietComfort Ultra Headphones', 4, 3, 'Bose QuietComfort Ultra Headphones.jpg', 8215000),
('Tai nghe không dây On-ear Bose QuietComfort Headphones', 4, 3, 'Bose QuietComfort Headphones.jpg', 7030000),
('Tai nghe không dây On-ear Bose A30 Aviation Headset', 4, 3, 'Bose A30 Aviation Headset.jpg', 23482500),
('Phụ kiện âm thanh Vỏ sạc không dây cho Bose Ultra Open Earbuds', 8, 3, 'Bose Ultra Open Earbuds Wireless Charging Case Cover.png', 916500),
('Phụ kiện âm thanh AirFly SE', 8, 3, 'AirFly SE.png', 1152500),
('Phụ kiện âm thanh AirFly Pro', 8, 3, 'AirFly Pro.png', 1621500),
('Phụ kiện âm thanh Vỏ sạc không dây Bose', 8, 3, 'Bose Wireless Charging Case Cover.jpg', 916500),
('Phụ kiện âm thanh Bộ đệm tai nghe QuietComfort 45', 8, 3, 'QuietComfort 45 Headphones Ear Cushion Kit.jpg', 681500);

-- Các thiết bị cho Marshall
INSERT INTO `T_DEVICE` (`DEV_NAME`, `CATE_ID`, `BRAND_ID`, `DEV_IMAGEURL`, `DEV_PRICE`)
VALUES
('Loa không dây ACTON III ĐEN', 7, 4, 'MRSHL-ACTON III BLACK.jpeg', 7049775),
('Loa không dây ACTON III NÂU', 7, 4, 'MRSHL-ACTON III BROWN.jpg', 7049775),
('Loa không dây ACTON III KEM', 7, 4, 'MRSHL-ACTON III CREAM.jpg', 7049775),
('Loa không dây STANMORE III ĐEN', 7, 4, 'MRSHL-STANMORE III BLACK.jpg', 9399975),
('Loa không dây WOBURN III ĐEN', 7, 4, 'MRSHL-WOBURN III BLACK.jpg', 11749975),
('Loa không dây WOBURN III KEM', 7, 4, 'MRSHL-WOBURN III CREAM.jpg', 11749975),
('Tai nghe không dây On-ear MONITOR II A.N.C. ĐEN', 4, 4, 'MRSHL-MONITOR II A.N.C. BLACK.jpg', 8229975),
('Tai nghe không dây On-ear MAJOR IV ĐEN', 4, 4, 'MRSHL-MAJOR IV BLACK.jpg', 3524975),
('Tai nghe không dây On-ear MAJOR IV NÂU', 4, 4, 'MRSHL-MAJOR IV BROWN.jpg', 3524975),
('Tai nghe không dây In-ear MOTIF II A.N.C ĐEN', 3, 4, 'MRSHL-MOTIF II A.N.C BLACK.png', 5874975),
('Tai nghe không dây In-ear MINOR III ĐEN', 3, 4, 'MRSHL-MINOR III BLACK.png', 3053325);

-- Các thiết bị cho Yamaha
INSERT INTO `T_DEVICE` (`DEV_NAME`, `CATE_ID`, `BRAND_ID`, `DEV_IMAGEURL`, `DEV_PRICE`)
VALUES
('Loa có dây CZR15', 6, 5, 'YAMAHA-CZR15.jpg', 30565000),
('Loa có dây CZR12', 6, 5, 'YAMAHA-CZR12.jpg', 25826500),
('Loa có dây CZR10', 6, 5, 'YAMAHA-CZR10.jpg', 23486500),
('Loa có dây CXS18XLF', 6, 5, 'YAMAHA-CXS18XLF.jpg', 46965000),
('Loa có dây CXS15XLF', 6, 5, 'YAMAHA-CXS15XLF.jpg', 42246500),
('Loa không dây TRUE X SPEAKER 1A', 7, 5, 'YAMAHA-TRUE-X-1A.jpg', 11731500),
('Loa không dây WS-B1A', 7, 5, 'YAMAHA-WS-B1A.jpg', 9385000),
('Phụ kiện âm thanh HXC-SC020', 8, 5, 'YAMAHA-HXC-SC020.jpg', 681500),
('Phụ kiện âm thanh HUC-SC020', 8, 5, 'YAMAHA-HUC-SC020.jpg', 916500),
('Phụ kiện âm thanh HBC-SC020', 8, 5, 'YAMAHA-HBC-SC020.jpg', 1151500);



-- Dumping data into emp table 

INSERT INTO `T_EMPLOYEE` (`EMP_ID`, `EMP_NAME`, `EMP_PHONE`, `EMP_EMAIL`, `EMP_ACCOUNT`, `EMP_PASSWORD`)
values
('thinha0001', 'Nguyen Vỉnh Thịnh', '0934567890', 'tamam1kaj@gmail.com', 'admin1', '123'),
('longa0001', 'Lê Hồng Nhất Long', '0934567190', 'longaaam1kaj@gmail.com', 'admin2', '123'),
('vub0001', 'Nguyễn Phong Thiên Vũ', '0934567120', 'bvkaj@gmail.com', 'vu11', '123456'),
('phungb0001', 'Trịnh Phương Nhất Phụng', '0934564190', 't332aj@gmail.com', 'lo132', '123'),
('daob0001', 'Lê Hưng Đạo', '0934562190', 'tsam1kaa@gmail.com', 'daotran', '123'),
('kimb0001', 'Trần Hoàng Kim', '0954567190', 'kimam1kaj@gmail.com', 'kimphung123', '123');

-- Dumping data into customers table

INSERT INTO `T_CUSTOMER` (`CUSTOMER_PHONE`,`CUSTOMER_NAME`, `CUSTOMER_EMAIL`, `CUSTOMER_ADDRESS`, `CUSTOMER_ACCOUNT`, `CUSTOMER_PASSWORD`)
VALUES
('08765432109', 'Alice Johnson', 'alice@example.com', '321 Đường Số 1, Quận A, Thành phố HCM', 'alice_johnson321', 'password987'),
('09654321098', 'Bob Smith', 'bob@example.com', '654 Đường Số 2, Quận B, Thành phố Hà Nội', 'bob_smith654', 'password876'),
('07543210987', 'Sarah Brown', 'sarah@example.com', '987 Đường Số 3, Quận C, Thành phố Đà Nẵng', 'sarah_brown987', 'password765'),
('09432109876', 'Ryan Davis', 'ryan@example.com', '210 Đường Số 4, Quận D, Thành phố Cần Thơ', 'ryan_davis210', 'password654'),
('09321098765', 'Emma Wilson', 'emma@example.com', '543 Đường Số 5, Quận E, Thành phố Hải Phòng', 'emma_wilson543', 'password543');


