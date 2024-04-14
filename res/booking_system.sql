CREATE DATABASE IF NOT EXISTS booking_system;
USE booking_system;

CREATE TABLE IF NOT EXISTS user_role (
  role_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  type CHAR(75) NOT NULL,
  PRIMARY KEY (role_id),
  UNIQUE INDEX UNIQUE_type (type));

CREATE TABLE IF NOT EXISTS user (
  user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password CHAR(200) NOT NULL,
  role_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (user_id),
  UNIQUE INDEX UNIQUE_username (username),
  INDEX FK_INDEX_user_role (role_id),
  CONSTRAINT FK_user_user_role
    FOREIGN KEY (role_id)
    REFERENCES user_role (role_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS customer (
  customer_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  firstName VARCHAR(50) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  phoneNumber CHAR(25) NOT NULL,
  emailAddress VARCHAR(150) NOT NULL,
  PRIMARY KEY (customer_id),
  UNIQUE INDEX UNIQUE_user_id (user_id),
  CONSTRAINT FK_customer_user
    FOREIGN KEY (user_id)
    REFERENCES user (user_id)
);

CREATE TABLE IF NOT EXISTS lessor (
  lessor_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  firstName VARCHAR(50) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  phoneNumber CHAR(25) NOT NULL,
  emailAddress VARCHAR(150) NOT NULL,
  PRIMARY KEY (lessor_id),
  UNIQUE INDEX UNIQUE_user_id (user_id),
  CONSTRAINT FK_lessor_user
    FOREIGN KEY (user_id)
    REFERENCES user (user_id)
);

CREATE TABLE IF NOT EXISTS administrator (
  administrator_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  firstName VARCHAR(50) NOT NULL,
  lastName VARCHAR(100) NOT NULL,
  phoneNumber CHAR(25) NOT NULL,
  emailAddress VARCHAR(150) NULL,
  PRIMARY KEY (administrator_id),
  UNIQUE INDEX UNIQUE_user_id (user_id),
  CONSTRAINT FK_administrator_user
    FOREIGN KEY (user_id)
    REFERENCES user (user_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS lodging (
  lodging_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  lessor_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  description VARCHAR(1000) NOT NULL,
  address VARCHAR(300) NOT NULL,
  perNightPrice DECIMAL UNSIGNED NOT NULL,
  availableRooms MEDIUMINT NOT NULL,
  PRIMARY KEY (lodging_id),
  INDEX FK_INDEX_lodging_lessor (lessor_id),
  CONSTRAINT FK_lodging_lessor
    FOREIGN KEY (lessor_id)
    REFERENCES lessor (lessor_id)
);

CREATE TABLE IF NOT EXISTS booking_status (
  booking_status_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  type VARCHAR(75) NOT NULL,
  PRIMARY KEY (booking_status_id),
  UNIQUE INDEX UNIQUE_type (type)
);

CREATE TABLE IF NOT EXISTS booking (
  booking_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  lodging_id INT UNSIGNED NOT NULL,
  customer_id INT UNSIGNED NOT NULL,
  status_id INT UNSIGNED NOT NULL,
  startDate CHAR(50) NOT NULL,
  endDate CHAR(50) NOT NULL,
  PRIMARY KEY (booking_id),
  INDEX FK_INDEX_booking_booking_status (status_id),
  INDEX FK_INDEX_booking_lodging (lodging_id),
  INDEX FK_INDEX_booking_customer (customer_id),
  
  CONSTRAINT FK_booking_booking_status
    FOREIGN KEY (status_id)
    REFERENCES booking_status (booking_status_id),
	
  CONSTRAINT FK_booking_lodging
    FOREIGN KEY (lodging_id)
    REFERENCES lodging (lodging_id),
	
  CONSTRAINT FK_booking_customer
    FOREIGN KEY (customer_id)
    REFERENCES customer (customer_id)
);

CREATE TABLE IF NOT EXISTS payment (
  booking_id INT UNSIGNED NOT NULL,
  date CHAR(50) NOT NULL,
  totalAmount DECIMAL UNSIGNED NOT NULL,
  INDEX FK_INDEX_booking_payment (booking_id),
  
  CONSTRAINT FK_booking_payment
    FOREIGN KEY (booking_id)
    REFERENCES booking (booking_id)
);
