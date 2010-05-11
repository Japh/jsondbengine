<?php
/*
 * @name JSONDB
 * @params host, username, password, database
 * Class to connect to MySQL database and return results as JSON.
 */
class jsondbengine
{
    /* Private variables */
    private $mysqli;
    private $result;
    
    /* Public variables */
    public $query;
    public $rows;
    public $lastID;
    public $last_error;
    
    /*
     * __construct
     * @params host, username, password, database
     * Initiates new MySQLi connection.
     */
    function __construct ($host, $user, $pass, $dbname)
    {
        $this->last_error = null;
        $this->mysqli = new mysqli($host, $user, $pass, $dbname);
        if (mysqli_connect_errno())
        {
            $this->throw_error("Connect failed: " . mysqli_connect_error() . "\n");
        }
    }
    
    public function run ($query, $args)
    {
        $this->query = $query;
        
    }
    
    /*
     * exec
     * @params query
     * Executes the given query.
     */
    public function q($query = '')
    {
        $this->query = $query;
        
        if (empty($this->query))
        {
            return false;
        }
        
        $this->result = $this->mysqli->query($this->query);
        if (!$this->result)
        {
            $this->throw_error('Query failed.');
        }
        $this->rows = $this->mysqli->affected_rows;
        if (stripos($this->query, 'INSERT') !== false)
        {
            $this->lastID = $this->mysqli->insert_id;
        }
    }
    
    /*
     * exec
     * @params query
     * Executes the given query.
     */
    public function exec($query = '')
    {
        $this->query = $query;
        
        if (empty($this->query))
        {
            return false;
        }
        
        $this->result = $this->mysqli->query($this->query);
        if (!$this->result)
        {
            $this->throw_error('Query failed.');
        }
        $this->rows = $this->mysqli->affected_rows;
        if (stripos($this->query, 'INSERT') !== false)
        {
            $this->lastID = $this->mysqli->insert_id;
        }
    }
    
    /*
     * fetch
     * @params type
     * Fetches the results from a previously run query.
     */
    public function fetch($type = 'object')
    {
        switch ($type)
        {
            case 'array':
                return $this->result->fetch_array();
            case 'assoc':
                return $this->result->fetch_assoc();
            case 'row':
                return $this->result->fetch_row();
            case 'object':
                return $this->result->fetch_object();
            default:
                return false;
        }
    }
    
    /*
     * db_error
     * @params msg
     * Outputs MySQL error information including the passed message.
     */
    public function throw_error($msg = '')
    {
        $this->last_error = $msg;
        if (empty($this->suppress_errors))
        {
            $output = '';
            $output .= '<p><strong>Database error</strong><br />';
            $output .= '<em>Message:</em> ' . $msg . '<br />';
            $output .= '<em>Database:</em> ' . $this->config['dbname'] . '<br />';
            $output .= '<em>Query:</em> ' . $this->query . '<br />';
            $output .= '<em>Error:</em> ' . ($this->mysqli->connect_error ? $this->mysqli->connect_error : $this->mysqli->error) . '</p>';
            exit($output);
        }
    }
}
