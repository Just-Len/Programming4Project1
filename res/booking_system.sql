CREATE DATABASE IF NOT EXISTS booking_system;
USE booking_system;

CREATE TABLE IF NOT EXISTS user_role (
  role_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  type CHAR(75) NOT NULL,
  PRIMARY KEY (role_id),
  UNIQUE INDEX UNIQUE_type (type));

CREATE TABLE IF NOT EXISTS user (
  name VARCHAR(50) NOT NULL,
  password CHAR(200) NOT NULL,
  role_id INT UNSIGNED NOT NULL,
  email_address VARCHAR(150) NOT NULL,
  last_logout TIMESTAMP NOT NULL DEFAULT '1970-01-01 00:00:00',
  image VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (name),
  INDEX FK_INDEX_user_role (role_id),
  UNIQUE INDEX UNIQUE_email_address (email_address),
  CONSTRAINT FK_user_user_role
    FOREIGN KEY (role_id)
    REFERENCES user_role (role_id)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS customer (
  customer_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_name VARCHAR(50) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  phone_number CHAR(25) NOT NULL,
  PRIMARY KEY (customer_id),
  UNIQUE INDEX UNIQUE_user_name (user_name),
  CONSTRAINT FK_customer_user
    FOREIGN KEY (user_name)
    REFERENCES user (name)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS lessor (
  lessor_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_name VARCHAR(50) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  phone_number CHAR(25) NOT NULL,
  PRIMARY KEY (lessor_id),
  UNIQUE INDEX UNIQUE_user_name (user_name),
  CONSTRAINT FK_lessor_user
    FOREIGN KEY (user_name)
    REFERENCES user (name)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS administrator (
  administrator_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_name VARCHAR(50) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  phone_number CHAR(25) NOT NULL,
  PRIMARY KEY (administrator_id),
  UNIQUE INDEX UNIQUE_user_name (user_name),
  CONSTRAINT FK_administrator_user
    FOREIGN KEY (user_name)
    REFERENCES user (name)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS lodging (
  lodging_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  lessor_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  description VARCHAR(1000) NOT NULL,
  address VARCHAR(300) NOT NULL,
  per_night_price DECIMAL UNSIGNED NOT NULL,
  available_rooms MEDIUMINT NOT NULL,
  image VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (lodging_id),
  INDEX FK_INDEX_lodging_lessor (lessor_id),
  CONSTRAINT FK_lodging_lessor
    FOREIGN KEY (lessor_id)
    REFERENCES lessor (lessor_id)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
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
  start_date CHAR(50) NOT NULL,
  end_date CHAR(50) NOT NULL,
  PRIMARY KEY (booking_id),
  INDEX FK_INDEX_booking_booking_status (status_id),
  INDEX FK_INDEX_booking_lodging (lodging_id),
  INDEX FK_INDEX_booking_customer (customer_id),

  CONSTRAINT FK_booking_booking_status
    FOREIGN KEY (status_id)
    REFERENCES booking_status (booking_status_id)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,

  CONSTRAINT FK_booking_lodging
    FOREIGN KEY (lodging_id)
    REFERENCES lodging (lodging_id)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,

  CONSTRAINT FK_booking_customer
    FOREIGN KEY (customer_id)
    REFERENCES customer (customer_id)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS payment (
  booking_id INT UNSIGNED NOT NULL,
  date CHAR(50) NOT NULL,
  total_amount DECIMAL UNSIGNED NOT NULL,
  INDEX FK_INDEX_booking_payment (booking_id),

  CONSTRAINT FK_booking_payment
    FOREIGN KEY (booking_id)
    REFERENCES booking (booking_id)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
);