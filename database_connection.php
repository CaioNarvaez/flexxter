<?php 

/** QUERIES FOR THE DATABASE - MYSQL
 * CREATE DATABASE flexxter;
 * USE flexxter;
 * 
 * CREATE TABLE `tblemployees` (
 * `EmployeeID` int(11) NOT NULL AUTO_INCREMENT,
 * `firstname` varchar(50) DEFAULT NULL,
 * `surname` varchar(50) DEFAULT NULL,
 * `password` varchar(50) DEFAULT NULL,
 * PRIMARY KEY (`EmployeeID`)
 * ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
 *
 * 
 * CREATE TABLE `tblmachines` (
 * `MachineID` int(11) NOT NULL AUTO_INCREMENT,
 * `title` varchar(50) DEFAULT NULL,
 * PRIMARY KEY (`MachineID`)
 * ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
 *
 * 
 * CREATE TABLE `tblcheckouts` (
 * `CheckOutID` int(11) NOT NULL AUTO_INCREMENT,
 * `EmployeeID` int(11) NOT NULL,
 * `MachineID` int(11) NOT NULL,
 * `date_checkout` datetime NOT NULL,
 * `date_return` datetime DEFAULT NULL,
 * PRIMARY KEY (`CheckOutID`),
 * UNIQUE KEY `CheckOutID_UNIQUE` (`CheckOutID`),
 * KEY `EmployeeID_idx` (`EmployeeID`),
 * KEY `MachineID_idx` (`MachineID`)
 * ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

 * 
 * 
 */


// Define database connection parameters
$hn      = '';
$un      = '';
$pwd     = '';
$db      = '';
$cs      = 'utf8';

// Set up the PDO parameters
$dsn 	= "mysql:host=" . $hn . ";port=3306;dbname=" . $db . ";charset=" . $cs;
$opt 	= array(
                     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                     PDO::ATTR_EMULATE_PREPARES   => false,
                    );
// Create a PDO instance (connect to the database)
$pdo 	= new PDO($dsn, $un, $pwd, $opt);

?>