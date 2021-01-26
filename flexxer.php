
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


class Employee {

    /**
     * Employee's unique id
     * @var int $id
     */
    public $id;

    /**
     * Employee's firstname
     * @var string $firstname
     */
    public $firstname;


    /**
     * Employee's surname
     * @var string $surname
     */
    public $surname;

     /**
      * Hashed als salted password
      * @var string $password
      */
      public $password;


    // Methods

    //set and get id
    function set_id($id) {
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }

    //set and get fisrtname
    function set_firstname($firstname) {
        $this->firstname = $firstname;
    }
    function get_firstname() {
        return $this->firstname;
    }
    
    //set and get surname
    function set_surname($surname) {
        $this->surname = $surname;
    }
    function get_surname() {
        return $this->surname;
    }

    //set and get password
    function set_password($password) {
        $this->password = $password;
    }
    function get_password() {
        return $this->password;
    }

}

class Machine {
    /**
     * Machine's unique id
     * @var int $id
     */
    public $id;

    /**
     * Machine's title
     * @var string $title
     */
    public $title;


    // Methods

    //set and get id
    function set_id($id) {
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }
    
    //set and get title
    function set_title($title) {
        $this->title = $title;
    }
    function get_title() {
        return $this->title;
    }




    /**
     * assigns the machine to the given employee (checks the machine out)
     * @param Employee $employee the employee who wants to check out the machine
     */
    public function checkout(Employee $employee) : void {


        //get employee's id and machine's id
        $id_employee = $employee->get_id();
        $id_machine = $this->get_id();

        //Check machine's Availability
        if(empty(machineAvailability($id_machine))) {
            //query for insert into database.
            $sql = "INSERT INTO TblCheckOuts(EmployeeID, MachineID, date_checkout, date_return) VALUES (:id_employee, :id_machine, NOW(), NULL);";
            $stmt 	= $pdo->prepare($sql);
            $stmt->bindParam(':id_employee', $id_employee, PDO::PARAM_INT); 
            $stmt->bindParam(':id_machine', $id_machine, PDO::PARAM_INT);                                          
            $stmt->execute();

            //In case to do something with the last insert or check if the stmt worked well.
            //$last_id = $pdo->lastInsertId();
        }
        else {
            //This machine is not available to borrow.
        }


    }

    /**
     * Indicates that no employee has taken the machine with them
     * and that the employee put the machine back to the wharehouse
     */
    public function back_to_warehouse() : void {

        //get machine's id
        $id_machine = $this->get_id();

        //query for update the table checkOut with the new return date.
        $sql = "UPDATE TblCheckOuts SET date_return = NOW() WHERE MachineID = :id_machine AND date_return IS NULL;";
        $stmt 	= $pdo->prepare($sql);
        $stmt->bindParam(':id_machine', $id_machine, PDO::PARAM_INT);                                          
        $stmt->execute();
    }

    //Show all resources checked out by a specific employee
    public function showResources($name, $password) {

        //query to select all results from the employee that shows the machines that are currently borrowed.
        $sql = "SELECT 
        TCO.CheckOutID, TM.MachineID, TM.title, TE.firstname
    FROM
        ((tblcheckouts AS TCO
        INNER JOIN tblemployees AS TE ON TCO.EmployeeID = TE.EmployeeID)
        INNER JOIN tblmachines AS TM ON TCO.MachineID = TM.MachineID)
    WHERE
        TCO.date_return IS NULL
        AND TE.firstname = :name
        AND TE.password  = :password;
        ";

        $stmt 	= $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);     
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);                                          
        $stmt->execute();

        //fill array with the results
        while($row = $stmt->fetch()){
            $checkOuts[] = $row;
         }
    
        //each result on checkOuts become a object of Machine.
        foreach ($checkOut as $checkOuts) {
            $machine = new Machine();
            $machine->set_id($checkOut['MachineID']);
            $machine->set_title($checkOut['title']);
            
            //Array of objects machine.
            $arrayMachine[] = $machine;
        }

        //Return the array of object
        return $arrayMachine;
    }


    //Method to check the machine's availability
    public function machineAvailability($id_machine) {

        $sql = "SELECT *
        FROM
            tblcheckouts AS TCO
                INNER JOIN
            tblmachines AS TM ON TCO.MachineID = TM.MachineID
        WHERE
            TCO.date_return IS NULL;";
        $stmt 	= $pdo->prepare($sql);
        $stmt->bindParam(':id_machine', $id_machine, PDO::PARAM_INT);                                     
        $stmt->execute();

        $result = $stmt->fetch();
        return $result;

    }

}
    $machine = new Machine();
    echo $machine->showResources("Sandy", "d0g");
?>