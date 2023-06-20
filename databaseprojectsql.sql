create database myartgallery;
use myartgallery;
CREATE TABLE `artist` (
  `artist_id` int NOT NULL,
  `birthplace` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `styleofart` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`artist_id`)
);
CREATE TABLE `artwork` (
  `art_title` varchar(10) NOT NULL,
  `releaseyear` int DEFAULT NULL,
  `typeofart` varchar(20) DEFAULT NULL,
  `price` int DEFAULT NULL,
  `artist_id` int DEFAULT NULL,
  PRIMARY KEY (`art_title`),
  KEY `artist_id` (`artist_id`),
  CONSTRAINT `artwork_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`artist_id`)
) ;
CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `cname` text,
  `address` varchar(30) DEFAULT NULL,
  `total_spendings` int DEFAULT NULL,
  `customer_liking` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ;
CREATE TABLE `exhibition` (
  `e_id` int NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`e_id`)
);
CREATE TABLE `arts_in_exhibition` (
  `e_id` int NOT NULL,
  `art_title` varchar(10) NOT NULL,
  PRIMARY KEY (`e_id`,`art_title`),
  KEY `art_title` (`art_title`),
  CONSTRAINT `arts_in_exhibition_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `exhibition` (`e_id`),
  CONSTRAINT `arts_in_exhibition_ibfk_2` FOREIGN KEY (`art_title`) REFERENCES `artwork` (`art_title`)
);
CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `order_date` datetime NOT NULL DEFAULT (now()),
  `total_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`)
);
CREATE TABLE `orderarts` (
  `artorder` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `art_title` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`artorder`),
  KEY `art_title` (`art_title`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `orderarts_ibfk_1` FOREIGN KEY (`art_title`) REFERENCES `artwork` (`art_title`),
  CONSTRAINT `orderarts_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
);
CREATE TABLE `orders_of_customer` (
  `customer_id` int NOT NULL,
  `order_id` int NOT NULL,
  PRIMARY KEY (`order_id`,`customer_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `orders_of_customer_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  CONSTRAINT `orders_of_customer_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
);
set sql_safe_updates = 0;
#liking of customer
UPDATE customer c
SET c.customer_liking = (
  SELECT a.typeofart
  FROM artwork a
  INNER JOIN orderarts oa ON a.art_title = oa.art_title
  INNER JOIN orders o ON oa.order_id = o.order_id
  WHERE o.customer_id = c.customer_id
  GROUP BY a.typeofart
  ORDER BY COUNT(a.typeofart) DESC
  LIMIT 1
);
#price of order
update orders set total_price = (
SELECT SUM(artwork.price)
FROM artwork, orderarts
where orderarts.art_title = artwork.art_title and orders.order_id = orderarts.order_id
GROUP BY orderarts.order_id);

#for total spendings
update customer set total_spendings = (
SELECT SUM(orders.total_price)
FROM orders
where orders.customer_id = customer.customer_id
GROUP BY orders.customer_id)


-- #triggers
DELIMITER $$

CREATE TRIGGER order_total
    AFTER INSERT
    ON orderarts FOR EACH ROW
BEGIN
     update orders set total_price = (
SELECT SUM(artwork.price)
FROM artwork, orderarts
where orderarts.art_title = artwork.art_title and orders.order_id = orderarts.order_id
GROUP BY orderarts.order_id);
END$$   

DELIMITER ;

DELIMITER $$

CREATE TRIGGER customer_spending
    AFTER INSERT
    ON orderarts FOR EACH ROW
BEGIN
update customer set total_spendings = (
SELECT SUM(orders.total_price)
FROM orders
where orders.customer_id = customer.customer_id
GROUP BY orders.customer_id);
END$$   

DELIMITER ;
DELIMITER $$

CREATE TRIGGER cust_like
    AFTER INSERT
    ON orderarts FOR EACH ROW
BEGIN
     UPDATE customer c
SET c.customer_liking = (
  SELECT a.typeofart
  FROM artwork a
  INNER JOIN orderarts oa ON a.art_title = oa.art_title
  INNER JOIN orders o ON oa.order_id = o.order_id
  WHERE o.customer_id = c.customer_id
  GROUP BY a.typeofart
  ORDER BY COUNT(a.typeofart) DESC
  LIMIT 1
);
END$$   

DELIMITER ;

alter table artist
add aname varchar(20);
INSERT INTO `myartgallery`.`artist` (`artist_id`, `birthplace`, `dob`, `styleofart`, `aname`) VALUES ('1', 'pakistan', '1969-04-20', 'realism', 'umama');
INSERT INTO `myartgallery`.`artwork` (`art_title`, `releaseyear`, `typeofart`, `price`, `artist_id`) VALUES ('love', '2001', 'realism', '2000', '1');
INSERT INTO `myartgallery`.`customer` (`customer_id`, `cname`, `address`) VALUES ('1', 'ali', 'attar nust');
INSERT INTO `myartgallery`.`orders` (`order_id`, `customer_id`) VALUES ('1', '1');
INSERT INTO `myartgallery`.`exhibition` (`e_id`, `start_date`, `end_date`, `location`) VALUES ('1', '2023-01-01', '2023-01-02', 'NUST');
INSERT INTO `myartgallery`.`arts_in_exhibition` (`e_id`, `art_title`) VALUES ('1', 'love');
