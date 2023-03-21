/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.5.16-log : Database - sis_data
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sis_data` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `sis_data`;

/*Table structure for table `bank_transactions` */

DROP TABLE IF EXISTS `bank_transactions`;

CREATE TABLE `bank_transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_date` datetime NOT NULL,
  `date_recorded` datetime NOT NULL,
  `transaction_type` tinyint(1) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `recorded_by_user_id` int(11) NOT NULL,
  `notes` text,
  PRIMARY KEY (`transaction_id`),
  KEY `recorded_by_user_id` (`recorded_by_user_id`),
  CONSTRAINT `bank_transactions_ibfk_1` FOREIGN KEY (`recorded_by_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `bank_transactions` */

insert  into `bank_transactions`(`transaction_id`,`transaction_date`,`date_recorded`,`transaction_type`,`amount`,`recorded_by_user_id`,`notes`) values (4,'2016-09-28 00:00:00','2016-09-28 19:29:57',1,'30000',1,'Deposited by Titus'),(5,'2016-09-28 00:00:00','2016-09-28 19:50:56',1,'5000',1,'from payment for uniforms'),(6,'2016-09-28 00:00:00','2016-09-28 19:51:18',-1,'10000',1,'for Salaries');

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(45) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) values ('f6bfc4545683ca34402ff20cd9620925','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.9.0.2700 Chrome/39.0.2146.0 Safari/',1477459880,'a:3:{s:9:\"user_data\";s:0:\"\";s:7:\"user_id\";s:1:\"1\";s:13:\"user_language\";s:5:\"en-GB\";}');

/*Table structure for table `class_teachers` */

DROP TABLE IF EXISTS `class_teachers`;

CREATE TABLE `class_teachers` (
  `class_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  KEY `fk_levels_has_Staff_levels1` (`class_id`),
  KEY `fk_levels_has_Staff_Staff1` (`staff_id`),
  CONSTRAINT `fk_levels_has_Staff_levels1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_levels_has_Staff_Staff1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `class_teachers` */

/*Table structure for table `classes` */

DROP TABLE IF EXISTS `classes`;

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `class_index` int(11) NOT NULL,
  `class_fees` int(11) NOT NULL,
  `class_section` varchar(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `classes` */

insert  into `classes`(`class_id`,`class_name`,`class_index`,`class_fees`,`class_section`) values (18,'Primary 1',3,45000,'English'),(19,'Nusery 1',1,45000,'English'),(20,'Nusery 2',2,45000,'English'),(22,'Primary 2',4,40000,'English'),(23,'Primary 3',5,40000,'English'),(24,'Primary 4',6,40000,'English'),(25,'Primary 5',7,40000,'English'),(26,'Primary 6',8,50000,'English'),(27,'Petite Section',1,45000,'French'),(28,'Grande Section',2,45000,'French'),(29,'La CIL',3,40000,'French'),(30,'Cout Preparatoire',4,40000,'French'),(31,'Cout Elementaire 1',5,40000,'French'),(32,'Cout Elementaire 2',6,40000,'French'),(33,'Cout Moyen 1',7,40000,'French'),(34,'Cout Moyen 2',8,50000,'French'),(35,'Pre-Nursery',0,45000,'English');

/*Table structure for table `collection_type_costs` */

DROP TABLE IF EXISTS `collection_type_costs`;

CREATE TABLE `collection_type_costs` (
  `type_cost_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `cost` decimal(10,0) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`type_cost_id`,`type_id`),
  KEY `fk_collection_type_costs_collection_types1_idx` (`type_id`),
  CONSTRAINT `fk_collection_type_costs_collection_types1` FOREIGN KEY (`type_id`) REFERENCES `collection_types` (`type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `collection_type_costs` */

/*Table structure for table `collection_types` */

DROP TABLE IF EXISTS `collection_types`;

CREATE TABLE `collection_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `cost` int(11) DEFAULT NULL,
  `is_different_cost_per_class` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_expense` tinyint(1) NOT NULL DEFAULT '0',
  `is_system_type` tinyint(1) DEFAULT '0',
  `is_not_student_related` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `collection_types` */

insert  into `collection_types`(`type_id`,`type_name`,`cost`,`is_different_cost_per_class`,`is_deleted`,`is_expense`,`is_system_type`,`is_not_student_related`) values (1,'Uniforms',5000,0,0,0,0,0),(2,'Report Cards',1000,0,0,0,0,0),(3,'Exam fees',2500,1,0,0,0,0),(4,'School  Fees',NULL,0,0,0,1,0),(5,'Salaries',NULL,0,0,1,1,1),(6,'Internet Bills',25000,0,0,1,0,1),(7,'Electricity Bills',5000,0,0,1,0,1),(8,'Purchase of office equipment',150000,0,0,1,0,1),(9,'Transportation',5000,0,0,1,0,1),(10,'Water bills',13700,0,0,1,0,1),(11,'Graduation Party',1200,0,0,1,0,0),(12,'sdasd',0,0,1,1,0,0),(13,'sdasd',43,0,1,1,0,0);

/*Table structure for table `collections` */

DROP TABLE IF EXISTS `collections`;

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `amount_due` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `academic_year` varchar(15) COLLATE latin1_general_ci DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `salary_month_index` int(11) DEFAULT NULL,
  `notes` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`collection_id`,`type_id`),
  KEY `fk_collections_1_idx` (`class_id`),
  KEY `fk_collections_2_idx` (`student_id`),
  CONSTRAINT `fk_collections_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_collections_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `collections` */

insert  into `collections`(`collection_id`,`type_id`,`amount_due`,`academic_year`,`class_id`,`student_id`,`staff_id`,`salary_month_index`,`notes`) values (1,7,'4500','2016/2017',NULL,NULL,NULL,NULL,'');

/*Table structure for table `courses` */

DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `course_code` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `course_description` text COLLATE latin1_general_ci,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`course_id`),
  KEY `fk_Courses_classes1` (`class_id`),
  CONSTRAINT `fk_Courses_classes1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `courses` */

/*Table structure for table `deductions` */

DROP TABLE IF EXISTS `deductions`;

CREATE TABLE `deductions` (
  `deduction_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci,
  `collection_id` int(11) NOT NULL,
  `date_recorded` datetime NOT NULL,
  `recorded_by_user_id` int(11) NOT NULL,
  PRIMARY KEY (`deduction_id`),
  KEY `fk_user` (`recorded_by_user_id`),
  CONSTRAINT `fk_deductions_2` FOREIGN KEY (`recorded_by_user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `deductions` */

/*Table structure for table `permission_groups` */

DROP TABLE IF EXISTS `permission_groups`;

CREATE TABLE `permission_groups` (
  `group_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `group_permission_value` int(11) NOT NULL,
  `group_description` text COLLATE latin1_general_ci,
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `permission_groups` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `permission_level` int(11) NOT NULL,
  PRIMARY KEY (`permission_id`,`permission_level`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `permissions` */

/*Table structure for table `resources` */

DROP TABLE IF EXISTS `resources`;

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `language` enum('en-GB','fr-FR') COLLATE latin1_general_ci NOT NULL DEFAULT 'en-GB',
  `resource_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `resource_value` varchar(150) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB AUTO_INCREMENT=348 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `resources` */

insert  into `resources`(`resource_id`,`language`,`resource_name`,`resource_value`) values (1,'en-GB','user_name','Username: '),(2,'en-GB','password','Password'),(3,'en-GB','login','Login'),(4,'en-GB','username_or_password_incorrect','Username or Password is incorrect !!!'),(5,'en-GB','logout','Logout'),(6,'en-GB','students','Students'),(7,'en-GB','subjects','Subjects'),(8,'en-GB','classses','Classes'),(9,'en-GB','staff','Staff'),(10,'en-GB','enrollments','Enrollments'),(11,'en-GB','fee_payments','Fee Payments'),(12,'en-GB','salary_payments','Salary Payments'),(13,'en-GB','users','Users'),(14,'en-GB','school_info','School Info'),(15,'en-GB','new_student','New Student'),(16,'en-GB','view','View'),(17,'en-GB','edit','Edit'),(18,'en-GB','student_number','Student Number'),(19,'en-GB','student_name','Student Name'),(20,'en-GB','date_of_birth','Date of Birth'),(21,'en-GB','gender','Gender'),(22,'en-GB','language','Language'),(23,'en-GB','english','English'),(24,'en-GB','french','French'),(25,'en-GB','picture_optional','Picture (Optional)'),(26,'en-GB','save','Save'),(27,'en-GB','dd_mm_yyyy','(dd/mm/yyyy)'),(28,'en-GB','delete','Delete'),(29,'en-GB','yes','Yes'),(30,'en-GB','no','No'),(31,'en-GB','are_you_sure','Are you sure?'),(32,'en-GB','class','Class'),(33,'en-GB','search','Search'),(34,'en-GB','new_subject','New Subject'),(35,'en-GB','subject_code','Subject Code'),(36,'en-GB','subject_name','Subject Name'),(37,'en-GB','subject_description','Subject Description'),(38,'en-GB','classes','Classes'),(39,'en-GB','new_class','New Class'),(40,'en-GB','section','Section'),(41,'en-GB','index','Index'),(42,'en-GB','class_name','Class Name'),(43,'en-GB','fees','Fees'),(44,'en-GB','teacher_optional','Teacher Optional'),(45,'en-GB','teacher','Teacher'),(46,'en-GB','fees_cfa','Fees (CFA)'),(47,'en-GB','new_staff','New Staff'),(48,'en-GB','name','Name'),(49,'en-GB','role','Role'),(50,'en-GB','male','Male'),(51,'en-GB','female','Female'),(52,'en-GB','required_fields','Required Fields'),(53,'en-GB','optional_fields','Optional Fields'),(54,'en-GB','staff_name','Staff Name'),(55,'en-GB','salary','Salary'),(56,'en-GB','qualification','Qualification'),(57,'en-GB','email','Email'),(58,'en-GB','phone_number','Phone Number'),(59,'en-GB','address','Address'),(60,'en-GB','class_enrollments','Class Enrollments'),(61,'en-GB','academic_year','Academic Year'),(62,'en-GB','new_enrollment','New Enrollment'),(63,'en-GB','class_teacher','Class Teacher'),(64,'en-GB','no_students_in_this_class','No students in this class and academic year'),(65,'en-GB','students_in','Students in'),(66,'en-GB','student_course_list','Student Course List'),(67,'en-GB','promote_student','Promote Student'),(68,'en-GB','score','Score'),(69,'en-GB','grade','Grade'),(70,'en-GB','are_you_sure_to_promote','Are you sure you want to promote %student to %class in %academic_year?'),(71,'en-GB','search_for_students_to_enroll','Search for Student to Enroll'),(72,'en-GB','new_payment','New Payment'),(73,'en-GB','payment_date','Date Recorded'),(74,'en-GB','paid_by','Paid by'),(75,'en-GB','amount','Amount'),(76,'en-GB','is_scholarship','Is Scholarship'),(77,'en-GB','amount_due','Amount Due'),(78,'en-GB','amount_paid','Amount Paid'),(79,'en-GB','back','Back'),(80,'en-GB','no_students','No Students'),(81,'en-GB','paid_to','Paid To'),(82,'en-GB','amount_cfa','Amount (CFA)'),(83,'en-GB','purpose_of_payment','Purpose of Payment'),(84,'en-GB','required_fields_validation','%s is required'),(85,'en-GB','invalid_fields_validation','$s is invalid'),(86,'en-GB','is_numeric_validation','%s must contain only numbers'),(87,'en-GB','new_user','New User'),(88,'en-GB','permission_groups','Permission Groups'),(89,'en-GB','full_name','Full Name'),(90,'en-GB','permission','Permission'),(91,'en-GB','is_admin','Is Admin'),(92,'en-GB','group_name','Group Name'),(93,'en-GB','new_permission_group','New Permission Group'),(94,'en-GB','group_description','Group Description'),(95,'en-GB','group_permissions','Group Permissions'),(96,'fr-FR','user_name','Nom d\'utilisateur'),(97,'fr-FR','password','Mot de passe'),(98,'fr-FR','login','Connexion'),(99,'fr-FR','username_or_password_incorrect','Nom d\'utilisateur ou mot de passe n\'est pas correcte'),(100,'fr-FR','logout','Deconnexion'),(101,'fr-FR','students','Ã©lÃ¨ves'),(102,'fr-FR','subjects','matiÃ¨res'),(103,'fr-FR','classses','classes'),(104,'fr-FR','staff','personnel'),(105,'fr-FR','enrollments','inscriptions'),(106,'fr-FR','fee_payments','pensions'),(107,'fr-FR','salary_payments','salaires'),(108,'fr-FR','users','utilisateurs'),(109,'fr-FR','school_info','infos sur l\'Ã©cole'),(110,'fr-FR','new_student','nouvel Ã©lÃ¨ve'),(111,'fr-FR','view','voir'),(112,'fr-FR','edit','modifier'),(113,'fr-FR','student_number','matricule d\'Ã©lÃ¨ve'),(114,'fr-FR','student_name','nom d\'Ã©lÃ¨ve'),(115,'fr-FR','date_of_birth','date de naissance'),(116,'fr-FR','gender','sexe'),(117,'fr-FR','language','Langue'),(118,'fr-FR','english','anglais'),(119,'fr-FR','french','franÃ§ais'),(120,'fr-FR','picture_optional','photo (facultatif)'),(121,'fr-FR','save','enregistrer'),(122,'fr-FR','dd_mm_yyyy','jj/mm/aaaa'),(123,'fr-FR','delete','supprimer'),(124,'fr-FR','yes','Oui'),(125,'fr-FR','no','Non'),(126,'fr-FR','are_you_sure','Ãªtes-vous sÃ»r?'),(127,'fr-FR','class','classe'),(128,'fr-FR','search','recherche'),(129,'fr-FR','new_subject','nouveau matiÃ¨res'),(130,'fr-FR','subject_code','code'),(131,'fr-FR','subject_name','matiÃ¨res'),(132,'fr-FR','subject_description','description'),(133,'fr-FR','classes','classes'),(134,'fr-FR','new_class','nouvelle classe'),(135,'fr-FR','section','Section'),(136,'fr-FR','index','Index'),(137,'fr-FR','class_name','Classe'),(138,'fr-FR','fees','pension'),(139,'fr-FR','teacher_optional','maÃ®tre (facultatif)'),(140,'fr-FR','teacher','maÃ®tre'),(141,'fr-FR','fees_cfa','pension (CFA)'),(142,'fr-FR','new_staff','nouveau personnel'),(143,'fr-FR','name','Nom'),(144,'fr-FR','role','rÃ´le'),(145,'fr-FR','male','masculin'),(146,'fr-FR','female','fÃ©minin'),(147,'fr-FR','required_fields','obligatoires'),(148,'fr-FR','optional_fields','facultatifs'),(149,'fr-FR','staff_name','nom'),(150,'fr-FR','salary','Salaire'),(151,'fr-FR','qualification','Qualification'),(152,'fr-FR','email','e-mail'),(153,'fr-FR','phone_number','numÃ©ro de tÃ©lÃ©phone'),(154,'fr-FR','address','addresse'),(155,'fr-FR','class_enrollments','inscriptions'),(156,'fr-FR','academic_year','annÃ©e scolaire'),(157,'fr-FR','new_enrollment','Nouvelle inscription'),(158,'fr-FR','class_teacher','maÃ®tre du class'),(159,'fr-FR','no_students_in_this_class','pas d\'Ã©lÃ¨ves dans cette classe'),(160,'fr-FR','students_in','Ã©lÃ¨ves dans'),(161,'fr-FR','student_course_list','Liste des cours d\'Ã©lÃ¨ve'),(162,'fr-FR','promote_student','envoyer l\'Ã©lÃ¨ve a la classe suivante'),(163,'fr-FR','score','Score'),(164,'fr-FR','grade','Mention'),(165,'fr-FR','are_you_sure_to_promote','Ãªtes-vous sÃ»r que vous voulez promouvoir %student Ã  %class pour l\'annÃ©e scolaire $academic_year?'),(166,'fr-FR','search_for_students_to_enroll','chercher des Ã©lÃ¨ves de se inscrire'),(167,'fr-FR','new_payment','nouveau paiement'),(168,'fr-FR','payment_date','date d\'enregistrement'),(169,'fr-FR','paid_by','payÃ© par'),(170,'fr-FR','amount','montant'),(171,'fr-FR','is_scholarship','est la bourse'),(172,'fr-FR','amount_due','montant dÃ»'),(173,'fr-FR','amount_paid','le montant payÃ©'),(174,'fr-FR','back','rentrez'),(175,'fr-FR','no_students','aucun Ã©lÃ¨ves '),(176,'fr-FR','paid_to','versÃ©e Ã '),(177,'fr-FR','amount_cfa','montant (CFA)'),(178,'fr-FR','purpose_of_payment','payÃ© pour'),(179,'fr-FR','required_fields_validation','%s est nÃ©cessaire'),(180,'fr-FR','invalid_fields_validation','%s est invalide'),(181,'fr-FR','is_numeric_validation','%s doit Ãªtre numÃ©rique'),(182,'fr-FR','new_user','nouvel utilisateur'),(183,'fr-FR','permission_groups','groupes d\'autorisations'),(184,'fr-FR','full_name','nom et prÃ©nom'),(185,'fr-FR','permission','Permission'),(186,'fr-FR','is_admin','Admin'),(187,'fr-FR','group_name','nom de groupe'),(188,'fr-FR','new_permission_group','nouveau groupe d\'autorisation'),(189,'fr-FR','group_description','description du groupe'),(190,'fr-FR','group_permissions','permissions de groupe'),(223,'en-GB','new_student_created','New Student Created'),(224,'fr-FR','new_student_created','Nouvelle Ã©lÃ¨ve crÃ©Ã© avec succÃ¨s'),(225,'en-GB','edit_or_save_the_proposed_student_number','Edit or save the proposed Student Number'),(226,'fr-FR','edit_or_save_the_proposed_student_number','Modifier ou enregistrer le matricule d\'Ã©lÃ¨ve proposÃ©'),(227,'en-GB','nationality','Nationality'),(228,'fr-FR','nationality','NationalitÃ©'),(229,'en-GB','student','Student'),(230,'fr-FR','student','Ã©lÃ¨ve'),(231,'en-GB','enroll_student','Enroll Student'),(232,'fr-FR','enroll_student','s\'inscrire l\'Ã©lÃ¨ve'),(233,'en-GB','no_class_in_database','No class in database'),(234,'fr-FR','no_class_in_database','aucune classe '),(235,'en-GB','subject','Subject'),(236,'fr-FR','subject','MatiÃ¨re'),(237,'en-GB','ok','OK'),(238,'fr-FR','ok','OK'),(239,'fr-FR','is_already_in','est deja en'),(240,'en-GB','for','for'),(241,'en-GB','is_already_in','is already in'),(242,'fr-FR','for','pour'),(243,'en-GB','none','None'),(244,'fr-FR','none','aucun'),(245,'en-GB','january','January'),(246,'en-GB','february','February'),(247,'en-GB','march','March'),(248,'en-GB','april','April'),(249,'en-GB','may','May'),(250,'en-GB','june','June'),(251,'en-GB','july','July'),(252,'en-GB','august','August'),(253,'en-GB','september','September'),(254,'en-GB','october','October'),(255,'en-GB','november','November'),(256,'en-GB','december','December'),(257,'fr-FR','january','Janvier'),(258,'fr-FR','february','Fevrier'),(259,'fr-FR','march','Mars'),(260,'fr-FR','april','Avril'),(261,'fr-FR','may','Mai'),(262,'fr-FR','june','Juin'),(263,'fr-FR','july','Julliet'),(264,'fr-FR','august','Aout'),(265,'fr-FR','september','Septembre'),(266,'fr-FR','october','Octobre'),(267,'fr-FR','november','Novembre'),(268,'fr-FR','december','Decembre'),(269,'en-GB','unique_field_validation','%s must be unique'),(270,'fr-FR','unique_field_validation','%s doit etre unique'),(271,'en-GB','subject_code_and_name_must_be_unique_for','Subject code, and Subject Name must be unique for this class'),(272,'fr-FR','subject_code_and_name_must_be_unique_for','code et nom doivent etre unique pour cette classe'),(273,'en-GB','school_name','School Name'),(274,'fr-FR','school_name','Nom d\'ecole'),(275,'en-GB','time_zone','Time Zone'),(276,'fr-FR','time_zone','Region de temps'),(277,'en-GB','keyword','Keyword'),(278,'fr-FR','keyword','Mot du recherche'),(279,'en-GB','student_already_in_class_and_year','The student is already in the specified class for the specified academic year'),(280,'fr-FR','student_already_in_class_and_year','L\'Ã©lÃ¨ve est deja dans la classe pour l\'annÃ¨e scolaire donnÃ©'),(283,'en-GB','provided_date_invalid','Provided date is invalid'),(284,'fr-FR','provided_date_invalid','La date n\'est pas correcte'),(285,'en-GB','no_class_after','There is no registered class after '),(286,'fr-FR','no_class_after','pas de classe apres  '),(287,'en-GB','fees_paid','Fees Paid'),(288,'fr-FR','fees_paid','Pension payÃ©s'),(289,'en-GB','status','Status'),(290,'fr-FR','status','statut'),(291,'fr-FR','incomplete','incomplet'),(292,'fr-FR','unpaid','impayÃ©e'),(293,'fr-FR','complete','complet'),(294,'en-GB','complete','Complete'),(295,'en-GB','unpaid','Unpaid'),(296,'en-GB','incomplete','Incomplete'),(297,'fr-FR','phone_number','numÃ©ro de tÃ©lÃ©phone'),(298,'en-GB','logo','Logo'),(299,'fr-FR','logo','logo'),(300,'en-GB','recorded_by','Recorded By'),(301,'fr-FR','recorded_by','enregistrÃ© par'),(302,'fr-FR','all_students','tous les Ã©lÃ¨ves'),(303,'en-GB','all_students','All Students'),(304,'fr-FR','scholarships','les bourses'),(305,'en-GB','scholarships','Scholarships'),(306,'fr-FR','amount_owed','dette'),(307,'en-GB','amount_owed','Amount Owed'),(308,'en-GB','date_recorded','Date Recorded'),(309,'fr-FR','date_recorded','date d\'enregistration'),(310,'fr-FR','scholarship_amount','montant de la bourse'),(311,'en-GB','scholarship_amount','Scholarship Amount'),(312,'fr-FR','enrollment_for','inscription pour'),(313,'en-GB','enrollment_for','Enrollment for'),(314,'fr-FR','award_scholarship','donner une bourse'),(315,'en-GB','award_scholarship','Award Scholarship'),(316,'en-GB','view_scholarship','View Scholarship'),(317,'fr-FR','view_scholarship','voyez la bourse'),(318,'fr-FR','pay_fees','payer les frais'),(319,'en-GB','pay_fees','Pay Fees'),(320,'fr-FR','home','accueil'),(321,'en-GB','home','Home'),(322,'en-GB','enrollment_stats','Enrollment Statistics'),(323,'fr-FR','enrollment_stats','Statistiques inscription'),(324,'fr-FR','enrollment_count','Nombre d\'inscriptions'),(325,'en-GB','enrollment_count','Enrollment Count'),(326,'en-GB','completed_fee_payments','Completed Fee Payments'),(327,'fr-FR','completed_fee_payments','pensions complets'),(328,'en-GB','incomplete_payments','Incomplete Payments'),(329,'fr-FR','incomplete_payments','pensions incomplets'),(330,'en-GB','unpaid_enrollments','Unpaid Enrollments'),(331,'fr-FR','unpaid_enrollments','inscriptions impayÃ©es'),(332,'en-GB','Unavailable','Unavailable'),(333,'fr-FR','Unavailable','indisponible'),(334,'en-GB','Unavailable','Unavailable'),(335,'fr-FR','Unavailable','indisponible'),(336,'en-GB','reason','Reason'),(337,'fr-FR','reason','raison'),(338,'en-GB','reason','Reason'),(339,'fr-FR','reason','raison'),(340,'fr-FR','total_amount_expected','Montant total prÃ©vu'),(341,'en-GB','total_amount_expected','Total Amount expected'),(342,'en-GB','total_amount_paid','Total Amount Paid'),(343,'fr-FR','total_amount_paid','Montant total payÃ©'),(344,'en-GB','total_scholarship_awarded','Total Scholarship Awarded'),(345,'fr-FR','total_scholarship_awarded','bourse total'),(346,'en-GB','total_amount_pending','Total Pending'),(347,'fr-FR','total_amount_pending','montant restant');

/*Table structure for table `salary_payments` */

DROP TABLE IF EXISTS `salary_payments`;

CREATE TABLE `salary_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `salary_month` datetime NOT NULL,
  `academic_year` varchar(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `fk_SalaryPayments_Staff1` (`staff_id`),
  CONSTRAINT `fk_SalaryPayments_Staff1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `salary_payments` */

/*Table structure for table `school_info` */

DROP TABLE IF EXISTS `school_info`;

CREATE TABLE `school_info` (
  `school_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `phone_number` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `address` text COLLATE latin1_general_ci,
  `logo` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `time_zone` tinyint(5) DEFAULT NULL,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `school_info` */

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `setting_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `setting_value` varchar(45) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `settings` */

insert  into `settings`(`setting_name`,`setting_value`) values ('salaries Collection ID','5'),('School Fees Collection Id','4');

/*Table structure for table `staff` */

DROP TABLE IF EXISTS `staff`;

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_qualification` text COLLATE latin1_general_ci,
  `title` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `salary` int(11) NOT NULL,
  `staff_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `phone_number` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `address` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `staff_role` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `staff` */

insert  into `staff`(`staff_id`,`staff_qualification`,`title`,`salary`,`staff_name`,`email`,`phone_number`,`date_of_birth`,`address`,`gender`,`staff_role`) values (1,NULL,NULL,100000,'CHIME FRANCINE YASMINE','admin.charisbnpsa@gmail.com',NULL,NULL,NULL,'female','SECRETARY / BURSAR'),(2,'',NULL,100000,'PHILLIPINE NAYANG','headmaster.charibnps@gmail.com','675963581',NULL,'','female','HEADMISTRESS'),(3,NULL,NULL,40000,'Pelagie Ebong','',NULL,NULL,NULL,'male','Teacher CP'),(4,'',NULL,35000,'Christine Djimo','','677050282',NULL,'','female','Teacher - Nursery 2'),(5,'',NULL,35000,'Rose Ebangha','','675194231',NULL,'','female','Teacher - Nursery 1'),(6,'',NULL,50000,'Federick Adi','','675808139',NULL,'','male','Teacher - Class 6'),(7,'',NULL,35000,'Mirene Fanyep','','6796410019',NULL,'','female','Teacher - CE2'),(8,'',NULL,40000,'Takem Berthe','','675905009',NULL,'NDOBO/BEPELE','female','Teacher - MGS'),(9,NULL,NULL,45000,'Brigitte Tchamtieu','',NULL,NULL,NULL,'female','Teacher - CM1 '),(10,'',NULL,35000,'Sylvianne Tanko','','',NULL,'','female','Teacher - MPS'),(11,'',NULL,35000,'Peter Guibolo','','',NULL,'','male','Teacher - CE1'),(12,'',NULL,45000,'Evelyn Ndawa','','',NULL,'','female','Teacher - Class 1'),(13,'',NULL,37000,'Emilia Ngalle','','670175094',NULL,'','female','Teacher - Class 4'),(14,'',NULL,35000,'Eveline Nubia','','',NULL,'','female','Teacher - Class 3'),(15,'',NULL,40000,'Vivian Domi','','675434852',NULL,'','female','Teacher - Class 2'),(16,NULL,NULL,45000,'Alliance F Fomekong','',NULL,NULL,NULL,'male','Teacher - SIL'),(17,'',NULL,40000,'John Etah','','678072970',NULL,'','male','Teacher - Class 5'),(18,NULL,NULL,50000,'James D. Moen',NULL,NULL,NULL,NULL,'male','Gate Man'),(19,NULL,NULL,25000,'Tita Fon',NULL,NULL,NULL,NULL,'male','Gardener');

/*Table structure for table `student_guardians` */

DROP TABLE IF EXISTS `student_guardians`;

CREATE TABLE `student_guardians` (
  `guardian_name` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `relationship` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `student_guardian_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `phone_number` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `date_of_birth` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`student_guardian_id`),
  KEY `fk_persons_has_Students_Students1` (`student_id`),
  CONSTRAINT `fk_persons_has_Students_Students1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `student_guardians` */

/*Table structure for table `students` */

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') COLLATE latin1_general_ci NOT NULL,
  `picture` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `language` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `student_number` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `guardian_name` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `guardian_phone_number` varchar(45) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `students` */

insert  into `students`(`student_id`,`student_name`,`date_of_birth`,`gender`,`picture`,`language`,`student_number`,`date_created`,`guardian_name`,`guardian_phone_number`) values (1,'Megoze Hilary Mathew','1985-06-11','male',NULL,'','C00012017','2016-09-19 19:59:02','',''),(2,'James Ikechukwu','2009-06-02','male',NULL,'','C00022017','2016-09-19 19:58:29','',''),(3,'Mbah Ndam H.','2005-02-01','male',NULL,'','C00032017','2016-09-28 19:25:58','Mbah Rolins','678494837');

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `collection_id` int(11) DEFAULT NULL,
  `amount` decimal(10,0) NOT NULL,
  `collection_type_id` int(11) NOT NULL,
  `is_input` tinyint(1) NOT NULL,
  `date_recorded` datetime NOT NULL,
  `recorded_by_user_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`transaction_id`),
  KEY `fk_transactions_1_idx` (`collection_type_id`),
  KEY `fk_transactions_2_idx` (`collection_id`),
  CONSTRAINT `fk_transactions_1` FOREIGN KEY (`collection_type_id`) REFERENCES `collection_types` (`type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_transactions_2` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`collection_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `transactions` */

insert  into `transactions`(`transaction_id`,`collection_id`,`amount`,`collection_type_id`,`is_input`,`date_recorded`,`recorded_by_user_id`) values (1,1,'4500',7,0,'2016-10-21 00:00:00',1);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `full_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `phone_number` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `permission_level` int(11) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `language` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `permission_level` (`permission_level`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`username`,`password`,`full_name`,`email`,`phone_number`,`permission_level`,`is_admin`,`language`) values (1,'titus','Jesus123','Yusinyu Titus Nsami','titus@suresoft.cm','679684428',65535,1,'en-GB'),(3,'chime','yasminecharis','Chime Francine Yasmine','','',65535,1,'en-GB'),(5,'Godlove','2015godlove','Godlove Ngwa','','',65535,0,'en-GB'),(6,'headmaster','charisheadmaster','Head Master','','',20419,0,'en-GB');

/* Function  structure for function  `get_bank_account_balance` */

/*!50003 DROP FUNCTION IF EXISTS `get_bank_account_balance` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `get_bank_account_balance`() RETURNS int(11)
    DETERMINISTIC
BEGIN
SET @balance = 
(SELECT SUM(amount * transaction_type) FROM bank_transactions WHERE cutt_off_id = 0 OR transaction_id >= cutt_off_id);
RETURN  COALESCE(@balance, 0);
    END */$$
DELIMITER ;

/* Function  structure for function  `get_collection_type_cost` */

/*!50003 DROP FUNCTION IF EXISTS `get_collection_type_cost` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `get_collection_type_cost`(collection_type_id INT, target_class_id INT) RETURNS int(11)
    DETERMINISTIC
BEGIN
SET @cost1 = 
(SELECT cost FROM collection_types
WHERE type_id = collection_type_id );
SET @cost2 = 
(SELECT class_cost FROM detailed_collection_type
WHERE type_id = collection_type_id AND class_id = target_class_id AND is_different_cost_per_class = 1);
RETURN  COALESCE(@cost2, COALESCE(@cost1, 0));
END */$$
DELIMITER ;

/* Function  structure for function  `get_staff_salary` */

/*!50003 DROP FUNCTION IF EXISTS `get_staff_salary` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `get_staff_salary`(this_staff_id INT, this_salary_month_index INT, this_academic_year VARCHAR(10)) RETURNS int(11)
    DETERMINISTIC
BEGIN
SET @cost1 = (SELECT amount_due FROM collection_details
WHERE staff_id = this_staff_id AND salary_month_index = this_salary_month_index AND academic_year = this_academic_year LIMIT 1);
RETURN COALESCE(@cost1, 0);
END */$$
DELIMITER ;

/* Function  structure for function  `get_yearly_salary` */

/*!50003 DROP FUNCTION IF EXISTS `get_yearly_salary` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `get_yearly_salary`( this_staff_id INT, this_academic_year VARCHAR (12) ) RETURNS int(11)
    DETERMINISTIC
BEGIN
   SET @yearly_salary = (SELECT SUM(COALESCE(amount_paid, 0)) FROM collection_details WHERE staff_id = this_staff_id AND academic_year = this_academic_year);
   RETURN COALESCE(@yearly_salary, 0);
END */$$
DELIMITER ;

/* Procedure structure for procedure `delete_collection` */

/*!50003 DROP PROCEDURE IF EXISTS  `delete_collection` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_collection`( col_id int)
begin
DELETE FROM transactions WHERE collection_id = col_id;
DELETE FROM deductions WHERE collection_id = col_id;
DELETE FROM collections WHERE collection_id = col_id;
end */$$
DELIMITER ;

/* Procedure structure for procedure `get_all_academic_years` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_all_academic_years` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_academic_years`()
    READS SQL DATA
begin
SELECT DISTINCT academic_year FROM collections order by academic_year DESC;
end */$$
DELIMITER ;

/* Procedure structure for procedure `get_collections_per_class` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_collections_per_class` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `get_collections_per_class`(IN fees_col_type INT, IN target_col_type INT, IN class_id INT,  IN academic_year VARCHAR(10))
BEGIN
SELECT COALESCE(cd2.`collection_id`, 0) AS collection_id, cd.`student_id`, cd.student_name, cd.`class_id`, cd.`class_name`, 
COALESCE(cd2.`academic_year`, cd.`academic_year`) AS academic_year, 
COALESCE(cd2.`type_id`, ct.type_id) AS type_id, 
COALESCE(cd2.`type_name`, ct.type_name) AS type_name, 
COALESCE(cd2.`amount_due`, get_collection_type_cost(target_col_type, class_id) )as amount_due,
COALESCE( cd2.`amount_paid`, 0) AS amount_paid, 
COALESCE(  cd2.`amount_owed`, 
COALESCE(cd2.`amount_due`, get_collection_type_cost(target_col_type, class_id) )
- COALESCE( cd2.`amount_paid`, 0) ) AS amount_owed
FROM collection_details cd
LEFT JOIN collection_details cd2 ON cd.`student_id` = cd2.`student_id` AND cd.`class_id` = cd2.`class_id` 
AND cd.`academic_year` = cd2.`academic_year` AND cd2.`type_id` = target_col_type
left join collection_types ct on ct.type_id = target_col_type
WHERE cd.`type_id` = fees_col_type AND cd.`class_id` = class_id AND cd.academic_year = academic_year;
END */$$
DELIMITER ;

/* Procedure structure for procedure `get_finance_summary` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_finance_summary` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `get_finance_summary`( this_academic_year varchar(10))
begin
SELECT 
ct.type_id,
ct.type_name, 
COALESCE(fs.academic_year, this_academic_year) as academic_year,
COALESCE(fs.amount, 0) as amount,
COALESCE(fs.is_expense, ct.is_expense) as is_expense
FROM 
collection_types ct LEFT JOIN 
(SELECT * FROM finance_summary WHERE academic_year = this_academic_year) fs ON ct.type_id = fs.type_id
order by is_expense;
end */$$
DELIMITER ;

/* Procedure structure for procedure `get_next_class` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_next_class` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `get_next_class`(
in classss_id int
)
begin
SELECT * FROM classes 
                WHERE class_index =  (
                SELECT class_index FROM classes WHERE class_id = classss_id+ 1  
                           AND class_section = (SELECT class_section FROM classes WHERE class_id =  classss_id )
                )  LIMIT 0,1;
end */$$
DELIMITER ;

/* Procedure structure for procedure `get_staff_salaries` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_staff_salaries` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `get_staff_salaries`(IN this_academic_year VARCHAR(20))
BEGIN
SELECT staff_id, staff_name, this_academic_year AS academic_year,
get_staff_salary(staff_id, 1, this_academic_year) AS January,
get_staff_salary(staff_id, 2, this_academic_year) AS February,
get_staff_salary(staff_id, 3, this_academic_year) AS March,
get_staff_salary(staff_id, 4, this_academic_year) AS April,
get_staff_salary(staff_id, 5, this_academic_year) AS May,
get_staff_salary(staff_id, 6, this_academic_year) AS June,
get_staff_salary(staff_id, 7, this_academic_year) AS July,
get_staff_salary(staff_id, 8, this_academic_year) AS August,
get_staff_salary(staff_id, 9, this_academic_year) AS September,
get_staff_salary(staff_id, 10, this_academic_year) AS October,
get_staff_salary(staff_id, 11, this_academic_year) AS November,
get_staff_salary(staff_id, 12, this_academic_year) AS December,
get_yearly_salary (staff_id, this_academic_year) as yearly_total
 FROM staff;
END */$$
DELIMITER ;

/* Procedure structure for procedure `search_staff` */

/*!50003 DROP PROCEDURE IF EXISTS  `search_staff` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `search_staff`(IN keyword1 VARCHAR(50))
BEGIN
DECLARE keyword VARCHAR(50);
SET keyword = CONCAT('%',  keyword1, '%');
SELECT * FROM staff WHERE 
salary LIKE keyword
OR gender  =  keyword1
OR email  LIKE keyword
OR phone_number  LIKE keyword
OR staff_role  LIKE keyword
OR staff_name  LIKE keyword
ORDER BY staff_name;
END */$$
DELIMITER ;

/* Procedure structure for procedure `search_student` */

/*!50003 DROP PROCEDURE IF EXISTS  `search_student` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `search_student`(IN keyword1 VARCHAR(50))
BEGIN
declare keyword varchar(50);
SET keyword = CONCAT('%',  keyword1, '%');
SELECT * FROM students WHERE 
student_name LIKE keyword
OR gender  =  keyword1
OR LANGUAGE  LIKE keyword
OR student_number  LIKE keyword
OR guardian_name  LIKE keyword
OR guardian_phone_number  LIKE keyword 
ORDER BY student_id DESC;
END */$$
DELIMITER ;

/*Table structure for table `collection_details` */

DROP TABLE IF EXISTS `collection_details`;

/*!50001 DROP VIEW IF EXISTS `collection_details` */;
/*!50001 DROP TABLE IF EXISTS `collection_details` */;

/*!50001 CREATE TABLE  `collection_details`(
 `collection_id` int(11) ,
 `type_id` int(11) ,
 `amount_due` varchar(45) ,
 `academic_year` varchar(15) ,
 `class_id` int(11) ,
 `student_id` int(11) ,
 `staff_id` int(11) ,
 `salary_month_index` int(11) ,
 `MONTH` varchar(9) ,
 `staff_name` varchar(50) ,
 `type_name` varchar(45) ,
 `is_expense` tinyint(1) ,
 `student_number` varchar(45) ,
 `student_name` varchar(45) ,
 `date_of_birth` date ,
 `gender` enum('male','female') ,
 `class_name` varchar(45) ,
 `guardian_name` varchar(11) ,
 `guardian_number` varchar(45) ,
 `amount_paid` decimal(32,0) ,
 `deductions` double ,
 `amount_owed` double 
)*/;

/*Table structure for table `collection_summaries` */

DROP TABLE IF EXISTS `collection_summaries`;

/*!50001 DROP VIEW IF EXISTS `collection_summaries` */;
/*!50001 DROP TABLE IF EXISTS `collection_summaries` */;

/*!50001 CREATE TABLE  `collection_summaries`(
 `type_id` int(11) ,
 `academic_year` varchar(15) ,
 `type_name` varchar(45) ,
 `total_due` double ,
 `total_paid` decimal(54,0) ,
 `total_deductions` double ,
 `total_owed` double 
)*/;

/*Table structure for table `collection_type_cost_details` */

DROP TABLE IF EXISTS `collection_type_cost_details`;

/*!50001 DROP VIEW IF EXISTS `collection_type_cost_details` */;
/*!50001 DROP TABLE IF EXISTS `collection_type_cost_details` */;

/*!50001 CREATE TABLE  `collection_type_cost_details`(
 `type_cost_id` int(11) ,
 `class_id` int(11) ,
 `cost` decimal(10,0) ,
 `type_id` int(11) ,
 `class_index` int(11) ,
 `class_name` varchar(45) ,
 `class_section` varchar(20) 
)*/;

/*Table structure for table `deduction_details` */

DROP TABLE IF EXISTS `deduction_details`;

/*!50001 DROP VIEW IF EXISTS `deduction_details` */;
/*!50001 DROP TABLE IF EXISTS `deduction_details` */;

/*!50001 CREATE TABLE  `deduction_details`(
 `deduction_id` int(11) ,
 `amount` varchar(45) ,
 `description` text ,
 `collection_id` int(11) ,
 `date_recorded` datetime ,
 `date_recorded_iso` varchar(20) ,
 `recorded_by_user_id` int(11) ,
 `recorded_by` varchar(50) 
)*/;

/*Table structure for table `detailed_bank_transactions` */

DROP TABLE IF EXISTS `detailed_bank_transactions`;

/*!50001 DROP VIEW IF EXISTS `detailed_bank_transactions` */;
/*!50001 DROP TABLE IF EXISTS `detailed_bank_transactions` */;

/*!50001 CREATE TABLE  `detailed_bank_transactions`(
 `transaction_id` int(11) ,
 `transaction_date` datetime ,
 `transaction_date_formatted` varchar(40) ,
 `date_recorded` datetime ,
 `date_recorded_formatted` varchar(49) ,
 `transaction_type` tinyint(1) ,
 `amount` decimal(10,0) ,
 `recorded_by_user_id` int(11) ,
 `notes` text ,
 `transaction_type_full` varchar(10) ,
 `balance` int(11) ,
 `recorded_by` varchar(50) 
)*/;

/*Table structure for table `detailed_collection_type` */

DROP TABLE IF EXISTS `detailed_collection_type`;

/*!50001 DROP VIEW IF EXISTS `detailed_collection_type` */;
/*!50001 DROP TABLE IF EXISTS `detailed_collection_type` */;

/*!50001 CREATE TABLE  `detailed_collection_type`(
 `type_id` int(11) ,
 `type_name` varchar(45) ,
 `cost` int(11) ,
 `is_different_cost_per_class` tinyint(1) ,
 `is_deleted` tinyint(1) ,
 `is_expense` tinyint(1) ,
 `is_system_type` tinyint(1) ,
 `class_id` int(11) ,
 `class_cost` decimal(10,0) 
)*/;

/*Table structure for table `finance_summary` */

DROP TABLE IF EXISTS `finance_summary`;

/*!50001 DROP VIEW IF EXISTS `finance_summary` */;
/*!50001 DROP TABLE IF EXISTS `finance_summary` */;

/*!50001 CREATE TABLE  `finance_summary`(
 `type_id` int(11) ,
 `type_name` varchar(45) ,
 `academic_year` varchar(15) ,
 `amount` decimal(54,0) ,
 `is_expense` tinyint(1) 
)*/;

/*Table structure for table `otherexpensedetails` */

DROP TABLE IF EXISTS `otherexpensedetails`;

/*!50001 DROP VIEW IF EXISTS `otherexpensedetails` */;
/*!50001 DROP TABLE IF EXISTS `otherexpensedetails` */;

/*!50001 CREATE TABLE  `otherexpensedetails`(
 `transaction_id` int(11) ,
 `collection_id` int(11) ,
 `date_recorded` datetime ,
 `date_recorded_iso` varchar(20) ,
 `type_name` varchar(45) ,
 `amount` decimal(10,0) ,
 `recorded_by_user_id` int(11) ,
 `recorded_by` varchar(50) ,
 `academic_year` varchar(15) ,
 `notes` text ,
 `is_input` tinyint(1) 
)*/;

/*Table structure for table `transaction_details` */

DROP TABLE IF EXISTS `transaction_details`;

/*!50001 DROP VIEW IF EXISTS `transaction_details` */;
/*!50001 DROP TABLE IF EXISTS `transaction_details` */;

/*!50001 CREATE TABLE  `transaction_details`(
 `transaction_id` int(11) ,
 `collection_id` int(11) ,
 `amount` decimal(10,0) ,
 `collection_type_id` int(11) ,
 `is_input` tinyint(1) ,
 `date_recorded` datetime ,
 `date_recorded_iso` varchar(20) ,
 `recorded_by_user_id` int(11) ,
 `recorded_by` varchar(50) 
)*/;

/*View structure for view collection_details */

/*!50001 DROP TABLE IF EXISTS `collection_details` */;
/*!50001 DROP VIEW IF EXISTS `collection_details` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `collection_details` AS select `col`.`collection_id` AS `collection_id`,`col`.`type_id` AS `type_id`,`col`.`amount_due` AS `amount_due`,`col`.`academic_year` AS `academic_year`,`col`.`class_id` AS `class_id`,`col`.`student_id` AS `student_id`,`col`.`staff_id` AS `staff_id`,`col`.`salary_month_index` AS `salary_month_index`,(case `col`.`salary_month_index` when 1 then 'January' when 2 then 'February' when 3 then 'March' when 4 then 'April' when 5 then 'May' when 6 then 'June' when 7 then 'July' when 8 then 'August' when 9 then 'September' when 10 then 'October' when 11 then 'November' when 12 then 'December' end) AS `MONTH`,`staff`.`staff_name` AS `staff_name`,`ctype`.`type_name` AS `type_name`,`ctype`.`is_expense` AS `is_expense`,`st`.`student_number` AS `student_number`,`st`.`student_name` AS `student_name`,`st`.`date_of_birth` AS `date_of_birth`,`st`.`gender` AS `gender`,`cl`.`class_name` AS `class_name`,coalesce(`g`.`guardian_name`,'') AS `guardian_name`,coalesce(`g`.`phone_number`,'') AS `guardian_number`,coalesce(sum(`tr`.`amount`),0) AS `amount_paid`,coalesce(sum(`ded`.`amount`),0) AS `deductions`,if((((`col`.`amount_due` - coalesce(sum(`tr`.`amount`),0)) - coalesce(sum(`ded`.`amount`),0)) > 0),((`col`.`amount_due` - coalesce(sum(`tr`.`amount`),0)) - coalesce(sum(`ded`.`amount`),0)),0) AS `amount_owed` from (((((((`collections` `col` left join `collection_types` `ctype` on((`ctype`.`type_id` = `col`.`type_id`))) left join `classes` `cl` on((`col`.`class_id` = `cl`.`class_id`))) left join `students` `st` on((`col`.`student_id` = `st`.`student_id`))) left join `transactions` `tr` on((`tr`.`collection_id` = `col`.`collection_id`))) left join `deductions` `ded` on((`ded`.`collection_id` = `col`.`collection_id`))) left join `student_guardians` `g` on((`g`.`student_id` = `col`.`student_id`))) left join `staff` on((`col`.`staff_id` = `staff`.`staff_id`))) group by `col`.`collection_id` */;

/*View structure for view collection_summaries */

/*!50001 DROP TABLE IF EXISTS `collection_summaries` */;
/*!50001 DROP VIEW IF EXISTS `collection_summaries` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `collection_summaries` AS select `c`.`type_id` AS `type_id`,`c`.`academic_year` AS `academic_year`,`c`.`type_name` AS `type_name`,sum(`c`.`amount_due`) AS `total_due`,sum(`c`.`amount_paid`) AS `total_paid`,sum(`c`.`deductions`) AS `total_deductions`,sum(`c`.`amount_owed`) AS `total_owed` from `collection_details` `c` group by `c`.`academic_year` order by `c`.`academic_year` desc */;

/*View structure for view collection_type_cost_details */

/*!50001 DROP TABLE IF EXISTS `collection_type_cost_details` */;
/*!50001 DROP VIEW IF EXISTS `collection_type_cost_details` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `collection_type_cost_details` AS (select `ctc`.`type_cost_id` AS `type_cost_id`,`ctc`.`class_id` AS `class_id`,`ctc`.`cost` AS `cost`,`ctc`.`type_id` AS `type_id`,`c`.`class_index` AS `class_index`,`c`.`class_name` AS `class_name`,`c`.`class_section` AS `class_section` from (`collection_type_costs` `ctc` left join `classes` `c` on((`ctc`.`class_id` = `c`.`class_id`)))) */;

/*View structure for view deduction_details */

/*!50001 DROP TABLE IF EXISTS `deduction_details` */;
/*!50001 DROP VIEW IF EXISTS `deduction_details` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `deduction_details` AS select `d`.`deduction_id` AS `deduction_id`,`d`.`amount` AS `amount`,`d`.`description` AS `description`,`d`.`collection_id` AS `collection_id`,`d`.`date_recorded` AS `date_recorded`,date_format(`d`.`date_recorded`,'%Y-%m-%dT%TZ') AS `date_recorded_iso`,`d`.`recorded_by_user_id` AS `recorded_by_user_id`,`u`.`full_name` AS `recorded_by` from (`deductions` `d` left join `users` `u` on((`d`.`recorded_by_user_id` = `u`.`user_id`))) */;

/*View structure for view detailed_bank_transactions */

/*!50001 DROP TABLE IF EXISTS `detailed_bank_transactions` */;
/*!50001 DROP VIEW IF EXISTS `detailed_bank_transactions` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detailed_bank_transactions` AS select `bt`.`transaction_id` AS `transaction_id`,`bt`.`transaction_date` AS `transaction_date`,date_format(`bt`.`transaction_date`,'%d %b %Y') AS `transaction_date_formatted`,`bt`.`date_recorded` AS `date_recorded`,date_format(`bt`.`date_recorded`,'%d %b %Y %l:%i %p') AS `date_recorded_formatted`,`bt`.`transaction_type` AS `transaction_type`,`bt`.`amount` AS `amount`,`bt`.`recorded_by_user_id` AS `recorded_by_user_id`,`bt`.`notes` AS `notes`,if((`bt`.`transaction_type` = 1),'Deposit','Withdrawal') AS `transaction_type_full`,`get_bank_account_balance`(`bt`.`transaction_id`) AS `balance`,`u`.`full_name` AS `recorded_by` from (`bank_transactions` `bt` left join `users` `u` on((`bt`.`recorded_by_user_id` = `u`.`user_id`))) */;

/*View structure for view detailed_collection_type */

/*!50001 DROP TABLE IF EXISTS `detailed_collection_type` */;
/*!50001 DROP VIEW IF EXISTS `detailed_collection_type` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detailed_collection_type` AS select `ct`.`type_id` AS `type_id`,`ct`.`type_name` AS `type_name`,`ct`.`cost` AS `cost`,`ct`.`is_different_cost_per_class` AS `is_different_cost_per_class`,`ct`.`is_deleted` AS `is_deleted`,`ct`.`is_expense` AS `is_expense`,`ct`.`is_system_type` AS `is_system_type`,`ctc`.`class_id` AS `class_id`,`ctc`.`cost` AS `class_cost` from (`collection_types` `ct` left join `collection_type_costs` `ctc` on((`ct`.`type_id` = `ctc`.`type_id`))) */;

/*View structure for view finance_summary */

/*!50001 DROP TABLE IF EXISTS `finance_summary` */;
/*!50001 DROP VIEW IF EXISTS `finance_summary` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `finance_summary` AS select `collection_details`.`type_id` AS `type_id`,`collection_details`.`type_name` AS `type_name`,`collection_details`.`academic_year` AS `academic_year`,sum(`collection_details`.`amount_paid`) AS `amount`,`collection_details`.`is_expense` AS `is_expense` from `collection_details` group by `collection_details`.`type_id`,`collection_details`.`academic_year` */;

/*View structure for view otherexpensedetails */

/*!50001 DROP TABLE IF EXISTS `otherexpensedetails` */;
/*!50001 DROP VIEW IF EXISTS `otherexpensedetails` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `otherexpensedetails` AS select `tr`.`transaction_id` AS `transaction_id`,`tr`.`collection_id` AS `collection_id`,`tr`.`date_recorded` AS `date_recorded`,date_format(`tr`.`date_recorded`,'%Y-%m-%dT%TZ') AS `date_recorded_iso`,`ct`.`type_name` AS `type_name`,`tr`.`amount` AS `amount`,`tr`.`recorded_by_user_id` AS `recorded_by_user_id`,`u`.`full_name` AS `recorded_by`,`cols`.`academic_year` AS `academic_year`,`cols`.`notes` AS `notes`,`tr`.`is_input` AS `is_input` from (((`transactions` `tr` left join `collection_types` `ct` on((`tr`.`collection_type_id` = `ct`.`type_id`))) left join `users` `u` on((`u`.`user_id` = `tr`.`recorded_by_user_id`))) left join `collections` `cols` on((`cols`.`collection_id` = `tr`.`collection_id`))) where (`ct`.`is_not_student_related` = 1) */;

/*View structure for view transaction_details */

/*!50001 DROP TABLE IF EXISTS `transaction_details` */;
/*!50001 DROP VIEW IF EXISTS `transaction_details` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `transaction_details` AS select `t`.`transaction_id` AS `transaction_id`,`t`.`collection_id` AS `collection_id`,`t`.`amount` AS `amount`,`t`.`collection_type_id` AS `collection_type_id`,`t`.`is_input` AS `is_input`,`t`.`date_recorded` AS `date_recorded`,date_format(`t`.`date_recorded`,'%Y-%m-%dT%TZ') AS `date_recorded_iso`,`t`.`recorded_by_user_id` AS `recorded_by_user_id`,`u`.`full_name` AS `recorded_by` from (`transactions` `t` left join `users` `u` on((`t`.`recorded_by_user_id` = `u`.`user_id`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
