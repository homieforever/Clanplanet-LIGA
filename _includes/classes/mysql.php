<?
/*
    @ PHP5 Mysql-klasse 
    @ Copyright by Web Communication World (www.wccw.in)
    @ Diese Klasse darf frei unter diesem Vermerk eingesetzt, ver�ndert und weitergegeben werden
    @ Weitere Klassen, sind auf www.wccw.in Kostenlos erh�ltlich


    Verwendete Funktionen:
    @ mysql_connect
    @ mysql_select_db
    @ mysql_error
    @ mysql_query
    @ mysql_fetch_array
    @ mysql_fetch_assoc
    @ mysql_fetch_object
    @ mysql_fetch_row
    @ mysql_num_rows
    @ mysql_real_escape_string
    @ mysql_free_result
    @ mysql_insert_id
    @ mysql_close
    @ htmlspecialchars
    @ trigger_error
*/


class mysql
{
    private static $last_injection = '';
    private static $conn_id = null;
    
    
    
    
    public function __construct()
    {
        $this->connect_mysql();
        return(self::$conn_id);
    }
    
    
    private static function connect_mysql()
    {
        self::$conn_id = mysql_connect(MYSQL_HOST , MYSQL_USER, MYSQL_PASSWORT);
        
        if(self::$conn_id === false)
        {
            throw new Exception (mysql_error());
        } 
        else 
        {
            self::set_charset();
            self::select_db();
        }
    }
    
    private static function set_charset()
    {
        mysql_set_charset('utf8',self::$conn_id);
    }
    
    
    private static function select_db()
    {
        $select = mysql_select_db(MYSQL_DB,self::$conn_id);
        
        if(!$select)
        {
            throw new Exception (mysql_error());
        }
    }
    
    
    public static function check_connect()
    {
        if(self::$conn_id == null)
        {
            self::connect_mysql();
        }
    }
    
    
    public static function query($sqlcode)
    {
        self::check_connect();
        self::$last_injection = mysql_query($sqlcode);
        
            if(!self::$last_injection)
            {
                throw new Exception (mysql_error());
            }
            
        return(self::$last_injection);
    }
    
    
    
    public static function array_result($sql = NULL)
    {
        self::check_connect();

        $inc = '';
        if($sql === NULL)
        {
            $inc = self::$last_injection;
            } else {
            $inc = $sql;
        }
        
        $row = @mysql_fetch_array($inc);
        
        return $row;
    }
    
    
    
    public static function row_result($sql = NULL, &$row = '')
    {
        self::check_connect();
        $inc = '';
        if($sql === NULL)
        {
            $inc = self::$last_injection;
            } else {
            $inc = $sql;
        }
        
        $row = mysql_fetch_row($inc);
        
        return($row);
    }
    
    
    
    public static function object_result($sql = NULL, &$row = '')
    {
        self::check_connect();
        $inc = '';
        if($sql === NULL)
        {
            $inc = self::$last_injection;
            } else {
            $inc = $sql;
        }
        
        $row = mysql_fetch_object($inc);
        
        return($row);
    }
    
    
    
    public static function assoc_result($sql = NULL, &$row = '')
    {
        self::check_connect();
        $inc = '';
        if($sql === NULL)
        {
            $inc = self::$last_injection;
            } else {
            $inc = $sql;
        }
        
        $row = mysql_fetch_assoc($inc);
        
        return($row);
    }
    
    
    
    public static function num_result($sql = NULL)
    {
        self::check_connect();
        $inc = '';
        if($sql === NULL)
        {
            $inc = self::$last_injection;
            } else {
            $inc = $sql;
        }
        
        $num = mysql_num_rows($inc);
        
        return($num);
    }
    
    
    
    public static function free_result($sql = NULL)
    {
        self::check_connect();
        $inc = '';
        if($sql === NULL)
        {
            $inc = self::$last_injection;
            } else {
            $inc = $sql;
        }
        
        mysql_free_result($inc);
    }
    
    
    
    public static function result($set = 0, $field = 0, $sql = NULL, &$row = '')
    {
        self::check_connect();
        $inc = '';
        if($sql === NULL)
        {
            $inc = self::$last_injection;
            } else {
            $inc = $sql;
        }
        
        $row = mysql_result($result, $set, $field);
        
        return($row);
    }
    
    
    
    public static function get_id()
    {
        self::check_connect();
    
        $row = mysql_insert_id(self::$conn_id);
        
        return($row);
    }
    
    public static function close_connect()
    {
        self::check_connect();
        mysql_close(self::$conn_id);
    }
    
    public static function escape($string)
    {
        self::check_connect();
        return mysql_real_escape_string($string, self::$conn_id);
    }
    
    public static function uncape($string)
    {
        self::check_connect();
        $string = str_replace("\\", "", $string);
        return $string;
    }
}