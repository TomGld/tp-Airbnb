CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `email` VARCHAR(50),
  `password` VARCHAR(128),
  `lastname` VARCHAR(50),
  `firstname` VARCHAR(50),
  `phone_number` VARCHAR(15),
  `id_role` int(5)
);

CREATE TABLE `accommodations` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(50),
  `price` DECIMAL(4,2),
  `surface` FLOAT(4,2),
  `description` TEXT(255),
  `capacity` INTEGER(255),
  `id_owner` INTEGER,
  `id_type` INT,
  `id_address` int
);

CREATE TABLE `rentals` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `id_accommodation` INTEGER,
  `id_customer` INTEGER,
  `date_from` DATE,
  `date_to` DATE
);

CREATE TABLE `equipments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `label` VARCHAR(50)
);

CREATE TABLE `accommodations_equipment` (
  `id_accommodation` int,
  `id_equipment` int,
  PRIMARY KEY (`id_accommodation`, `id_equipment`)
);

CREATE TABLE `accommodation_types` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `label` VARCHAR(50)
);

CREATE TABLE `addresses` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `street` VARCHAR(100),
  `city` VARCHAR(50),
  `country` VARCHAR(50)
);

ALTER TABLE `accommodations` ADD FOREIGN KEY (`id_owner`) REFERENCES `users` (`id`);

ALTER TABLE `accommodations` ADD FOREIGN KEY (`id_address`) REFERENCES `addresses` (`id`);

ALTER TABLE `accommodations_equipment` ADD FOREIGN KEY (`id_accommodation`) REFERENCES `accommodations` (`id`);

ALTER TABLE `accommodations_equipment` ADD FOREIGN KEY (`id_equipment`) REFERENCES `equipments` (`id`);

ALTER TABLE `rentals` ADD FOREIGN KEY (`id_accommodation`) REFERENCES `accommodations` (`id`);

ALTER TABLE `rentals` ADD FOREIGN KEY (`id_customer`) REFERENCES `users` (`id`);

ALTER TABLE `accommodations` ADD FOREIGN KEY (`id_type`) REFERENCES `accommodation_types` (`id`);
