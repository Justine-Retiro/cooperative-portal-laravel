

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `citizenship` varchar(20) DEFAULT NULL,
  `civil_status` varchar(15) DEFAULT NULL,
  `city_address` varchar(80) DEFAULT NULL,
  `provincial_address` varchar(75) DEFAULT NULL,
  `mailing_address` varchar(50) DEFAULT NULL,
  `account_status` varchar(50) NOT NULL,
  `phone_num` int(11) DEFAULT NULL,
  `taxID_num` int(20) DEFAULT NULL,
  `spouse_name` varchar(50) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(50) DEFAULT NULL,
  `date_employed` date DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `natureOf_work` varchar(50) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL,
  `amountOf_share` decimal(10,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO clients VALUES("6","6","639132689","Retiro","Dagatan","Justine","Filipino","Single","Cabanatuan","Cabanatuan","user@gmail.com","Active","99959532","234234","None","2003-01-19","cabanatuan city","2016-11-06","Developer","Teaching","2550.00","5000.00","Unpaid");
INSERT INTO clients VALUES("15","18","695339834","test","test","test","","","","","","Active","","","","2023-11-29","","","test","","0.00","0.00","");
INSERT INTO clients VALUES("17","20","635038887","Abi","Master","Hev","","","","","","Active","","","","2023-12-05","","","master","","0.00","0.00","");
INSERT INTO clients VALUES("18","21","632869008","rtge","e5t53","vggfff","gttsf","Single","tevvfvf","rgrgdr","fdfdfd","Active","1223","0","None","2023-12-05","fdgfgf","2023-12-06","tgtdh","Teaching","30000.00","0.00","Unpaid");
INSERT INTO clients VALUES("19","22","606401612","qw","w","qqw","Filipino","Single","wqwq","Cabanatuan","test@gmail.com","Active","2147483647","9821312","None","2023-12-06","Cabanatuan","2023-12-06","oiruwqoe","Teaching","0.00","12312.00","");



CREATE TABLE `loan_applications` (
  `loan_id` int(11) NOT NULL AUTO_INCREMENT,
  `loanNo` int(11) NOT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `date_employed` date DEFAULT NULL,
  `contact_num` int(15) DEFAULT NULL,
  `college` varchar(50) DEFAULT NULL,
  `taxID_num` varchar(25) DEFAULT NULL,
  `loan_type` varchar(15) DEFAULT NULL,
  `work_position` varchar(50) DEFAULT NULL,
  `retirement_year` int(4) DEFAULT NULL,
  `application_date` date NOT NULL,
  `applicant_sign` varchar(255) DEFAULT NULL,
  `applicant_receipt` varchar(255) DEFAULT NULL,
  `application_status` varchar(20) NOT NULL,
  `amount_before` decimal(10,2) NOT NULL,
  `amount_after` varchar(50) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `time_pay` int(11) DEFAULT NULL,
  `loan_term_Type` varchar(15) DEFAULT NULL,
  `dueDate` varchar(50) DEFAULT NULL,
  `action_taken` varchar(20) NOT NULL,
  PRIMARY KEY (`loan_id`),
  UNIQUE KEY `loanNo` (`loanNo`),
  KEY `account_number` (`account_number`),
  CONSTRAINT `loan_applications_ibfk_1` FOREIGN KEY (`account_number`) REFERENCES `clients` (`account_number`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO loan_applications VALUES("89","85083","639132689","Justine Retiro","20","2003-01-19","2023-10-29","963463445","Developer","","Regular","Developer","2025","2023-11-06","","","Rejected","50000.00","51041","Rejected","5","month/s","","Rejected");
INSERT INTO loan_applications VALUES("93","81261","639132689","Justine Retiro","23","0000-00-00","0000-00-00","0","Rap","","Regular","","0","0000-00-00","LogoEtc.png","","Rejected","0.00","0","Rejected","","","","Rejected");
INSERT INTO loan_applications VALUES("95","99539","639132689","Justine Retiro","0","2003-01-19","0000-00-00","0","Rapper","","Regular","","0","0000-00-00","","","Rejected","0.00","0","Rejected","","","","Rejected");
INSERT INTO loan_applications VALUES("96","62574","639132689","Justine Retiro","0","2003-01-19","0000-00-00","0","Rapper","","Regular","","0","0000-00-00","","","Rejected","0.00","0","Rejected","","","","Rejected");
INSERT INTO loan_applications VALUES("97","16771","639132689","Justine Retiro","0","2003-01-19","2023-11-26","141241","Rapper","","Regular","SDAS","2025","2023-12-05","","","Accepted","20000.00","25000","Unpaid","5","year/s","December 05, 2028","Accepted");
INSERT INTO loan_applications VALUES("98","49173","632869008","vggfff rtge","21","2023-12-05","2023-12-05","2147483647","cas","","Regular","teacher","2030","2023-12-05","","","Accepted","30000.00","33000","Unpaid","24","month/s","December 05, 2025","Accepted");
INSERT INTO loan_applications VALUES("99","61701","606401612","qqw qw","0","2023-12-06","2023-12-06","0","","9821312","Providential re","oiruwqoe","0","2023-12-06","","","Pending","0.00","0","","","month/s","","");
INSERT INTO loan_applications VALUES("100","53155","606401612","qqw qw","0","2023-12-06","2023-12-06","0","","9821312","Regular renew","oiruwqoe","0","2023-12-07","","","Pending","0.00","0","","","","","");
INSERT INTO loan_applications VALUES("101","6291","606401612","qqw qw","0","2023-12-06","2023-12-06","0","","9821312","Regular renew","oiruwqoe","0","2023-12-07","","","Pending","0.00","0","","","","","");
INSERT INTO loan_applications VALUES("102","20443","606401612","qqw qw","0","2023-12-06","2023-12-06","0","","9821312","Regular renew","oiruwqoe","0","2023-12-07","","","Pending","0.00","0","","0","month/s","","");
INSERT INTO loan_applications VALUES("103","13363","606401612","qqw qw","0","2023-12-06","2023-12-06","0","","9821312","Regular renew","oiruwqoe","0","2023-12-07","","","Pending","0.00","0","","0","month/s","","");
INSERT INTO loan_applications VALUES("104","58068","606401612","qqw qw","0","2023-12-06","2023-12-06","0","","9821312","Regular renew","oiruwqoe","0","2023-12-07","","","Pending","0.00","0","","","","","");
INSERT INTO loan_applications VALUES("105","1239","606401612","qqw qw","0","2023-12-06","2023-12-06","0","","9821312","Regular renew","oiruwqoe","0","2023-12-07","","","Pending","0.00","0","","","","","");
INSERT INTO loan_applications VALUES("106","6939","606401612","qqw qw","23","2023-12-06","2023-12-06","123","asdas","9821312","Regular renew","oiruwqoe","3231","2023-12-07","qqw qw - 2023-12-07 - asdas.png","qqw qw - 2023-12-07 - asd","Pending","1233.00","1256.63","","23","month/s","","");
INSERT INTO loan_applications VALUES("107","53617","606401612","qqw qw","23","2023-12-06","2023-12-06","12312","das","9821312","Regular","oiruwqoe","1233","2023-12-07","qqw qw - 2023-12-07 - das.jpg","qqw qw - 2023-12-07 - das","Pending","1233.00","1256.63","","23","month/s","","");
INSERT INTO loan_applications VALUES("108","26006","606401612","qqw qw","23","2023-12-06","2023-12-06","31231","ADAS","9821312","Regular","oiruwqoe","3213","2023-12-07","qqw qw - 2023-12-07 - ADAS.png","qqw qw - 2023-12-07 - ADAS.jpg","Pending","1323.00","1348.36","","23","month/s","","");
INSERT INTO loan_applications VALUES("109","22614","606401612","qqw qw","56","2023-12-06","2023-12-06","9876987","hgfkgh","9821312","Regular","oiruwqoe","6756","2023-12-07","qqw qw - 2023-12-07 - hgfkgh.jpg","qqw qw - 2023-12-07 - hgfkgh.png","Pending","5765.00","5875.50","","23","month/s","","");
INSERT INTO loan_applications VALUES("110","84909","606401612","qqw qw","45","2023-12-06","2023-12-06","86758","hjgkhj","9821312","Regular","oiruwqoe","4569","2023-12-07","qqw qw - 2023-12-07 - hjgkhj.png","qqw qw - 2023-12-07 - hjgkhj.jpg","Pending","3454.00","3514.45","","21","month/s","","");
INSERT INTO loan_applications VALUES("111","46322","639132689","Justine Retiro","25","2003-01-19","2016-11-06","123124","hgfkgh","234234","Regular","Developer","2025","2023-12-13","Justine Retiro - 2023-12-13 - hgfkgh.jpg","Justine Retiro - 2023-12-13 - hgfkgh.jpg","Accepted","2500.00","2550.00","Unpaid","24","month/s","December 13, 2025","Accepted");



CREATE TABLE `loan_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `loanNo` int(11) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `audit_description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `idx_loan_id` (`loanNo`),
  CONSTRAINT `loan_payments_ibfk_1` FOREIGN KEY (`loanNo`) REFERENCES `loan_applications` (`loanNo`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO loan_payments VALUES("31","16771","639132689","Paid Partially","22500.00","2023-12-05","Loan Payment");
INSERT INTO loan_payments VALUES("32","16771","639132689","Paid Fully","0.00","2023-12-05","Loan Payment");
INSERT INTO loan_payments VALUES("33","49173","632869008","Paid Partially","30000.00","2023-12-05","Loan Payment");



CREATE TABLE `transaction_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(50) DEFAULT NULL,
  `loanNo` int(11) NOT NULL,
  `audit_description` varchar(50) DEFAULT NULL,
  `transaction_type` varchar(255) NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_status` varchar(50) NOT NULL,
  PRIMARY KEY (`history_id`),
  KEY `idx_loan_id` (`account_number`),
  CONSTRAINT `transaction_history_ibfk_1` FOREIGN KEY (`account_number`) REFERENCES `clients` (`account_number`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO transaction_history VALUES("72","639132689","85083","Loan Request","Loan","2023-11-06","Rejected");
INSERT INTO transaction_history VALUES("86","639132689","81261","Loan Request","Loan","2023-12-05","Rejected");
INSERT INTO transaction_history VALUES("88","639132689","99539","Loan Request","Loan","2023-12-05","Rejected");
INSERT INTO transaction_history VALUES("89","639132689","62574","Loan Request","Loan","2023-12-05","Rejected");
INSERT INTO transaction_history VALUES("90","639132689","16771","Loan Request","Loan","2023-12-05","Accepted");
INSERT INTO transaction_history VALUES("91","639132689","0","Loan Payment","Loan Payment","2023-12-05","Paid Partially");
INSERT INTO transaction_history VALUES("92","639132689","0","Loan Payment","Loan Payment","2023-12-05","Paid Fully");
INSERT INTO transaction_history VALUES("93","632869008 ","49173","Loan Request","Loan","2023-12-05","Accepted");
INSERT INTO transaction_history VALUES("94","632869008","0","Loan Payment","Loan Payment","2023-12-05","Paid Partially");
INSERT INTO transaction_history VALUES("95","606401612","61701","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("96","606401612","53155","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("97","606401612","6291","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("98","606401612","20443","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("99","606401612","13363","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("100","606401612","58068","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("101","606401612","1239","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("102","606401612","6939","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("103","606401612","53617","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("104","606401612","26006","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("105","606401612","22614","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("106","606401612","84909","Loan Request","Loan","2023-12-07","Pending");
INSERT INTO transaction_history VALUES("107","639132689","46322","Loan Request","Loan","2023-12-13","Accepted");



CREATE TABLE `transaction_remarks` (
  `remark_id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) DEFAULT NULL,
  `remarks` varchar(255) NOT NULL,
  `remark_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`remark_id`),
  KEY `idx_loan_id` (`loan_id`),
  CONSTRAINT `transaction_remarks_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan_applications` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `user_sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(50) NOT NULL,
  `passwords` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `account_number` (`account_number`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users VALUES("6","639132689","$2y$10$.Oixn0KWzP9qj1LeJRvGjeoPym9IczrehGGZeW1SJyiZrUzGInp3e","mem");
INSERT INTO users VALUES("15","660579529","$2y$10$m0BayJZenOf.HgF.cwVevuMxOI8gaNNE2udGUZpppaVDubnFGv632","admin");
INSERT INTO users VALUES("16","602552131","$2y$10$dEYd4zu0Q3sIqnJ.eVegu.SifbnjZl0kpWApos.Z0iOdyi8UhFq6e","master");
INSERT INTO users VALUES("17","612360957","$2y$10$362VhFlUIaB/wyUM.MciauSYaYQvCipFzyZIRV5X2kc1e2Au5PdOS","master");
INSERT INTO users VALUES("18","695339834","$2y$10$qh6.5xZXmyfW0NTSOl8iKeOQEhS7nAwK0ZCJtP1V9BTIhmhQe7u5C","admin");
INSERT INTO users VALUES("20","635038887","$2y$10$OOko8NfB6ho/lJZQrf8cL.5QNRSQhiALcEmJrzNpkZOcjeIbZx9Re","master");
INSERT INTO users VALUES("21","632869008","$2y$10$2KtcCV/gsq2E7PenNGYGF.6uygoGAmo8ai948DInG8DcsUuATn2Hq","mem");
INSERT INTO users VALUES("22","606401612","$2y$10$fyLFbruNirznUdJ5XJ44x.lQEBnkBKzui.XJfTegU4GioKk/0q6tO","mem");

