-- You may need to run the commented out lines as a user with permission to
-- create users and grant permissions

-- CREATE USER IF NOT EXISTS bugmeAdmin IDENTIFIED BY 'password123';

DROP DATABASE IF EXISTS `bugme`;
CREATE DATABASE `bugme`;

-- GRANT ALL PRIVILEGES ON bugme.* TO bugmeAdmin;

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
    `status` varchar(200) NOT NULL DEFAULT "OPEN",
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
    /** Hash for Passwords written in SQL -> HashBytes('SHA2_512', LTRIM(RTRIM('password123')) )
    LTRIM is left trim which just removes any whitespace to the left of the password & RTRIM removes whitespace to the right of the password*/
    'password123',
    'admin@project2.com'
),
(
    'Jane',
    'Berry',
    /** Hash for Passwords written in SQL -> HashBytes('SHA2_512', LTRIM(RTRIM('password1')) )*/
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
    'Bug',
    'Minor',
    'OPEN',
    1,
    1
),
(
    'Cannot log into computer',
    'Hi Team I am unable to login to my computer',
    'Proposal',
    'Major',
    'IN PROGRESS',
    2,
    2
),
(
    'Cannot log into computer',
    'Hi Team I am unable to login to my computer',
    'Task',
    'Critical',
    'CLOSED',
    1,
    2
),
(
    'Cannot log into computer',
    'Hi Team I am unable to login to my computer',
    'Bug',
    'Minor',
    'OPEN',
    2,
    1
);
