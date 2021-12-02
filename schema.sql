DROP DATABASE IF EXISTS `bugme`;
CREATE DATABASE `bugme`;
USE `bugme`;

DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `issues`;

--
-- Table creation
--

CREATE TABLE `users` (
    `id` int(11) AUTO_INCREMENT NOT NULL,
    `firstname` varchar(200) NOT NULL,
    `lastname` varchar(200) NOT NULL,
    `password` varchar(200) NOT NULL,
    `email` varchar(35) NOT NULL,
    `date_joined` datetime NOT NULL DEFAULT current_timestamp(),

    PRIMARY KEY(`id`)

);

CREATE TABLE `issues` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(200) NOT NULL,
    `description` text NOT NULL,
    `type` varchar(200) NOT NULL,
    `priority` varchar(200) NOT NULL,
    `status` varchar(200) NOT NULL,
    `assigned_to` int(11) NOT NULL,
    `created_by` int(11) NOT NULL,
    `created` datetime NOT NULL DEFAULT current_timestamp(),
    `updated` datetime NOT NULL DEFAULT current_timestamp(),

    PRIMARY KEY(`id`),
    FOREIGN KEY(`created_by`) REFERENCES `users`(`id`) ON UPDATE cascade,
    FOREIGN KEY(`assigned_to`) REFERENCES `users`(`id`) ON UPDATE cascade
);

--
-- Data for `users` table
--

-- REMEMBER TO HASH THE PASSWORD AND COPY THE HASH HERE
INSERT INTO `users` (
    `firstname`,
    `lastname`,
    `password`,
    `email`)
VALUES
(
    'John',
    'Doe',
    'password123',
    'admin@project2.com'
),
(
    'Jane',
    'Berry',
    'password1',
    'janeberyy@example.com'
);

--
-- Data for `issues` table
--

INSERT INTO `issues` (
    `title`,
    `description`,
    `type`,
    `priority`,
    `status`,
    `assigned_to`,
    `created_by`)
VALUES
(
    'Cannot log into computer',
    'Hi Team I am unable to login to my computer',
    'Support',
    'High',
    'Open',
    1,
    1
);
