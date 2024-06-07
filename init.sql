CREATE DATABASE IF NOT EXISTS carmaster_db;
USE carmaster_db;

CREATE TABLE IF NOT EXISTS client
(
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(20)
    );

CREATE TABLE IF NOT EXISTS car
(
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type      VARCHAR(255),
    brand     VARCHAR(255),
    model     VARCHAR(255),
    year      INT UNSIGNED,
    number    VARCHAR(20),
    client_id INT UNSIGNED,
    FOREIGN KEY (client_id) REFERENCES client (id)
    );


CREATE TABLE IF NOT EXISTS product
(
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(255),
    cost          FLOAT UNSIGNED,
    quantity      INT UNSIGNED,
    type          VARCHAR(10),
    selling_price FLOAT UNSIGNED
    );

CREATE TABLE IF NOT EXISTS `order`
(
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    creation_date DATETIME,
    client_id     INT UNSIGNED,
    FOREIGN KEY (client_id) REFERENCES client (id),
    car_id        INT UNSIGNED,
    FOREIGN KEY (car_id) REFERENCES car (id),
    total_cost    FLOAT UNSIGNED,
    payment_date  DATETIME
    );

CREATE TABLE IF NOT EXISTS service
(
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(255),
    cost     FLOAT UNSIGNED,
    duration INT UNSIGNED
    );

CREATE TABLE IF NOT EXISTS order_service
(
    order_id  INT UNSIGNED,
    service_id INT UNSIGNED,
    PRIMARY KEY (order_id, service_id),
    FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE
    );
