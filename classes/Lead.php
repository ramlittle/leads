<?php 
class Lead{
    private $conn;
    public $lead_id;
    public $phone_number;
    public $contact_name;
    public $email;
    public $disposition;
    public $user_id;

    public function __construct($dbase)
    {
        $this->conn = $dbase;
    }

    public function readLeads($userId) {
        $query = "SELECT *, CONCAT(first_name, ' ', last_name, '(', users.user_id, ')') AS added_by 
                  FROM leads
                  INNER JOIN users ON leads.user_id = users.user_id
                  WHERE leads.user_id = :userId
                  ORDER BY leads.lead_id DESC
                  ";
                  
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->execute();

        return $statement;
    }
    public function deleteLead()
    {
        $query = "DELETE FROM leads WHERE lead_id = :lead_id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':lead_id', $this->lead_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            return true; // Indicate success
        } else {
            // Handle errors
            $error = $statement->errorInfo();
            echo "Error deleting lead: " . $error[2];
            return false; // Indicate failure
        }
    }

    public function addLead()
    {
        $query = "INSERT INTO leads 
                  SET phone_number = :phone_number,
                      contact_name = :contact_name,
                      email = :email,
                      disposition = :disposition,
                      user_id = :user_id";

        $statement = $this->conn->prepare($query);
        
        $statement->bindParam(':phone_number', $this->phone_number, PDO::PARAM_INT);
        $statement->bindParam(':contact_name', $this->contact_name, PDO::PARAM_STR);
        $statement->bindParam(':email', $this->email, PDO::PARAM_STR);
        $statement->bindParam(':disposition', $this->disposition, PDO::PARAM_STR);
        $statement->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);        

        if ($statement->execute()) {
            return true;
        } else {
            $error = $statement->errorInfo();
            echo "Error adding lead: " . $error[2];
            return false;
        }
    }

    public function editLead()
    {
        $query = "SELECT * FROM leads WHERE lead_id = :lead_id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':lead_id', $this->lead_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $this->phone_number = $row['phone_number'];
                $this->contact_name = $row['contact_name'];
                $this->email = $row['email'];
                $this->disposition = $row['disposition'];
                $this->user_id = $row['user_id'];
            } else {
                // Handle the case where the user ID does not exist
                echo "lead not found.";
                return false;
            }
        } else {
            // Handle errors
            $error = $statement->errorInfo();
            echo "Error fetching activity: " . $error[2];
            return false;
        }

        return true;
    }

    public function updateLead()
    {
        $query = "UPDATE leads
                  SET phone_number = :phone_number,
                      contact_name = :contact_name,
                      email = :email,
                      disposition = :disposition,
                      user_id = :user_id
                  WHERE lead_id = :lead_id
                  ";
        $statement = $this->conn->prepare($query);

        $statement->bindParam(':phone_number', $this->phone_number, PDO::PARAM_INT);
        $statement->bindParam(':contact_name', $this->contact_name, PDO::PARAM_STR);
        $statement->bindParam(':email', $this->email, PDO::PARAM_STR);
        $statement->bindParam(':disposition', $this->disposition, PDO::PARAM_STR);
        $statement->bindParam(':email', $this->email, PDO::PARAM_INT);
        $statement->bindParam(':lead_id', $this->lead_id, PDO::PARAM_INT);
        if ($statement->execute()) {
            return true;
        } else {
            $error = $statement->errorInfo();
            echo "Error updating activity: " . $error[2];
            return false;
        }
    }

    public function searchLead($searchQuery)
    {
        // Use a prepared statement to prevent SQL injection
        $query = "
            SELECT *, CONCAT(first_name, ' ', last_name, '(', users.user_id, ')') AS added_by 
                  FROM leads
                  INNER JOIN users ON leads.user_id = users.user_id
                  WHERE leads.phone_number LIKE :searchQuery
                  ORDER BY leads.lead_id DESC
        ";
        
        
        // Prepare the statement
        $stmt = $this->conn->prepare($query);
        
        // Bind the parameter
        $searchTerm = '%' . $searchQuery . '%';
        $stmt->bindParam(':searchQuery', $searchTerm, PDO::PARAM_STR);
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }
}
?>