<?php 

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

    public function set_id($id) {
        $this->id = $id;
    }
    public function get_id() {
        return $this->id;
    }
    
    public function set_title($title) {
        $this->title = $title;
    }
    public function get_title() {
        return $this->title;
    }

    /**
     * assigns the machine to the given employee (checks the machine out)
     * @param Employee $employee the employee who wants to check out the machine
     */
    public function checkout(Employee $employee) : void {
        $id_employee = $employee->get_id();
        $id_machine = $this->get_id();

        //Check machine's Availability
        if(empty($this->machineAvailability($id_machine))) {
            $sql = "INSERT INTO TblCheckOuts(EmployeeID, MachineID, date_checkout, date_return) VALUES (:id_employee, :id_machine, NOW(), NULL);";
            $stmt 	= $pdo->prepare($sql);
            $stmt->bindParam(':id_employee', $id_employee, PDO::PARAM_INT); 
            $stmt->bindParam(':id_machine', $id_machine, PDO::PARAM_INT);                                          
            $stmt->execute();
            //In case to do something with the last insert or check if the stmt worked well.
            //$last_id = $pdo->lastInsertId();
        }
    }

    /**
     * Indicates that no employee has taken the machine with them
     * and that the employee put the machine back to the wharehouse
     */
    public function back_to_warehouse() : void {

        $id_machine = $this->get_id();

        $sql = "UPDATE TblCheckOuts SET date_return = NOW() WHERE MachineID = :id_machine AND date_return IS NULL;";
        $stmt 	= $pdo->prepare($sql);
        $stmt->bindParam(':id_machine', $id_machine, PDO::PARAM_INT);                                          
        $stmt->execute();
    }

    //Show all resources checked out by a specific employee
    public function showResources($name, $password) {

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

        $arrayMachine = [];
        $checkOuts = [];

        //fill array with the results
        while($row = $stmt->fetch()){
            $checkOuts[] = $row;
         }
    
        //each result on checkOuts become a object of Machine.
        foreach ($checkOut as $checkOuts) {
            $machine = new Machine();
            $machine->set_id($checkOut['MachineID']);
            $machine->set_title($checkOut['title']);
            
            $arrayMachine[] = $machine;
        }
        
        return $arrayMachine;
    }

    public function machineAvailability($id_machine) : array{

        $sql = "SELECT *
        FROM
            tblcheckouts AS TCO
                INNER JOIN
            tblmachines AS TM ON TCO.MachineID = TM.MachineID
        WHERE
            TCO.date_return IS NULL 
            AND TM.MachineID = :id_machine;";
        $stmt 	= $pdo->prepare($sql);
        $stmt->bindParam(':id_machine', $id_machine, PDO::PARAM_INT);                                     
        $stmt->execute();

        $result = $stmt->fetch();
        return $result;

    }

}

?>
