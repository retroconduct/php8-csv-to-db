<?php

class ProcessFile
{
    private $connection;
    private $database = "db";

    public function __construct(
        private string $user,
        private string $password,
        private string $host,
        private string $file,
    ) {
    	$this->connectDB();
    }

    // initialize the file processor
    private function connectDB(): void {
    	if (!$this->host) {
    		throw new Exception("Host required for database connection");
    	}

    	if (!$this->user) {
    		throw new Exception("User required for database connection");
    	}

    	if (!$this->password) {
    		throw new Exception("Password required for database connection");
    	}

    	try {
    		// setting up the mysql instance at startup
	        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
	    } catch (Exception $e) {
	    	throw new Exception("Database connection error");
	    }

        if ($this->connection->connect_errno) {
          throw new Exception("Database connection error");
        }    
    }

    // create table for users
    public function createTable(): void {
        $sql = "CREATE TABLE users( ".
            "name VARCHAR(255) NOT NULL , ".
            "surname VARCHAR(255) NOT NULL, ".
            "email VARCHAR(255) NOT NULL, ".
            "PRIMARY KEY ( email )); ";

        $con = $this->getConnecton();

        if (!$con->query($sql)) {
        	throw new Exception("Cannot create table");
        }
    }

    // delete table users
    public function deleteTable(): void {
        $sql = "DROP TABLE users";

        $con = $this->getConnecton();

        if (!$con->query($sql)) {
        	throw new Exception("Cannot delete table");
        }
    }

    // run the process use $dry argument as true for a dry run
    // and set it to false to run the application as regular
    public function run(bool $dry): void {
    	$csv = "";

    	if (!$this->file) {
    		throw new Exception("File required for run");
    	}

    	try {
    		if (file_exists($this->file)) {
	    		$readFile = file($this->file);

	    		if (is_array($readFile)) {
	    			$csv = array_map('str_getcsv', $readFile);
	    		}	   
    		} else {
    			throw new Exception("File does not exist");	
    		}
	    } catch (Exception $e) {
	    	throw new Exception("Error reading file");
	    }

    	// assuming that all the files will have a header row
    	if (is_array($csv) && count($csv) > 1) {
    		$headers = array_shift($csv);

    		foreach($csv as $record) {
    			$name = "";
    			$surname = "";
    			$email = "";
    			$insert = true;

    			if (isset($record[0]) && !empty($record[0])) {
    				$cleanStr = preg_replace('/[^A-Za-z0-9\' ]/', '', $record[0]);
    				$name = ucwords(trim(strtolower($cleanStr)));
    			}

    			if (isset($record[1]) && !empty($record[1])) {
    				$cleanStr = preg_replace('/[^A-Za-z0-9\' ]/', '', $record[1]);
    				$surname = ucwords(trim(strtolower($cleanStr)));
    			}

    			if (isset($record[2]) && !empty($record[2])) {
    				$email = trim(strtolower($record[2]));

    				if(!preg_match("/^[^@]+@[^@]+\.[a-z]{2,6}$/i",$email)) {
    					$insert = false;
    					print "\n";
    					print "The following email is invalid \n";
    					print $email;
						print "\n";
					} 				
    			}

    			if ($dry) {
    				print "Name : $name | Surname : $surname | Email : $email";
					print "\n";
    			} else {
    				if ($insert) {
    					try {
    						$stmt = $this->getConnecton()->prepare("INSERT INTO users (name, surname, email) VALUES (?, ?, ?)");
							$stmt->bind_param("sss", $name, $surname, $email);
	    					$stmt->execute();
	    					print "Row written to database \n";
	    				} catch (\Exception $e) {
	    					print "Error occurred while writing record to database. Reason : " . $e->getMessage() . "\n";
	    				}
					} 
    			}				
    		}

    		$this->getConnecton()->close();
    	}
    }

    // Get mysql DB connection
    public function getConnecton() {
        if (!$this->connection) {
            $this->connectDB();
        }
        
        return $this->connection;    
    }
}