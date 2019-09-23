<?php

/**
 * This class contains database specific functionalities
 *
 * Establishes connection to the database on each
 * new request and helps other models retrive/insert
 * data to the database
 *
 * @package    Authentication_API
 * @subpackage Authentication_API/config
 */

class Database {

    /**
     * Your host name goes here
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $_host
     */
    private $_host = 'localhost';

    /**
     * Database name goes here
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $_db_name
     */
    private $_db_name = 'rest_api_db';

    /**
     * Your username for accessing db
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $_username
     */
    private $_username = 'root';

    /**
     * Password for accessing db
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $_password
     */
    private $_password = '';

    /**
     * Holds the PDO object and provides PDO functionalities
     *
     * @since    1.0.0
     * @access   private
     * @var      PDO Object    $_conn
     */
    private $_conn;


    /**
     * Instantiate the $_conn variable with a PDO object
     * Connect to the database and return the PDO instance if there are no errors
     * 
     * @access   public
     * @since    1.0.0
     * @return   PDO instance returns initialized PDO instance
     */
    public function connect() {
        
        $this->_conn = null;
        
        try {
            $this->_conn = new PDO(
                'mysql:host=' . $this->_host . ';dbname=' . $this->_db_name,
                $this->_username, 
                $this->_password
            );

            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            echo 'Connection Error' . $e -> getMessage();
        }

        return $this->_conn;
    }
    
}