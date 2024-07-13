<?php
class User
{
    private $conn;
    public $user_id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $is_active;
    public $access_level;
    public function __construct($dbase)
    {
        $this->conn = $dbase;
    }
    public function readUser()
    {
        $query = "SELECT * FROM users";
        $statement = $this->conn->prepare($query);
        $statement->execute();

        return $statement;
    }

    public function deleteUser()
    {
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            return true; // Indicate success
        } else {
            // Handle errors
            $error = $statement->errorInfo();
            echo "Error deleting user: " . $error[2];
            return false; // Indicate failure
        }
    }

    public function doesEmailExist($email)
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $email);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row['count'] > 0;
    }

    public function createUser()
    {
        // Check if email already exists
        if ($this->doesEmailExist($this->email)) {            
            return 'email_exists'; // email already exists
        }

        $query = "INSERT INTO users 
                  SET first_name = :first_name,
                      last_name = :last_name,
                      email = :email,
                      password = :password,
                      is_active = :is_active,
                      access_level = ";
        $statement = $this->conn->prepare($query);
        $hash_password = password_hash($this->password, PASSWORD_BCRYPT);

        $statement->bindParam(':first_name', $this->first_name, PDO::PARAM_STR);
        $statement->bindParam(':last_name', $this->last_name, PDO::PARAM_STR);
        $statement->bindParam(':email', $this->email, PDO::PARAM_STR);
        $statement->bindParam(':password', $hash_password, PDO::PARAM_STR);
        $statement->bindParam(':is_active', $this->is_active, PDO::PARAM_BOOL);

        if ($statement->execute()) {
            return true;
        } else {
            $error = $statement->errorInfo();
            echo "Error adding user: " . $error[2];
            return false;
        }
    }
    public function editUser()
    {
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $this->first_name = $row['first_name'];
                $this->last_name = $row['last_name'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->is_active = $row['is_active'];
            } else {
                // Handle the case where the user ID does not exist
                echo "User not found.";
                return false;
            }
        } else {
            // Handle errors
            $error = $statement->errorInfo();
            echo "Error fetching user: " . $error[2];
            return false;
        }

        return true;
    }

    public function updateUser()
    {
        $query = "UPDATE users
                  SET first_name = :first_name,
                      last_name = :last_name,
                      email = :email,
                      password = :password,
                      is_active = :is_active
                  WHERE user_id = :user_id";
        $statement = $this->conn->prepare($query);

        $hash_password = password_hash($this->password, PASSWORD_BCRYPT);

        $statement->bindParam(':first_name', $this->first_name, PDO::PARAM_STR);
        $statement->bindParam(':last_name', $this->last_name, PDO::PARAM_STR);
        $statement->bindParam(':email', $this->email, PDO::PARAM_STR);
        $statement->bindParam(':password', $hash_password, PDO::PARAM_STR);
        $statement->bindParam(':is_active', $this->is_active, PDO::PARAM_BOOL);
        $statement->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            return true;
        } else {
            $error = $statement->errorInfo();
            echo "Error updating user: " . $error[2];
            return false;
        }
    }
    
    public function searchUser($searchQuery)
    {
        $query = "SELECT * FROM users WHERE last_name LIKE :searchQuery";
        $result = $this->conn->prepare($query);
        $searchTerm = '%' . $searchQuery . '%';
        $result->bindParam(':searchQuery', $searchTerm, PDO::PARAM_STR);
        $result->execute();
        $results = $result->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function getUserLoggedInDetails($userId) {
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
    }
}
?>