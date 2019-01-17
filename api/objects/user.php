<?php
class User{

    // DATABASE CONNECTION AND TABLE NAME
    private $conn;
    private $table_name = "user";

    // OBJECT PROPERTIES
    public $user_email;
    public $user_name;
    public $user_contact;
    public $user_password;
    public $user_url;

    // CONSTRUCTOR WITH $db AS DATABASE CONNECTION
    public function __construct($db){
        $this->conn = $db;
    }

    // READ ALL DATA-------------------------------------------------------------
    public function readAll(){

        // SELECT ALL QUERY
        $query = "SELECT * FROM " . $this->table_name ;

        // PREPARE QUERY STATEMENT
        $stmt = $this->conn->prepare($query);

        // EXECUTE QUERY
        $stmt->execute();

        return $stmt;
    }

    // INSERT ONE DATA-------------------------------------------------------------
    public function insert(){

        // INSERT RECORD QUERY
        $query =      " INSERT INTO "
                    .   $this->table_name
                    . " (user_email, user_name, user_contact, user_password, user_url) "
                    . " VALUES "
                    . " (:user_email, :user_name, :user_contact, :user_password, :user_url) ";

        // PREPARE QUERY STATEMENT
        $stmt = $this->conn->prepare($query);

        // SANITIZE
        $this->user_email=htmlspecialchars(strip_tags($this->user_email));
        $this->user_name=htmlspecialchars(strip_tags($this->user_name));
        $this->user_contact=htmlspecialchars(strip_tags($this->user_contact));
        $this->user_password=htmlspecialchars(strip_tags($this->user_password));
        $this->user_url=htmlspecialchars(strip_tags($this->user_url));

        // BIND VALUES
        $stmt->bindParam(":user_email", $this->user_email);
        $stmt->bindParam(":user_name", $this->user_name);
        $stmt->bindParam(":user_contact", $this->user_contact);
        $stmt->bindParam(":user_password", $this->user_password);
        $stmt->bindParam(":user_url", $this->user_url);

        // EXECUTE QUERY
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // READ ONE USER-------------------------------------------------------------
    public function readOne(){

        // READ ONE RECORD QUERY
        $query =      " SELECT * "
                    . " FROM "
                    .   $this->table_name
                    . " WHERE user_email = :user_email";

        // PREPARE QUERY STATEMENT
        $stmt = $this->conn->prepare($query);

        // BIND VALUES
        $stmt->bindParam(':user_email', $this->user_email);

        // EXECUTE THE QUERY
        $stmt->execute();

        // GET DATA ROW FROM RESPONSE
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // SET VALUEST TO THE OBJECT PROPERTIES
        $this->user_email = $row['user_email'];
        $this->user_name = $row['user_name'];
        $this->user_contact = $row['user_contact'];
        $this->user_password = $row['user_password'];
        $this->user_url = $row['user_url'];

        $num = $stmt->rowCount();
        if($num>0){
            return true;
        }else{
            return false;
        }
    }

    // UPDATE USER-------------------------------------------------------------
    public function update(){

        // UPDATE RECORDS QUERY
        $query =      " UPDATE "
                    .   $this->table_name
                    . " SET "
                    . " user_name = :user_name, "
                    . " user_contact = :user_contact, "
                    . " user_password = :user_password,"
                    . " user_url = :user_url, "
                    . " user_modified = CURRENT_TIMESTAMP() "
                    . " WHERE "
                    . " user_email = :user_email " ;

        // PREPARE QUERY STATEMENT
        $stmt = $this->conn->prepare($query);

        // SANITIZE
        $this->user_name=htmlspecialchars(strip_tags($this->user_name));
        $this->user_contact=htmlspecialchars(strip_tags($this->user_contact));
        $this->user_password=htmlspecialchars(strip_tags($this->user_password));
        $this->user_url=htmlspecialchars(strip_tags($this->user_url));
        $this->user_email=htmlspecialchars(strip_tags($this->user_email));

        // BIND VALUES
        $stmt->bindParam(':user_name', $this->user_name);
        $stmt->bindParam(':user_contact', $this->user_contact);
        $stmt->bindParam(':user_password', $this->user_password);
        $stmt->bindParam(':user_url', $this->user_url);
        $stmt->bindParam(':user_email', $this->user_email);

        // EXECUTE THE QUERY
        if($stmt->execute()){
            return true;
        }

        return false;

    }

    // DELETE USER-------------------------------------------------------------
    public function delete(){

        // DELETE RECORDS QUERY
        $query = " DELETE FROM " . $this->table_name . " WHERE user_email = ?";

        // PREPARE QUERY STATEMENT
        $stmt = $this->conn->prepare($query);

        // SANITIZE
        $this->user_email = htmlspecialchars(strip_tags($this->user_email));

        // BIND VALUES
        $stmt->bindParam(1, $this->user_email);

        // EXECUTE THE QUERY
        if($stmt->execute()){
            return true;
        }

        return false;

    }

    // SEARCH USER-------------------------------------------------------------
    public function search($keywords){

        // SEARCH RECORDS QUERY
        $query =      " SELECT * "
                    . " FROM "
                    .   $this->table_name
                    . " WHERE "
                    . " user_name LIKE ? OR user_email LIKE ? OR user_contact LIKE ? "
                    . " ORDER BY "
                    . " user_created ";

        // PREPARE QUERY STATEMENT
        $stmt = $this->conn->prepare($query);

        // SANITIZE
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // BIND VALUES
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // EXECUTE QUERY
        $stmt->execute();

        // RETURN DATA FROM DATABASE
        return $stmt;
    }

    // PAGINATION USER-------------------------------------------------------------
    public function readPaging($from_record_num, $records_per_page){

        // SELECT RECORDS QUERY
        $query =      " SELECT * "
                    . " FROM "
                    .   $this->table_name
                    . " ORDER BY user_created DESC "
                    . " LIMIT ? , ? ";

        // PREPARE QUERY STATEMENT
        $stmt = $this->conn->prepare($query);

        // BIND VALUES
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // EXECUTE QUERY
        $stmt->execute();

        // RETURN DATA FROM DATABASE
        return $stmt;
    }

    // GET THE COUNT FOR PAGEING-------------------------------------------------------------
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    // CHECK ID AVAILABLE-------------------------------------------------------------------
    public function check(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_email=? ";

        // PREPARE QUERY STATEMENT
        $stmt = $this->conn->prepare($query);

        // SANITIZE
        $this->user_email = htmlspecialchars(strip_tags($this->user_email));

        // BIND VALUES
        $stmt->bindParam(1, $this->user_email);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            return true;
        }else{
            return false;
        }
    }
}
?>