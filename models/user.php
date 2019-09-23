<?php

/**
 * This class contains User specific functionalities
 *
 * Contains all the properties for the users and provides
 * functions for api CRUD operations to interact with the users' 
 * table
 *
 * @package    Authentication_API
 * @subpackage Authentication_API/models
 */

class User {

    /**
     * Database specific variables
     */

    /**
     * Varible holds the PDO object
     *
     * @since    1.0.0
     * @access   private
     * @var      PDO Object    $_conn
     */
    private $_conn;
    
    /**
     * Table name which stores the users data
     *
     * @since    1.0.0
     * @access   private
     * @var      String    $_table
     */
    private $_table = 'users';
    

    /**
     * user properties
     */

    /**
     * User variables 
     *
     * @since    1.0.0
     * @access   public
     * @var      String    $id
     * @var      String    $email
     * @var      String    $password
     */
    public $id;
    public $email;
    public $password;

    /**
     * Initializes the $_conn variable with passed in db object
     * 
     * @access   public
     * @since    1.0.0
     * 
     * @param  Database Object    Database class object
     */
    public function __construct($db) {
        $this->_conn = $db;
    }


    /**
     * Used for reading all users from db
     * 
     * Directly interacts with the database and
     * returns the requested data
     * 
     * @access   public
     * @since    1.0.0
     * @return   PDOStatement object 
     */
    public function get_all() {

        // create query
        $query = 'SELECT
                    id,
                    email,
                    password
                FROM ' . $this->_table . '
                    ORDER BY id ASC';

        // prepare statement
        $stmt = $this->_conn->prepare($query);

        // execute query
        $stmt->execute();
        
        return $stmt;
    }


    /**
     * Used for reading a single user from db
     * 
     * Directly interacts with the database and
     * returns the requested data
     * 
     * @access   public
     * @since    1.0.0
     * @return   boolean true if user found, else false
     */
    public function get_single() {

        // create query
        $query = 'SELECT
                    id,
                    email
                FROM ' . $this->_table . ' 
                WHERE 
                    id = :id
                LIMIT 0,1';

        // prepare statement
        $stmt = $this->_conn->prepare($query);

        // bind ID
        $stmt->bindParam(':id', $this->id);

        // execute query
        $stmt->execute();

        $row_count = $stmt->rowCount();

        // no user for given id
        if ($row_count <= 0) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //set properties
        $this->email = $row[ 'email' ];

        return true;
    }

    /**
     * Used for registering new user
     * 
     * Directly interacts with the database and
     * responds with appropriate output
     * 
     * @access   public
     * @since    1.0.0
     * @return   Mixed true if successfully registered, else false/403
     */    
    public function register() {

        // check if user already exists
        $query = 'SELECT id from ' . 
                $this->_table . '
                WHERE
                    email = :email';

        // prepare statement
        $stmt = $this->_conn->prepare($query);

        $this->email = htmlSpecialChars(strip_tags($this->email));

        // bind data
        $stmt->bindParam(':email', $this->email);
        
        if ($stmt->execute()) {
            $rows = $stmt->rowCount();

            if ($rows > 0) {
                // user already is registered
                return '403';
            }
        }
        
        // It's a new user so insert it        
        $query = 'INSERT INTO ' .
            $this->_table . ' 
            SET
                email = :email,
                password  = :password';

        //prepare statement
        $stmt = $this->_conn->prepare($query);

        // sanitize data
        $this->email = htmlSpecialChars(strip_tags($this->email));
        $this->password = htmlSpecialChars(strip_tags($this->password));

        // bind data
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        // execute query
        if ($stmt->execute()) {
            return true;
        } else {
            //print error if something goes wrong
            print_f('Error: %s.\n', $stmt->error);

            return false;
        }

    }

    /**
     * Used for updating password of a user
     * 
     * Directly interacts with the database and
     * responds with appropriate output
     * 
     * @access   public
     * @since    1.0.0
     * @return   boolean true if successfully updated, else false
     */
    public function update() {

        // create query
        $query = 'UPDATE ' .
            $this->_table . ' 
            SET
                id = :id,
                password = :password,
                email = :email
            WHERE
                email = :email AND id = :id';

        //prepare statement
        $stmt = $this->_conn->prepare($query);

        // sanitize data
        $this->email = htmlSpecialChars(strip_tags($this->email));
        $this->password = htmlSpecialChars(strip_tags($this->password));
        $this->id = htmlSpecialChars(strip_tags($this->id));

        // bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        // execute query
        if ($stmt->execute()) {
            return true;
        } else {
            //print error if something goes wrong
            print_f('Error: %s.\n', $stmt->error);

            return false;
        }
    }

    /**
     * Used for removing a user from db
     * 
     * Directly interacts with the database and
     * responds with appropriate output
     * 
     * @access   public
     * @since    1.0.0
     * @return   boolean true if successfully deleted, else false
     */
    public function delete() {

        // create query
        $query = 'DELETE FROM ' . $this->_table . ' 
                WHERE
                 id = :id AND email = :email';

        // prepare statement
        $stmt = $this->_conn->prepare($query);

        // sanitize id
        $this->id = htmlSpecialChars(strip_tags($this->id));
        $this->email = htmlSpecialChars(strip_tags($this->email));

        // bind id
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':email', $this->email);

        // execute query
        if ($stmt->execute()) {
            return true;
        } else {

            //print error if something goes wrong
            print_f('Error: %s.\n', $stmt->error);

            return false;
        }

    }

    /**
     * Used for authenticating a user
     * 
     * Directly interacts with the database and
     * responds with appropriate output
     * 
     * @access   public
     * @since    1.0.0
     * @return   Mixed true if successfully deleted, else false/401
     */
    public function authenticate() {

        // create query
        $query = 'SELECT
                    id, email 
                FROM ' .
                    $this->_table . ' 
                WHERE
                    email = :email 
                AND 
                    password = :password';

        //prepare statement
        $stmt = $this->_conn->prepare($query);

        // sanitize data
        $this->email = htmlSpecialChars(strip_tags($this->email));
        $this->password = htmlSpecialChars(strip_tags($this->password));

        // bind data
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        // execute query
        if ($stmt->execute()) {
            
            $rows = $stmt->rowCount();

            if ($rows === 1) {
                // set id from the fetched data
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row['id'];
                return true;
            } else {
                // invalid username of password
                return '404';
            }

        } else {
            //print error if something goes wrong
            print_f('Error: %s.\n', $stmt->error);

            return false;
        }

    }

}