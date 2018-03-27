CREATE TABLE `categories` (
  `sno` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `cities` (
  `name` varchar(255) NOT NULL,
  `sno` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `doctors` (
  `sno` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `city` int(11) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `sno` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` bigint(10) NOT NULL,
  `confirm_email` bit(1) NOT NULL DEFAULT b'0',
  `reset_password` varchar(255) NOT NULL DEFAULT '0',
  `otp` varchar(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `categories`
  ADD PRIMARY KEY (`sno`);

ALTER TABLE `cities`
  ADD PRIMARY KEY (`sno`);

ALTER TABLE `doctors`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `category` (`category`),
  ADD KEY `city` (`city`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`sno`);

ALTER TABLE `categories`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

ALTER TABLE `cities`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `doctors`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`sno`),
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`city`) REFERENCES `cities` (`sno`);
COMMIT;