<?php
require_once('config.php');
require_once('Ingredient.php');
require_once('Menu.php');
require_once('DiscountPolicy.php');
require_once('CourseDish.php');
require_once('CustomizedCourseDishInfo.php');
require_once('RegisteredUserInfo.php');
require_once('OrderingInfo.php');

/**
 * DBTransactionManager short summary.
 *
 * DBTransactionManager description.
 *
 * @version 1.0
 * @author psj
 */

define("DB_EMP_DELIVERED","DELIVERY");
define("DB_EMP_COOKED","COOK");
define("DB_EMP_MAT","ADMIN");

define("DB_MENU_TYPE_SIMPLE",'1');
define("DB_MENU_TYPE_DELUXE",'2');
define("DB_MENU_TYPE_GRAND",'3');

class DBTransactionManager
{
    private static $m_Instance = null;
    
    private $con = false;
    private $db_host = '127.0.0.1:9090';
    private $db_user = 'dbclient';
    private $db_pass = 'qwerty';
    private $db_name = 'test';
    
    private $_db_connect;
    private $error_message;
    
    private function __construct()
    {
        $this->connect();    
        //$this->createDefaultTables(); //temp
    }
    
    public function __destruct()
    {
        $this->disconnect();
    }
    
    function GetErrorMessage()
    {
        return $this->error_message;
    }
    
    static function GetInstance()
    {
        if(DBTransactionManager::$m_Instance === null)
        {
            DBTransactionManager::$m_Instance = new DBTransactionManager();
        }
        return DBTransactionManager::$m_Instance;
    }
    
    private function tableExists($table)
    {
        $tablesInDb = @mysql_query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
        if($tablesInDb)
        {
            if(mysql_num_rows($tablesInDb)==1)
            {
                return true; 
            }
            else
            {   
                $this->error_message = "ERROR: non table :" .$table;
                return false; 
            }
        }
    }
    
    private function connect()
    {
        if(!$this->con)
        {
            $this->_db_connect = @mysql_connect($this->db_host,$this->db_user,$this->db_pass) ;
            if($this->_db_connect)
            {
                
                // utf8 option
                mysql_set_charset("utf8",$this->_db_connect);
                mysql_query("set names utf8",$this->_db_connect);
                
                mysql_query("set session character_set_connection=utf8;",$this->_db_connect);
                mysql_query("set session character_set_results=utf8;",$this->_db_connect);
                mysql_query("set session character_set_client=utf8;",$this->_db_connect);
                
                $seldb = @mysql_select_db($this->db_name,$this->_db_connect);
                
                if($seldb)
                {
                    $this->con = true; 
                    return true; 
                } else
                {
                    $this->error_message = "ERROR: could not select DB -> create new DB :";
                    @mysql_create_db($this->db_name,$this->_db_connect);
                    return true;
                }
            } else
            {
                $this->error_message = "ERROR: db server connect error " . $this->db_name;
                return false; 
            }
        } else
        {
            
            return true; 
        }
    }
    
    private function disconnect()
    {
        if($this->con)
        {
            if(@mysql_close())
            {
                $this->con = false; 
                @mysql_close();
                return true; 
            }
            else
            {
                return false; 
            }
        }
    }
    
    function mysql_query_custom($query,$link_identifier = NULL)
    {
        //return mysql_query($query,$link_identifier);
        return @mysql_query($query,$link_identifier);
    }
    
    private function createDefaultTables()
    {
        
        
        $sql = "CREATE TABLE CUSTOMER
                    (
	                    ID              CHAR(50) NOT NULL,
	                    PASSWORD        CHAR(50) NULL,
	                    ADDRESS         CHAR(60) NULL,
	                    NAME            CHAR(20) NULL,
	                    PHONE           CHAR(20) NULL,
	                    CARDNUM         CHAR(20) NULL,
	                    JOIN_DATE		DATE NULL,
	                    PRIMARY KEY(ID)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
        $sql = "CREATE TABLE EMPLOYEE
                    (
	                    ID                  CHAR(50) NOT NULL,
	                    PASSWORD            CHAR(50) NULL,
	                    EMP_TYPE            CHAR(20) NULL,
	                    EMP_IP              CHAR(20) NULL,
	                    EMP_RANK            CHAR(20) NULL,
	                    SALARY              INT NULL,
	                    JOIN_DATE           DATE NULL,
	                    PRIMARY KEY(ID)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
        $sql = "CREATE TABLE MENU_MAKEUP
                    (
	                    IDX_MENU         INT NOT NULL,
	                    IDX_INGR         INT NOT NULL,
	                    INGR_NEED_GRAM   INT NOT NULL,
	                    LAST_UPDATE      DATE NULL,
	                    PRIMARY KEY (IDX_MENU,IDX_INGR)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
        
        $sql = "CREATE TABLE MENU
                    (
	                    IDX_MENU           INT auto_increment NOT NULL,
	                    MENU_NAME          CHAR(40) NULL,
	                    MENU_TYPE          CHAR(20) NULL,
	                    MENU_DETAIL        CHAR(100) NULL,
	                    LAST_UPDATE        DATE NULL,
	                    PRICE              INT NOT NULL,
                        DEL                CHAR(1) NULL,
	                    PRIMARY KEY(IDX_MENU)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
        
        $sql = "CREATE TABLE INGREDIENTS
                    (
	                    IDX_INGR            INT auto_increment NOT NULL,
	                    INGR_NAME           CHAR(20) NULL,
	                    STOCK_GRAM          INT NOT NULL,
	                    ABLE_STOCK_GRAM     INT NOT NULL,
	                    INGR_TYPE     	    CHAR(20) NULL,
	                    DEL              	CHAR(1) NULL,
	                    LAST_UPDATE       	DATE NULL,
	                    PRIMARY KEY (IDX_INGR)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
        $sql = "CREATE TABLE COURSE_MAKEUP
                    (
	                    IDX_COURSE       	INT NOT NULL,
	                    IDX_MENU           	INT NOT NULL,
                        MENU_NUM			INT NOT NULL,
	                    MENU_SUB_TYPE		CHAR(4) NULL,
                        LAST_UPDATE        	DATE NULL,
	                    PRIMARY KEY (IDX_COURSE,IDX_MENU)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
        $sql = "CREATE TABLE COURSE
                    (
	                    IDX_COURSE       	INT auto_increment NOT NULL,
	                    COURSE_NAME         CHAR(40) NULL,
	                    COURSE_TYPE       	CHAR(20) NULL,
	                    COURSE_STYLE    	CHAR(3) NULL,
	                    COURSE_DETAIL       CHAR(200) NULL,
	                    COOK_TIME  			TIME NULL,
                        DEL                 CHAR(1) NULL,
	                    PRIMARY KEY(IDX_COURSE)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
        $sql = "CREATE TABLE ORDER_MAKEUP
                    (
	                    IDX_ORDER           INT NOT NULL,
	                    IDX_COURSE       	INT NOT NULL,
	                    IDX_MENU			INT NOT NULL,
	                    ORDER_GROUP         INT NOT NULL,
	                    COURSE_STYLE        CHAR(3) NULL,
	                    COURSE_NAME         CHAR(40) NULL,
	                    MENU_NAME           CHAR(40) NULL,
	                    MENU_SUB_TYPE       CHAR(4) NULL,
	                    MENU_NUM            INT NOT NULL,
	                    PRIMARY KEY(IDX_ORDER,IDX_COURSE,IDX_MENU,ORDER_GROUP)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
        $sql = "CREATE TABLE ORDER_HISTORY
                    (
	                    IDX_ORDER          		  INT auto_increment NOT NULL,
                        ID					 	  CHAR(20) NULL,
	                    NAME				      CHAR(20) NULL,
	                    PHONE				      CHAR(20) NULL,
	                    ADDRESS                   CHAR(18) NULL,
	                    IS_JOIN				      CHAR(1) NULL,
                        IS_DISCOUNT			      CHAR(1) NULL,
	                    TOTAL_PRICE               INT NOT NULL,
	                    CARDNUM                   CHAR(20) NULL,
                        CANCELED			      CHAR(1) NULL,
	                    ORDER_REQUEST_TIME        DATETIME NOT NULL,
	                    REQUESTED_DELIVERED_TIME  DATETIME NULL,
	                    COOKING_START_TIME        DATETIME NULL,
	                    COOKING_END_TIME          DATETIME NULL,
	                    DELIVERY_START_TIME       DATETIME NULL,
	                    DELIVERY_END_TIME         DATETIME NULL,
	                    ORDER_STATE            	  CHAR(1) NULL,
	                    DELIVERY_MAN              CHAR(20) NULL,
	                    PRIMARY KEY (IDX_ORDER)
                    )
                    ;";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);

        $sql = "CREATE TABLE DISCOUNT_POLICY
                    (
	                    IDX_DISCOUNT     INT auto_increment NOT NULL,
	                    START_DATE       DATETIME NOT NULL,
	                    DISCOUNT_RATE    INT NOT NULL,
	                    DISCOUNT_GAP     INT NOT NULL,
	                    PRIMARY KEY (IDX_DISCOUNT)
                    )";
        
        $result = @mysql_query($sql);
        //$this->query_debug_print($sql,$result);
        
    }
    
    
    // need sql injection block
    function CheckUserValidate($id,$pw)
    {
        
        //encrypted later?
        $sql = "SELECT UNI.ID, UNI.PASSWORD FROM (SELECT ID,PASSWORD FROM EMPLOYEE UNION SELECT ID,PASSWORD FROM CUSTOMER) AS UNI
        WHERE TRIM(UNI.ID) = TRIM('" .$id. "') AND TRIM(UNI.PASSWORD) = TRIM('" . $pw . "')";
        $result = @mysql_query($sql);
        if(!$result)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return false;
        }
        $numResults = mysql_num_rows($result);
        if($numResults >= 1) 
        {
            return true; 
        }
        else 
        {
            $this->error_message = "ERROR: non id";
            return false;
        }
    }
    
    // need sql injection block
    function CheckEmployeeValidate($id,$pw)
    {
       
        //encrypted later?
        $sql = "SELECT UNI.ID, UNI.PASSWORD, UNI.EMP_TYPE FROM (SELECT ID,PASSWORD,EMP_TYPE FROM EMPLOYEE UNION SELECT ID,PASSWORD,'' FROM CUSTOMER) AS UNI
        WHERE TRIM(UNI.ID) = TRIM('" .$id. "') AND TRIM(UNI.PASSWORD) = TRIM('" . $pw . "')";
        $result = @mysql_query($sql);
        if(!$result)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return SESSION_TYPE_WRONG;
        }
        $numResults = mysql_num_rows($result);
        if($numResults >= 1) 
        {
            $record = mysql_fetch_array($result);
            switch ($record['EMP_TYPE'])
            {
            	case DB_EMP_MAT:
                    return SESSION_TYPE_MAT;
                case DB_EMP_COOKED:
                    return SESSION_TYPE_EMP_COOKED;
                case DB_EMP_DELIVERED:
                    return SESSION_TYPE_EMP_DELIVERED;
                default:
                    $this->error_message ="ERROR: non register type or ";
                    return SESSION_TYPE_WRONG;
            }
            
        }
        else 
        {
            $this->error_message = "ERROR: non id or false password";
            return SESSION_TYPE_WRONG;
        }
    }
    // 
    function CheckIpValidate($ip)
    {
        //encrypted later?
        
        $sql = "SELECT ID,PASSWORD FROM EMPLOYEE WHERE TRIM(EMP_IP) = TRIM('" . $ip . "')";
        $result = mysql_query($sql);
        if(!$result)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return false;
        }
        $numResults = mysql_num_rows($result);
        if($numResults >= 1)
        {
            return true; 
        }
        else 
        {
            $this->error_message = "ERROR: non ip";
            return false;
        }
        
    }
    function CheckMenuValidate($menu_names)
    {
        
        // only name? what about menu_key?
        // two names -> two item 
        // spec change names->key
        if (is_array($menu_names))
        {
            $sql = "SELECT INGSUM.IDX_INGR AS IDX_INGR, INGSUM.ING_SUM AS SUM, ING.ABLE_STOCK_GRAM AS STOCK 
                        FROM (
                            SELECT IDX_INGR, SUM(INGR_NEED_GRAM) AS ING_SUM
                            FROM (";
            
            for($i=0;$i < count($menu_names); $i++)
            {
                $sql_subs[$i] = "SELECT IDX_INGR, INGR_NEED_GRAM 
                                FROM MENU AS ME, MENU_MAKEUP AS MUP
                                WHERE ME.IDX_MENU = MUP.IDX_MENU AND
                                TRIM(ME.IDX_MENU) = TRIM('" .$menu_names[$i]. "') ";
            }
            $sql_subs = implode(' UNION ALL ',$sql_subs);
            
            $sql .= $sql_subs. " ) AS UNI GROUP BY UNI.IDX_INGR
                        ) AS INGSUM, INGREDIENTS AS ING 
                        WHERE INGSUM.IDX_INGR = ING.IDX_INGR AND INGSUM.ING_SUM > ING.ABLE_STOCK_GRAM;";
            
            $result = @mysql_query($sql);
            if(!$result)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql;
                return false;
            }
            $numResults = mysql_num_rows($result);
            //1 row + over is error
            if($numResults >= 1) 
            {
                $this->error_message = "ERROR: ingredients valid error , failure in supply";
                return false; 
            }
            else 
            {
                return true;
            }
        }
        else //one item
        {
            
            $sql = "SELECT INGSUM.IDX_INGR AS IDX_INGR, INGSUM.ING_SUM AS SUM, ING.ABLE_STOCK_GRAM AS STOCK 
                        FROM (
                            SELECT IDX_INGR, SUM(INGR_NEED_GRAM) AS ING_SUM
                            FROM (";
            
            $sql_subs = "SELECT IDX_INGR, INGR_NEED_GRAM 
                            FROM MENU AS ME, MENU_MAKEUP AS MUP
                            WHERE ME.IDX_MENU = MUP.IDX_MENU AND
                            TRIM(ME.IDX_MENU) = TRIM('" .$menu_names. "') ";
            
            $sql .= $sql_subs. " ) AS UNI GROUP BY UNI.IDX_INGR
                        ) AS INGSUM, INGREDIENTS AS ING 
                        WHERE INGSUM.IDX_INGR = ING.IDX_INGR AND INGSUM.ING_SUM > ING.ABLE_STOCK_GRAM;";
            
            $result = @mysql_query($sql);
            if(!$result)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql;
                return false;
            }
            $numResults = mysql_num_rows($result);
            if($numResults >= 1) 
            {
                $this->error_message = "ERROR: ingredients valid error , failure in supply";
                return false; 
            }
            else 
            {
                return true;
            }
        }
        
    }
    function CheckUserIdDuplicated($id)
    {
        //encrypted later?
        $sql = "SELECT ID,PASSWORD FROM CUSTOMER WHERE TRIM(ID) = TRIM('" . $id . "');";
        $result = @mysql_query($sql);
        if(!$result)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return false;
        }
        $numResults = mysql_num_rows($result);
        if($numResults >= 1)
        {
            return true;
        }
        else 
        {
            return false;
        }
    }
    
    
    // getter
    function GetUserInfo($id)
    {
        //don't mind non-existence of id?
        $sql = "SELECT ID,PASSWORD,ADDRESS,NAME,PHONE,CARDNUM 
                    FROM CUSTOMER 
                    WHERE TRIM(ID) = TRIM('" . $id . "');";
        $result = @mysql_query($sql);
        if(!$result)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return false;
        }
        $numResults = mysql_num_rows($result);
        if($numResults != 1)
        {
            $this->error_message = "ERROR: non-existed id or duplicated id";
            return false;
        }
        $record = mysql_fetch_array($result);
        //$userinfo = new RegisteredUserInfo($record['NAME'],$record['PHONE'],$record['ADDRESS'],$record['CARDNUM'],$record['ID'],$record['PASSWORD']);
        $userinfo = new RegisteredUserInfo($record['NAME'],$record['PHONE'],$record['ADDRESS'],$record['CARDNUM'],$record['ID'],"");
        return $userinfo;
    }
    
    
    function GetCourseDishList()
    {
        //need all?
        $courseDishList = array();    
        
        $sql_course = "SELECT IDX_COURSE, COURSE_NAME, COURSE_TYPE, COURSE_STYLE, COURSE_DETAIL FROM COURSE WHERE TRIM(DEL) IS NULL ORDER BY TRIM(COURSE_NAME)";
        $result_course = @mysql_query($sql_course);
        if(!$result_course)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql_course;
            return false;
        }
        $numResults_course = mysql_num_rows($result_course);
        if($numResults_course > 0)
        {
            for($i=0;$i<$numResults_course;$i++)
            {
                $record_course = mysql_fetch_array($result_course);
                $courseDish = new CourseDish($record_course['IDX_COURSE'],$record_course['COURSE_NAME']);
                
                $str = $record_course['COURSE_STYLE'];
                if(substr($str,0,1) == 'T') $courseDish->AddAvailableStyle(MENU_TYPE_SIMPLE);
                if(substr($str,1,1) == 'T') $courseDish->AddAvailableStyle(MENU_TYPE_DELUXE);
                if(substr($str,2,1) == 'T') $courseDish->AddAvailableStyle(MENU_TYPE_GRAND);
                $courseDish->SetDescription($record_course['COURSE_DETAIL']);
                
                $sql_menu = "SELECT MENU.IDX_MENU, MENU.MENU_NAME, MENU.MENU_DETAIL, MENU.PRICE ,CUP.MENU_SUB_TYPE, CUP.MENU_NUM
                    FROM MENU , COURSE_MAKEUP AS CUP 
                    WHERE MENU.IDX_MENU = CUP.IDX_MENU AND CUP.IDX_COURSE = '" .$record_course['IDX_COURSE']. 
                "' AND TRIM(MENU.DEL) IS NULL ORDER BY IF(TRIM(CUP.MENU_SUB_TYPE)='main','1','2'), MENU.MENU_NAME";
                $result_menu = @mysql_query($sql_menu);
                if(!$result_menu)
                {
                    $this->error_message = "ERROR: query error, SQL : " .$sql_menu;
                    return false;
                }
                $numResults_menu = mysql_num_rows($result_menu);
                if($numResults_menu > 0)
                {
                    for($j=0;$j<$numResults_menu;$j++)
                    {
                        $record_menu = mysql_fetch_array($result_menu);
                        
                        
                        $count = $record_menu['MENU_NUM'];
                        for($k=0;$k<$count;$k++)
                        {
                            $menu = new Menu($record_menu['IDX_MENU'],$record_menu['MENU_NAME'], $record_menu['PRICE']);
                            $menu->SetDescription($record_menu['MENU_DETAIL']);
                            
                            $sql_ing = "SELECT MUP.IDX_MENU, ING.IDX_INGR, ING.INGR_NAME, MUP.INGR_NEED_GRAM AS VAL 
                                FROM MENU_MAKEUP AS MUP, INGREDIENTS AS ING 
                                WHERE MUP.IDX_INGR = ING.IDX_INGR AND MUP.IDX_MENU = '" .$record_menu['IDX_MENU']. "' 
                                ORDER BY ING.INGR_NAME;";
                            $result_ing = @mysql_query($sql_ing);
                            if(!$result_ing)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql_ing;
                                return false;
                            }
                            $numResults_ing = mysql_num_rows($result_ing);
                            if($numResults_ing > 0)
                            {
                                for($m=0;$m<$numResults_ing;$m++)
                                {
                                    $record_ing = mysql_fetch_array($result_ing);
                                    $ing = new Ingredient($record_ing['IDX_INGR'],$record_ing['INGR_NAME'],$record_ing['VAL']);
                                    $menu->AddIngredientList($ing);
                                }
                            }
                            
                            if(trim($record_menu['MENU_SUB_TYPE'])=='main')
                            {
                                $courseDish->AddMainMenu($menu);                             
                            }
                            else
                            {
                                $courseDish->AddSubMenu($menu);
                            }
                            
                        }
                    }
                }
                
                //final
                array_push($courseDishList,$courseDish);
            }
        }
        
        return $courseDishList;
        
    }
    
    //what?
    function GetMenuInfoList($type)
    {
        if(is_null($type))
        {
            $menu_list = array();
            $sql_menu = "SELECT IDX_MENU, MENU_NAME, MENU_TYPE, MENU_DETAIL, PRICE 
                            FROM MENU 
                            WHERE TRIM(DEL) IS NULL 
                            ORDER BY TRIM(MENU_NAME);";
            $result_menu = @mysql_query($sql_menu);
            if(!$result_menu)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql_menu;
                return false;
            }
            $numResults_menu = mysql_num_rows($result_menu);
            for($i=0;$i<$numResults_menu;$i++)
            {
                $record_menu = mysql_fetch_array($result_menu);
                $menu = new Menu($record_menu['IDX_MENU'],$record_menu['MENU_NAME'], $record_menu['PRICE']);
                $menu->SetDescription($record_menu['MENU_DETAIL']);
                
                $sql_ing = "SELECT MUP.IDX_MENU, ING.IDX_INGR, ING.INGR_NAME, MUP.INGR_NEED_GRAM AS VAL 
                                FROM MENU_MAKEUP AS MUP, INGREDIENTS AS ING 
                                WHERE MUP.IDX_INGR = ING.IDX_INGR AND MUP.IDX_MENU = '" .$record_menu['IDX_MENU']. "' 
                                ORDER BY ING.INGR_NAME;";
                $result_ing = @mysql_query($sql_ing);
                if(!$result_ing)
                {
                    $this->error_message = "ERROR: query error, SQL : " .$sql_ing;
                    return false;
                }
                $numResults_ing = mysql_num_rows($result_ing);
                if($numResults_ing > 0)
                {
                    for($m=0;$m<$numResults_ing;$m++)
                    {
                        $record_ing = mysql_fetch_array($result_ing);
                        $ing = new Ingredient($record_ing['IDX_INGR'],$record_ing['INGR_NAME'],$record_ing['VAL']);
                        $menu->AddIngredientList($ing);
                    }
                }
                array_push($menu_list,$menu);
            }
            
            return $menu_list;
        }
        else if ($type instanceof Menu)
        {
            //특정 메뉴
            $_key = $type->GetDBkey();
            $sql_menu = "SELECT IDX_MENU, MENU_NAME, MENU_TYPE, MENU_DETAIL, PRICE 
                            FROM MENU WHERE IDX_MENU = '" .$_key. "'";
            
            $result_menu = @mysql_query($sql_menu);
            if(!$result_menu)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql_menu;
                return false;
            }
            $numResults_menu = mysql_num_rows($result_menu);
            if($numResults_menu == 0)
            {
                $this->error_message = "ERROR: non menu id";
                return false;
            }
            $record_menu = mysql_fetch_array($result_menu);
            $menu = new Menu($record_menu['IDX_MENU'],$record_menu['MENU_NAME'], $record_menu['PRICE']);
            $menu->SetDescription($record_menu['MENU_DETAIL']);
            
            $sql_ing = "SELECT MUP.IDX_MENU, ING.IDX_INGR, ING.INGR_NAME, MUP.INGR_NEED_GRAM AS VAL 
                            FROM MENU_MAKEUP AS MUP, INGREDIENTS AS ING 
                            WHERE MUP.IDX_INGR = ING.IDX_INGR AND MUP.IDX_MENU = '" .$record_menu['IDX_MENU']. "' 
                            ORDER BY ING.INGR_NAME;";
            $result_ing = @mysql_query($sql_ing);
            if(!$result_ing)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql_ing;
                return false;
            }
            $numResults_ing = mysql_num_rows($result_ing);
            if($numResults_ing > 0)
            {
                for($m=0;$m<$numResults_ing;$m++)
                {
                    $record_ing = mysql_fetch_array($result_ing);
                    $ing = new Ingredient($record_ing['IDX_INGR'],$record_ing['INGR_NAME'],$record_ing['VAL']);
                    $menu->AddIngredientList($ing);
                }
            }
            return $menu;
            
        }
        else
        {
            $this->error_message = "ERROR: GetMenuInfoList -> type error";
            return false;
        }
    }
    
    
    // NULL인자 넣고 시간상으로 가장 나중의 할인 정책을 DiscountPolicy 객체로 리턴
    function GetDiscountPolicy()
    {
        //return latest date
        $sql = "SELECT DISCOUNT_RATE, DISCOUNT_GAP, START_DATE 
                    FROM DISCOUNT_POLICY 
                    ORDER BY START_DATE DESC";
        $result = @mysql_query($sql);
        if(!$result)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return false;
        }
        $numResults = mysql_num_rows($result);
        if($numResults == 0)
        {
            $this->error_message = "ERROR: empty policy";
            return false;
        }
        //just 1 row
        $record = mysql_fetch_array($result);
        $policy = new DiscountPolicy($record['DISCOUNT_GAP'], $record['DISCOUNT_RATE']);
        return $policy;
    }
    
    
    // 인자 NULL이면 모든 재료 리스트 반환
    // 인자타입 단일 Ingredient면 key값 확인해서 없으면 false 있으면 객체 내용 넣어서 반환
    function GetSuppliesInfoList($type = null)
    {
        // print all
        if(is_null($type))
        {
            $ing_list = array();
            
            $sql = "SELECT IDX_INGR, INGR_NAME, STOCK_GRAM, ABLE_STOCK_GRAM 
                        FROM INGREDIENTS ORDER BY IDX_INGR,TRIM(INGR_NAME)";
            $result = @mysql_query($sql);
            if(!$result)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql;
                return false;
            }
            $numResults = mysql_num_rows($result);
            for($i=0;$i<$numResults;$i++)
            {
                $record = mysql_fetch_array($result);
                $ing = new Ingredient($record['IDX_INGR'],$record['INGR_NAME'],$record['ABLE_STOCK_GRAM']);
                array_push($ing_list,$ing);
            }
            
            return $ing_list;
            
        }
        // print one
        else if($type instanceof Ingredient)
        {
            //else if(is_string($type))
            //$_key = $type;
            $_key = $type->GetDBkey();
            
            $sql = "SELECT IDX_INGR, INGR_NAME, STOCK_GRAM, ABLE_STOCK_GRAM 
                        FROM INGREDIENTS WHERE IDX_INGR = '" .$_key. "';";
            $result = @mysql_query($sql);
            if(!$result)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql;
                return false;
            }
            $numResults = mysql_num_rows($result);
            if ($numResults != 1)
            {
                $this->error_message = "ERROR: non Ingredient IDX";
                return false;
            }
            $record = mysql_fetch_array($result);
            $type = new Ingredient($record['IDX_INGR'],$record['INGR_NAME'],$record['ABLE_STOCK_GRAM']);
            return $type;
            
        }
        else
        {
            $this->error_message = "ERROR: GetSuppliesInfoList -> type error";
            return false;
        }
    }
    
    //up-to-date count
    function GetOrderingInfoList($id,$count)
    {
        $order_list = array();
        
        $sql = "SELECT IDX_ORDER, ID, NAME, PHONE, ADDRESS, IS_JOIN, IS_DISCOUNT, TOTAL_PRICE, CARDNUM, ORDER_REQUEST_TIME, REQUESTED_DELIVERED_TIME, COOKING_START_TIME, COOKING_END_TIME, DELIVERY_START_TIME, DELIVERY_END_TIME, CANCELED FROM ORDER_HISTORY WHERE TRIM(ID) = '" .$id. "' AND TRIM(CANCELED) IS NULL ORDER BY ORDER_REQUEST_TIME DESC";
        $result_order = @mysql_query($sql);
        if(!$result_order)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return false;
        }
        $numResults_order = mysql_num_rows($result_order);
        if($count==0)
        {
            $count = $numResults_order;
        }
        else
        {
            $count = ($numResults_order > $count) ? $count : $numResults_order;
        }
        for($i=0;$i<$count;$i++)
        {
            $record_order = mysql_fetch_array($result_order);
            //passwd null
            $regiUser = new RegisteredUserInfo($record_order['NAME'],$record_order['PHONE'],$record_order['ADDRESS'],$record_order['CARDNUM'],$record_order['ID'],"");
            
            $order = new OrderingInfo($regiUser,$record_order['TOTAL_PRICE'],($record_order['IS_DISCOUNT']=="T") ? true : false, $record_order['ORDER_REQUEST_TIME'],$record_order['REQUESTED_DELIVERED_TIME']);
            $order->SetDBKey($record_order['IDX_ORDER']);
            $order->SetCookingStartTime($record_order['COOKING_START_TIME']);
            $order->SetCookingEndTime($record_order['COOKING_END_TIME']);
            $order->SetDeliveryStartTime($record_order['DELIVERY_START_TIME']);
            $order->SetDeliveryEndTime($record_order['DELIVERY_END_TIME']);
            
            $sql_dish = "SELECT DISTINCT IDX_ORDER, IDX_COURSE, ORDER_GROUP,COURSE_STYLE,COURSE_NAME FROM ORDER_MAKEUP WHERE IDX_ORDER = '" .$record_order['IDX_ORDER']."'";
            $result_dish = @mysql_query($sql_dish);
            if(!$result_dish)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql_dish;
                return false;
            }
            $numResults_dish = mysql_num_rows($result_dish);
            for($j=0;$j<$numResults_dish;$j++)
            {
                $record_dish = mysql_fetch_array($result_dish);
                $_temp_course_key = $record_dish['IDX_COURSE'];
                $_temp_group_key = $record_dish['ORDER_GROUP'];
                
                $sql_course_menu = "SELECT IDX_MENU, MENU_NUM, MENU_NAME, COURSE_NAME, COURSE_STYLE FROM ORDER_MAKEUP WHERE IDX_COURSE ='" .$_temp_course_key. "' AND ORDER_GROUP = '".$_temp_group_key."' AND IDX_ORDER = '".$record_order['IDX_ORDER']. "' ORDER BY IF(TRIM(MENU_SUB_TYPE)='main','1','2'), MENU_NAME";
                $result_course_menu = @mysql_query($sql_course_menu);
                if(!$result_course_menu)
                {
                    $this->error_message = "ERROR: query error, SQL : " .$sql_course_menu;
                    return false;
                }
                $numResults_course_menu = mysql_num_rows($result_course_menu);
                
                
                $dish = new CustomizedCourseDishInfo($this->strTOstyle($record_dish['COURSE_STYLE']),$record_dish['COURSE_NAME'],$_temp_course_key);                    $list_menu = array();

                for($k=0;$k<$numResults_course_menu;$k++)
                {
                    
                    $record_course_menu = mysql_fetch_array($result_course_menu);
                    
                    $iter_num = $record_course_menu['MENU_NUM'];
                    for($m=0;$m<$iter_num;$m++)
                    {
                        array_push($list_menu,$record_course_menu['IDX_MENU']);
                    }
                }
                $dish->SetMenuNameList($list_menu);
                $order->AddCustomizedCourseDish($dish);
                
            }
            array_push($order_list,$order);
            
        }
        return $order_list;
        
    }
    
    
    function GetOrderingCount($id)
    {
        $sql = "SELECT ID FROM CUSTOMER WHERE TRIM(ID) = '" .$id. "'";
        $result = @mysql_query($sql);
        if(!$result)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return false;
        }
        $numResults = mysql_num_rows($result);
        if($numResults==0)
        {
            $this->error_message = "ERROR: non existed id ";
            return false;
        }
        $sql = "SELECT IDX_ORDER FROM ORDER_HISTORY 
                    WHERE TRIM(ID) = TRIM('".$id."') AND TRIM(CANCELED) IS NULL AND COOKING_START_TIME IS NOT NULL";
        $result = @mysql_query($sql);
        if(!$result)
        {
            $this->error_message = "ERROR: query error, SQL : " .$sql;
            return false;
        }
        $numResults = mysql_num_rows($result);
        return $numResults;
    }
    
    
    function SetUserInfo($reginfo,$oper)
    {
        if ($reginfo instanceof RegisteredUserInfo)
        {
            
            $_name = $reginfo->GetName();
            $_phone = $reginfo->GetPhoneNumber();
            $_address = $reginfo->GetAddress();
            $_cardnum = $reginfo->GetCreditcardnum();
            $_email = $reginfo->GetEmail();
            $_pass = $reginfo->GetPassword();
            
            $sql = "SELECT ID FROM CUSTOMER WHERE TRIM(ID) = TRIM('" .$_email. "');";
            $result = @mysql_query($sql);
            if(!$result)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql;
                return false;
            }
            $numResults = mysql_num_rows($result);
            
            
            switch($oper)
            {
                case OPER_TYPE_ADD :
                    if($numResults>0)
                    {
                        $this->error_message = "ERROR: id existed";
                        return false;
                    }
                    $sql = "INSERT INTO CUSTOMER VALUES ('" .$_email. "','" .$_pass. "','" .$_address. "','" .$_name. "','" .$_phone. "','" .$_cardnum. "',NOW());";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    return true;
                        
                case OPER_TYPE_MOD :
                    if($numResults!=1)
                    {
                        $this->error_message = "ERROR: non id existed";
                        return false;
                    }
                    $sql = "UPDATE CUSTOMER SET PASSWORD = '" .$_pass. "', ADDRESS = '" .$_address. "', NAME = '" .$_name. "', PHONE = '" .$_phone. "', CARDNUM = '" .$_cardnum. "' WHERE TRIM(ID) = TRIM('" .$_email. "');";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    return true;
                
                case OPER_TYPE_DEL :
                    if($numResults!=1)
                    {
                        $this->error_message = "ERROR: non id exited";
                        return false;
                    }
                    $sql = "DELETE FROM CUSTOMER WHERE TRIM(ID) = TRIM('" .$_email. "');";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    return true;
                
            }    
        }
        else
        {
            $this->error_message = "ERROR: RegisteredUserInfo type error";
            return false;
        }   
        
        
    }
    
    
    function SetOrderingInfo($orderinfo,$oper,&$req_ret)
    {
        if ($orderinfo instanceof OrderingInfo)
        {
            $_key = $orderinfo->GetDBkey();
            $_req_time = $orderinfo->GetOrderRequestTime();
            $_delivery_req_time = $orderinfo->GetRequestDeliveryTime();
            $_total_cost = $orderinfo->GetTotalCost();
            $_is_discounted = $orderinfo->GetIsDiscounted();
            $_userinfo = $orderinfo->GetUserInfo();
            $_ccdlist = $orderinfo->GetCustomizedCourseDishList();
            
            //나머지 시간 업데이트 해놓기
            $_cooking_start_time = $orderinfo->GetCookingStartTime();
            $_cooking_end_time = $orderinfo->GetCookingEndTime();
            $_delivery_start_time = $orderinfo->GetDeliveryStartTime();
            $_delivery_end_time =$orderinfo->GetDeliveryEndTime();
            
            $_is_registered = $_userinfo->IsRegistered();
            
            switch($oper)
            {
                //init register
                case OPER_TYPE_ADD :
                    
                    $row_list = array("ID","NAME","PHONE","ADDRESS","IS_JOIN","IS_DISCOUNT","TOTAL_PRICE","CARDNUM","ORDER_REQUEST_TIME","REQUESTED_DELIVERED_TIME");
                    $val_list = array(($_is_registered == true) ? $_userinfo->GetEmail() : "",$_userinfo->GetName(),$_userinfo->GetPhoneNumber(),$_userinfo->GetAddress(),($_is_registered == true) ? "T" : "",($_is_discounted == true) ? "T" : "",$_total_cost,$_userinfo->GetCreditcardNum(),$_req_time,$_delivery_req_time);
                    
                    if($this->query_insert("ORDER_HISTORY",$val_list,$row_list)==false)
                    {
                        //$this->error_message = "ERROR: ORERINFO INSERT ERROR";
                        return false;
                    }
                    $result = @mysql_query("SELECT LAST_INSERT_ID() AS ID");
                    $record = mysql_fetch_array($result);
                    $_key =  $record['ID'];
                    $req_ret = $_key;
                    $group_counter = 0;
                    $row_list_makeup = array("IDX_ORDER","IDX_COURSE","IDX_MENU","ORDER_GROUP","COURSE_STYLE","COURSE_NAME","MENU_NAME","MENU_SUB_TYPE","MENU_NUM");
                    
                    foreach ($_ccdlist as $ccd)
                    {
                        $menu_list = $ccd->GetMenuNameList();
                        $_course_style_code = $this->styleTOstr($ccd->GetStyle());
                        $_course_name = $ccd->GetOriginCourseName();
                        $_course_key = $ccd->GetDBkey();
                        foreach ($menu_list as $_menu_key)
                        {
                            
                            $_menu_name = $this->KeyToValue("MENU",$_menu_key,"IDX_MENU","MENU_NAME");
                            if($_menu_name == false) continue;
                            //메뉴 주메뉴 부메뉴는 알수가 없음....
                            $sql = "SELECT IDX_ORDER,IDX_MENU,IDX_COURSE,ORDER_GROUP FROM ORDER_MAKEUP 
                                        WHERE IDX_ORDER = '" .$_key. "' AND IDX_MENU = '".$_menu_key. "' AND 
                                        IDX_COURSE = '".$_course_key. "' AND ORDER_GROUP = '" .$group_counter. "'";
                            $result_menu = @mysql_query($sql);
                            $numResults_menu = mysql_num_rows($result_menu);
                            if($numResults_menu == 0)
                            {
                                //추가
                                $val_list_menu = array($_key,$_course_key,$_menu_key,$group_counter,$_course_style_code,$_course_name,$_menu_name,"",1);
                                if($this->query_insert("ORDER_MAKEUP",$val_list_menu,$row_list_makeup)==false)
                                {
                                    //$this->error_message = "ERROR: ORERER MAKEUP INSERT ERROR";
                                    return false;
                                }
                                
                            }
                            else if($numResults_menu == 1)
                            {
                                //값 +1
                                $sql = "UPDATE ORDER_MAKEUP SET MENU_NUM = MENU_NUM+1 
                                        WHERE IDX_ORDER = '" .$_key. "' AND IDX_MENU = '".$_menu_key. "' AND 
                                        IDX_COURSE = '".$_course_key. "' AND ORDER_GROUP = '" .$group_counter. "'";
                                $result_menu = @mysql_query($sql);
                                if(!$result_menu) 
                                {
                                    $this->error_message = "ERROR: ORERER MAKEUP UPDATE ERROR ->" . $sql;
                                    return false;
                                }
                                
                            }
                            
                        }
                        
                        $group_counter++;
                    }
                    
                    //가용 재료 소모
                    
                    $menu_names = array();
                   
                    $sql = "SELECT IDX_MENU,MENU_NUM FROM ORDER_MAKEUP WHERE IDX_ORDER = '" .$_key ."'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    $numResults = mysql_num_rows($result);
                    for($i=0;$i<$numResults;$i++)
                    {
                        $record = mysql_fetch_array($result);
                        $menu_num = $record['MENU_NUM'];
                        for($k=0;$k<$menu_num;$k++)
                        {
                            array_push($menu_names,$record['IDX_MENU']);
                        }
                    }
                    
                    
                    $sql = "SET SQL_SAFE_UPDATES=0";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    $sql = "UPDATE INGREDIENTS AS I , 
                            (
                            SELECT IDX_INGR, SUM(INGR_NEED_GRAM) AS ING_SUM
                            FROM (";
                    
                    for($i=0;$i < count($menu_names); $i++)
                    {
                        $sql_subs[$i] = "SELECT IDX_INGR, INGR_NEED_GRAM 
                                FROM MENU AS ME, MENU_MAKEUP AS MUP
                                WHERE ME.IDX_MENU = MUP.IDX_MENU AND
                                TRIM(ME.IDX_MENU) = TRIM('" .$menu_names[$i]. "') ";
                    }
                    
                    $sql_subs = implode(' UNION ALL ',$sql_subs);
                    
                    $sql .= $sql_subs. " ) AS UNI GROUP BY UNI.IDX_INGR
                        ) AS INGSUM 
                        SET I.ABLE_STOCK_GRAM = I.ABLE_STOCK_GRAM - INGSUM.ING_SUM WHERE I.IDX_INGR = INGSUM.IDX_INGR";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    $sql = "SET SQL_SAFE_UPDATES=1";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    return true;
                
                case OPER_TYPE_MOD :
                    
                    
                    $sql = "SELECT IDX_ORDER FROM ORDER_HISTORY WHERE IDX_ORDER = '" .$_key. "'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0)
                    {
                        $this->error_message = "ERROR: non order idx";
                        return false;
                    }
                    
                    //꼭 바꾸기
                    $row_list = array("ID","NAME","PHONE","ADDRESS","IS_JOIN","IS_DISCOUNT","TOTAL_PRICE","CARDNUM","ORDER_REQUEST_TIME","COOKING_START_TIME","COOKING_END_TIME","DELIVERY_START_TIME","DELIVERY_END_TIME");
                    $val_list = array(($_is_registered == true) ? $_userinfo->GetEmail() : "",$_userinfo->GetName(),$_userinfo->GetPhoneNumber(),$_userinfo->GetAddress(),($_is_registered == true) ? "T" : "",($_is_discounted == true) ? "T" : "",$_total_cost,$_userinfo->GetCreditcardNum(),$_req_time,$_cooking_start_time,$_cooking_end_time,$_delivery_start_time,$_delivery_end_time);
                    
                    if($this->query_update("ORDER_HISTORY",$val_list,$row_list,"IDX_ORDER = '".$_key."'")==false)
                    {
                        $this->error_message = "ERROR: ORDERINFO update error";
                        return false;
                    }
                    
                    
                    
                    
                    //주문시 메뉴 구성이 바뀌는 경우는 상정X
                    /*
                    $sql = "DELETE FROM ORDER_MAKEUP WHERE IDX_ORDER = '" .$_key. "'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $group_counter = 0;
                    $row_list_makeup = array("IDX_ORDER","IDX_COURSE","IDX_MENU","ORDER_GROUP","COURSE_STYLE","COURSE_NAME","MENU_NAME","MENU_SUB_TYPE","MENU_NUM");
                    
                    foreach ($_ccdlist as $ccd)
                    {
                        $menu_list = $ccd->GetMenuNameList();
                        $_course_style_code = $this->styleTOstr($ccd->GetStyle());
                        $_course_name = $ccd->GetOriginCourseName();
                        $_course_key = $ccd->GetDBkey();
                        foreach ($menu_list as $_menu_key)
                        {
                            
                            $_menu_name = $this->KeyToValue("MENU",$_menu_key,"IDX_MENU","MENU_NAME");
                            if($_menu_name == false) continue;
                            //메뉴 주메뉴 부메뉴는 알수가 없음....
                            $sql = "SELECT IDX_ORDER,IDX_MENU,IDX_COURSE,ORDER_GROUP FROM ORDER_MAKEUP 
                                        WHERE IDX_ORDER = '" .$_key. "' AND IDX_MENU = '".$_menu_key. "' AND 
                                        IDX_COURSE = '".$_course_key. "' AND ORDER_GROUP = '" .$group_counter. "'";
                            $result_menu = @mysql_query($sql);
                            $numResults_menu = mysql_num_rows($result_menu);
                            if($numResults_menu == 0)
                            {
                                //추가
                                $val_list_menu = array($_key,$_course_key,$_menu_key,$group_counter,$_course_style_code,$_course_name,$_menu_name,"",1);
                                if($this->query_insert("ORDER_MAKEUP",$val_list_menu,$row_list_makeup)==false)
                                {
                                    return false;
                                }
                                
                            }
                            else if($numResults_menu == 1)
                            {
                                //값 +1
                                $sql = "UPDATE ORDER_MAKEUP SET MENU_NUM = MENU_NUM+1 
                                        WHERE IDX_ORDER = '" .$_key. "' AND IDX_MENU = '".$_menu_key. "' AND 
                                        IDX_COURSE = '".$_course_key. "' AND ORDER_GROUP = '" .$group_counter. "'";
                                $result_menu = @mysql_query($sql);
                                if(!$result_menu)
                                {
                                    $this->error_message = "ERROR: ORERER MAKEUP UPDATE ERROR";
                                    return false;
                                }
                                
                            }
                            
                        }
                        
                        $group_counter++;
                    }
                    */
                    
                    
                    //재료 소모
                    if(!is_null($_cooking_start_time) && is_null($_cooking_end_time) && is_null($_delivery_start_time) && is_null($_delivery_end_time))
                    {
                        
                        $menu_names = array();
                        
                        $sql = "SELECT IDX_MENU,MENU_NUM FROM ORDER_MAKEUP WHERE IDX_ORDER = '" .$_key ."'";
                        $result = @mysql_query($sql);
                        if(!$result)
                        {
                            $this->error_message = "ERROR: query error, SQL : " .$sql;
                            return false;
                        }
                        
                        $numResults = mysql_num_rows($result);
                        for($i=0;$i<$numResults;$i++)
                        {
                            $record = mysql_fetch_array($result);
                            $menu_num = $record['MENU_NUM'];
                            for($k=0;$k<$menu_num;$k++)
                            {
                                array_push($menu_names,$record['IDX_MENU']);
                            }
                        }
                        
                        
                        $sql = "SET SQL_SAFE_UPDATES=0";
                        $result = @mysql_query($sql);
                        
                        $sql = "UPDATE INGREDIENTS AS I , 
                            (
                            SELECT IDX_INGR, SUM(INGR_NEED_GRAM) AS ING_SUM
                            FROM (";
                        
                        for($i=0;$i < count($menu_names); $i++)
                        {
                            $sql_subs[$i] = "SELECT IDX_INGR, INGR_NEED_GRAM 
                                FROM MENU AS ME, MENU_MAKEUP AS MUP
                                WHERE ME.IDX_MENU = MUP.IDX_MENU AND
                                TRIM(ME.IDX_MENU) = TRIM('" .$menu_names[$i]. "') ";
                        }
                        
                        $sql_subs = implode(' UNION ALL ',$sql_subs);
                        
                        $sql .= $sql_subs. " ) AS UNI GROUP BY UNI.IDX_INGR
                        ) AS INGSUM 
                        SET I.STOCK_GRAM = I.STOCK_GRAM - INGSUM.ING_SUM WHERE I.IDX_INGR = INGSUM.IDX_INGR";
                        $result = @mysql_query($sql);
                        if(!$result)
                        {
                            $this->error_message = "ERROR: query error, SQL : " .$sql;
                            return false;
                        }
                        
                        $sql = "SET SQL_SAFE_UPDATES=1";
                        $result = @mysql_query($sql);
                    }
                    
                    
                    return true;
                
                
                case OPER_TYPE_DEL :
                    
                    $sql = "SELECT IDX_ORDER, CANCELED, COOKING_START_TIME FROM ORDER_HISTORY WHERE IDX_ORDER = '" .$_key. "'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0)
                    {
                        $this->error_message = "ERROR: non order idx error";
                        return false;
                    }
                    
                    $sql = "UPDATE ORDER_HISTORY SET CANCELED = 'T' WHERE IDX_ORDER = '" .$_key. "'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    //가용 재료 회복
                    
                    $menu_names = array();
                    
                    $sql = "SELECT IDX_MENU,MENU_NUM FROM ORDER_MAKEUP WHERE IDX_ORDER = '" .$_key ."'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    $numResults = mysql_num_rows($result);
                    for($i=0;$i<$numResults;$i++)
                    {
                        $record = mysql_fetch_array($result);
                        $menu_num = $record['MENU_NUM'];
                        for($k=0;$k<$menu_num;$k++)
                        {
                            array_push($menu_names,$record['IDX_MENU']);
                        }
                    }
                    
                    
                    $sql = "SET SQL_SAFE_UPDATES=0";
                    $result = @mysql_query($sql);
                    
                    $sql = "UPDATE INGREDIENTS AS I , 
                            (
                            SELECT IDX_INGR, SUM(INGR_NEED_GRAM) AS ING_SUM
                            FROM (";
                    
                    for($i=0;$i < count($menu_names); $i++)
                    {
                        $sql_subs[$i] = "SELECT IDX_INGR, INGR_NEED_GRAM 
                                FROM MENU AS ME, MENU_MAKEUP AS MUP
                                WHERE ME.IDX_MENU = MUP.IDX_MENU AND
                                TRIM(ME.IDX_MENU) = TRIM('" .$menu_names[$i]. "') ";
                    }
                    
                    $sql_subs = implode(' UNION ALL ',$sql_subs);
                    
                    $sql .= $sql_subs. " ) AS UNI GROUP BY UNI.IDX_INGR
                        ) AS INGSUM 
                        SET I.ABLE_STOCK_GRAM = I.ABLE_STOCK_GRAM + INGSUM.ING_SUM WHERE I.IDX_INGR = INGSUM.IDX_INGR";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    $sql = "SET SQL_SAFE_UPDATES=1";
                    $result = @mysql_query($sql);
                    
                    return true;
            }
        }
        else
        {
            $this->error_message = "ERROR: type error";
            return false;            
        }
    }
    
    
    function SetDiscountPolicy($policy)
    {
        //change or modify -> insert(record concept)
        if ($policy instanceof DiscountPolicy)
        {
            $discount_gap = $policy->GetTargetOrderCount();
            $discount_rate = $policy->GetDiscountPercent();
            $sql = "INSERT INTO DISCOUNT_POLICY VALUES (NULL,NOW(),'" . $discount_rate . "','" . $discount_gap . "')";
            $result = @mysql_query($sql);
            if(!$result)
            {
                $this->error_message = "ERROR: query error, SQL : " .$sql;
                return false;
            }
            return true;
        }
        else
        {
            $this->error_message = "ERROR: type error";
            return false;
        }
    }
    
    
    //modify one item?
    function SetCourseDish($course,$oper)
    {
        if ($course instanceof CourseDish)
        {
            $_key = $course->GetDBkey();
            $_name = $course->GetName();
            $_main_menu_list = $course->GetMainMenuList();
            $_sub_menu_list = $course->GetSubMenuList();
            $_detail = $course->GetDescription();
            $_style_list = $course->GetStyleList();
            $_str_style = $this->stylesTOstr($_style_list);
            
            switch($oper)
            {
                case OPER_TYPE_ADD :
                    // non key state
                    $sql = "SELECT IDX_COURSE, COURSE_NAME FROM COURSE WHERE TRIM(COURSE_NAME) = TRIM('" .$_name. "') AND TRIM(DEL) IS NULL;";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults>0)
                    {
                        $this->error_message = "ERROR: course name duplicated";
                        return false;
                    }
                    $sql = "INSERT INTO COURSE VALUES (NULL,'" .$_name. "','기본','".$_str_style."','" .$_detail. "',NULL,NULL);";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    //get key (select last id -> warning?)
                    //change last_key 얻음
                    
                    /*
                    $sql = "SELECT IDX_COURSE, COURSE_NAME FROM COURSE WHERE TRIM(COURSE_NAME) = TRIM('" .$_name. "');";
                    $result = @mysql_query($sql);
                    $numResults = mysql_num_rows($result);
                    if($numResults==0) return false;
                    $record = mysql_fetch_array($result);
                    $_key = $record['IDX_COURSE'];
                     */
                    
                    // last_insert_id -> replace
                    $result = @mysql_query("SELECT LAST_INSERT_ID() AS ID");
                    $record = mysql_fetch_array($result);
                    $_key =  $record['ID'];
                    
                    
                    //course_makeup 
                    foreach ($_main_menu_list as $menu)
                    {
                        
                        $_menu_key = $menu->GetDBkey();
                        
                        $sql = "SELECT IDX_COURSE, IDX_MENU, MENU_NUM, MENU_SUB_TYPE FROM COURSE_MAKEUP WHERE IDX_COURSE = '" .$_key. "' AND IDX_MENU = '" .$_menu_key . "'";
                        $result = @mysql_query($sql);
                        if(!$result)
                        {
                            $this->error_message = "ERROR: query error, SQL : " .$sql;
                            return false;
                        }
                        $numResults = mysql_num_rows($result);
                        if($numResults==0)
                        {
                            //insert
                            $sql = "INSERT INTO COURSE_MAKEUP VALUES ('" .$_key. "','" .$_menu_key. "',1,'main',NOW());";
                            $result = @mysql_query($sql);
                            if(!$result)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql;
                                return false;
                            }
                            
                        }else if($numResults==1)
                        {
                            $sql = "UPDATE COURSE_MAKEUP SET MENU_NUM = MENU_NUM+1 WHERE IDX_COURSE = '" .$_key. "' AND IDX_MENU = '" .$_menu_key. "'";
                            $result = @mysql_query($sql);
                            if(!$result)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql;
                                return false;
                            }
                            //menu_num + 1
                            
                        }
                        
                    }
                    
                    foreach ($_sub_menu_list as $menu)
                    {
                        $_menu_key = $menu->GetDBkey();
                        
                        $sql = "SELECT IDX_COURSE, IDX_MENU, MENU_NUM, MENU_SUB_TYPE FROM COURSE_MAKEUP WHERE IDX_COURSE = '" .$_key. "' AND IDX_MENU = '" .$_menu_key . "'";
                        $result = @mysql_query($sql);
                        if(!$result)
                        {
                            $this->error_message = "ERROR: query error, SQL : " .$sql;
                            return false;
                        }
                        $numResults = mysql_num_rows($result);
                        if($numResults==0)
                        {
                            //insert
                            $sql = "INSERT INTO COURSE_MAKEUP VALUES ('" .$_key. "','" .$_menu_key. "',1,'sub',NOW());";
                            $result = @mysql_query($sql);
                            if(!$result)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql;
                                return false;
                            }
                            
                        }else if($numResults==1)
                        {
                            $sql = "UPDATE COURSE_MAKEUP SET MENU_NUM = MENU_NUM+1 WHERE IDX_COURSE = '" .$_key. "' AND IDX_MENU = '" .$_menu_key. "'";
                            $result = @mysql_query($sql);
                            if(!$result)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql;
                                return false;
                            }
                            //menu_num + 1
                            
                        }
                    }
                    return true;
                
                case OPER_TYPE_MOD :
                    
                    $sql = "SELECT IDX_COURSE, COURSE_NAME FROM COURSE WHERE TRIM(IDX_COURSE) = TRIM('" .$_key. "');";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0)
                    {
                        $this->error_message = "ERROR: non course idx";
                        return false;
                    }
                    $sql = "UPDATE COURSE SET COURSE_NAME = '" .$_name. "', COURSE_DETAIL = '" .$_detail. "', COURSE_STYLE = '".$_str_style."' WHERE IDX_COURSE  = '" .$_key. "'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    // all delete
                    $sql = "DELETE FROM COURSE_MAKEUP WHERE IDX_COURSE = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    //course_makeup 
                    foreach ($_main_menu_list as $menu)
                    {
                        
                        $_menu_key = $menu->GetDBkey();
                        
                        $sql = "SELECT IDX_COURSE, IDX_MENU, MENU_NUM, MENU_SUB_TYPE FROM COURSE_MAKEUP WHERE IDX_COURSE = '" .$_key. "' AND IDX_MENU = '" .$_menu_key . "'";
                        $result = @mysql_query($sql);
                        if(!$result)
                        {
                            $this->error_message = "ERROR: query error, SQL : " .$sql;
                            return false;
                        }
                        $numResults = mysql_num_rows($result);
                        if($numResults==0)
                        {
                            //insert
                            $sql = "INSERT INTO COURSE_MAKEUP VALUES ('" .$_key. "','" .$_menu_key. "',1,'main',NOW());";
                            $result = @mysql_query($sql);
                            if(!$result)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql;
                                return false;
                            }
                            
                        }else if($numResults==1)
                        {
                            $sql = "UPDATE COURSE_MAKEUP SET MENU_NUM = MENU_NUM+1 WHERE IDX_COURSE = '" .$_key. "' AND IDX_MENU = '" .$_menu_key. "'";
                            $result = @mysql_query($sql);
                            if(!$result)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql;
                                return false;
                            }
                            //menu_num + 1
                            
                        }
                        
                    }
                    
                    foreach ($_sub_menu_list as $menu)
                    {
                        $_menu_key = $menu->GetDBkey();
                        
                        $sql = "SELECT IDX_COURSE, IDX_MENU, MENU_NUM, MENU_SUB_TYPE FROM COURSE_MAKEUP WHERE IDX_COURSE = '" .$_key. "' AND IDX_MENU = '" .$_menu_key . "'";
                        $result = @mysql_query($sql);
                        if(!$result)
                        {
                            $this->error_message = "ERROR: query error, SQL : " .$sql;
                            return false;
                        }
                        $numResults = mysql_num_rows($result);
                        if($numResults==0)
                        {
                            //insert
                            $sql = "INSERT INTO COURSE_MAKEUP VALUES ('" .$_key. "','" .$_menu_key. "',1,'sub',NOW());";
                            $result = @mysql_query($sql);
                            if(!$result)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql;
                                return false;
                            }
                            
                        }else if($numResults==1)
                        {
                            $sql = "UPDATE COURSE_MAKEUP SET MENU_NUM = MENU_NUM+1 WHERE IDX_COURSE = '" .$_key. "' AND IDX_MENU = '" .$_menu_key. "'";
                            $result = @mysql_query($sql);
                            if(!$result)
                            {
                                $this->error_message = "ERROR: query error, SQL : " .$sql;
                                return false;
                            }
                            //menu_num + 1
                            
                        }
                    }
                    return true;
                
                case OPER_TYPE_DEL :
                    
                    $sql = "SELECT IDX_COURSE, COURSE_NAME FROM COURSE WHERE TRIM(IDX_COURSE) = TRIM('" .$_key. "');";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0) 
                    {
                        $this->error_message = "ERROR: non course idx";
                        return false;
                    }
                    $sql = "UPDATE COURSE SET DEL = 'T' WHERE IDX_COURSE  = '" .$_key. "'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    return true;
            }
        }
        else
        {
            $this->error_message = "ERROR: CourseDish type error";
            return false;
        }
    }
    
    //modify one item?
    //Menu 객체면 opeation보고 해당 operation 실행 TF 리턴, 객체 아니면 F 리턴
    function SetMenu($menu,$oper)
    {
        if ($menu instanceof Menu)
        {
            
            $_key = $menu->GetDBkey();
            $_name = $menu->GetName();
            $_detail = $menu->GetDescription();
            $_cost = $menu->GetCost();
            $_ingr_list = $menu->GetIngredientList();
            
            
            switch($oper)
            {
                case OPER_TYPE_ADD :
                    // non key state
                    $sql = "SELECT IDX_MENU, MENU_NAME FROM MENU WHERE TRIM(MENU_NAME) = TRIM('" .$_name. "') AND TRIM(DEL) IS NULL";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults > 0) 
                    {
                        $this->error_message = "ERROR: menu name duplicated";
                        return false;
                    }
                    $sql = "INSERT INTO MENU VALUES (NULL,'" .$_name. "','기본','" .$_detail. "',NOW(),'" .$_cost. "',NULL);";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    //get key
                    
                    /*
                    $sql = "SELECT IDX_MENU, MENU_NAME FROM MENU WHERE TRIM(MENU_NAME) = TRIM('" .$_name. "') AND TRIM(DEL) IS NULL;";
                    $result = @mysql_query($sql);
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0) return false;
                    $record = mysql_fetch_array($result);
                    $_key = $record['IDX_MENU'];
                    */
                    
                    // last_insert_id -> replace
                    $result = @mysql_query("SELECT LAST_INSERT_ID() AS ID");
                    $record = mysql_fetch_array($result);
                    $_key =  $record['ID'];
                    
                    
                    //ingr_makeup 
                    foreach ($_ingr_list as $ing)
                    {
                        //need ingr key valid check?
                        $sql = "INSERT INTO MENU_MAKEUP VALUES ('" .$_key. "','" .$ing->GetDBkey(). "','" .$ing->GetVal(). "',NOW());";
                        $result = @mysql_query($sql);
                        if(!$result)
                        {
                            $this->error_message = "ERROR: query error, SQL : " .$sql;
                            return false;
                        }
                        
                    }
                    
                    return true;
                
                case OPER_TYPE_MOD :
                    // validation check
                    $sql = "SELECT IDX_MENU, MENU_NAME 
                                FROM MENU WHERE IDX_MENU = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0) 
                    {
                        $this->error_message = "ERROR: non menu idx";
                        return false;
                    }
                    // update
                    $sql = "UPDATE MENU SET MENU_NAME = '" .$_name. "', MENU_DETAIL = '" .$_detail. "', PRICE = '" . $_cost . "' WHERE IDX_MENU = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    //ingr init
                    $sql = "DELETE FROM MENU_MAKEUP WHERE IDX_MENU = '" .$_key. "'";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    //ingr_makeup 
                    foreach ($_ingr_list as $ing)
                    {
                        //need ingr key valid check?
                        $sql = "INSERT INTO MENU_MAKEUP VALUES ('" .$_key. "','" .$ing->GetDBkey(). "','" .$ing->GetVal(). "',NOW());";
                        $result = @mysql_query($sql);
                        if(!$result)
                        {
                            $this->error_message = "ERROR: query error, SQL : " .$sql;
                            return false;
                        }
                    }
                    break;
                
                case OPER_TYPE_DEL :
                    // val -> 0 setting
                    $sql = "SELECT IDX_MENU, MENU_NAME 
                                FROM MENU WHERE IDX_MENU = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0) 
                    {
                        $this->error_message = "ERROR: non menu idx";
                        return false;
                    }
                    // menu delete
                    $sql = "UPDATE MENU SET DEL = 'T'  
                                WHERE IDX_MENU = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    // menu_makeup delete
                    /*
                    $sql = "DELETE FROM MENU_MAKEUP 
                    WHERE IDX_MENU = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if($result) return true; else false;
                    
                    // course_makeup delete -> how about?
                    $sql = "DELETE FROM COURSE_MAKEUP 
                    WHERE IDX_MENU = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if($result) return true; else false;
                     */
                    //관련 코스 메뉴 다 지움
                    $sql = "UPDATE COURSE SET DEL = 'T' WHERE IDX_COURSE IN (SELECT IDX_COURSE FROM COURSE_MAKEUP WHERE IDX_MENU = '" .$_key. "')";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    
                    return true;
                
            }    
            
        }
        else
        {
            $this->error_message = "ERROR: menu type error";
            return false;
        }
    }
    //modify one item?
    function SetIngredient($ingredient,$oper)
    {
        if ($ingredient instanceof Ingredient)
        {
            
            $_key = $ingredient->GetDBkey();
            $_name = $ingredient->GetName();
            $_val = $ingredient->GetVal();
            
            switch($oper)
            {
                case OPER_TYPE_ADD :
                    // non key state
                    $sql = "SELECT IDX_INGR, INGR_NAME FROM INGREDIENTS WHERE TRIM(INGR_NAME) = TRIM('" .$_name. "');";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults > 0) 
                    {
                        $this->error_message = "ERROR: ingredient name duplicated";
                        return false;
                    }
                    $sql = "INSERT INTO INGREDIENTS VALUES (NULL,'" .$_name. "','" .$_val. "','" .$_val. "',NULL,NULL,NOW());";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    return true;
                
                case OPER_TYPE_MOD :
                    // validation check
                    $sql = "SELECT * FROM (SELECT IDX_INGR,STOCK_GRAM + " .$_val. " AS S_VAL, ABLE_STOCK_GRAM + ".$_val. " AS A_VAL 
                                FROM INGREDIENTS) AS STOCK WHERE STOCK.IDX_INGR = '" .$_key. "' AND STOCK.S_VAL >= 0 AND STOCK.A_VAL >= 0 ;";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0) 
                    {
                        $this->error_message = "ERROR: ingredient value is minus";
                        return false;
                    }// update
                    $sql = "UPDATE INGREDIENTS SET STOCK_GRAM = STOCK_GRAM + " .$_val. ", ABLE_STOCK_GRAM = ABLE_STOCK_GRAM + " .$_val. 
                        " WHERE IDX_INGR = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    return true;
                    
                case OPER_TYPE_DEL :
                    // val -> 0 setting
                    $sql = "SELECT IDX_INGR 
                                FROM INGREDIENTS WHERE IDX_INGR = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    $numResults = mysql_num_rows($result);
                    if($numResults == 0) 
                    {
                        $this->error_message = "ERROR: non ingredient idx";
                        return false;
                    }
                    $sql = "UPDATE INGREDIENTS SET STOCK_GRAM = 0, ABLE_STOCK_GRAM = 0 
                                WHERE IDX_INGR = '" .$_key. "';";
                    $result = @mysql_query($sql);
                    if(!$result)
                    {
                        $this->error_message = "ERROR: query error, SQL : " .$sql;
                        return false;
                    }
                    return true;
            }    
            
        }
        else
        {
            $this->error_message = "ERROR: ingredients type error";
            return false;
        }
    }
    
    
    private function query_select($table, $rows = '*', $where = null, $order = null)
    {
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $q .= ' WHERE '.$where;
        if($order != null)
            $q .= ' ORDER BY '.$order;
        if($this->tableExists($table))
        {
            $query = @mysql_query($q);
            if($query)
            {
                $this->numResults = mysql_num_rows($query);
                for($i = 0; $i < $this->numResults; $i++)
                {
                    $r = mysql_fetch_array($query);
                    $key = array_keys($r); 
                    for($x = 0; $x < count($key); $x++)
                    {
                        // Sanitizes keys so only alphavalues are allowed
                        if(!is_int($key[$x]))
                        {
                            if(mysql_num_rows($query) > 1)
                                $this->result[$i][$key[$x]] = $r[$key[$x]];
                            else if(mysql_num_rows($query) < 1)
                                $this->result = null; 
                            else
                                $this->result[$key[$x]] = $r[$key[$x]]; 
                        }
                    }
                }            
                return true; 
            }
            else
            {
                return false; 
            }
        }
        else
        {
            return false;   
        }
    }
    
    
    private function query_debug_print($query,$result)
    {
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            echo $message;
        }
    }
    
    
    private function query_delete($table,$where = null)
    {
        if($this->tableExists($table))
        {
            if($where == null)
            {
                $delete = 'DELETE '.$table; 
            }
            else
            {
                $delete = 'DELETE FROM '.$table.' WHERE '.$where; 
            }
            $del = @mysql_query($delete);
            
            if($del)
            {
                return true; 
            }
            else
            {
                $this->error_message = "ERROR: DELETE ERROR -> ".$delete;
                return false; 
            }
        }
        else
        {
            return false; 
        }
    }
    
    
    private function query_insert($table,$values,$cols = null)
    {
        if($this->tableExists($table))
        {
            $insert = 'INSERT INTO '.$table;
            if($cols != null)
            {
                $cols = implode(',',$cols);
                $insert .= ' ('.$cols.')'; 
            }
            
            
            for($i = 0; $i < count($values); $i++)
            {
                if(is_null($values[$i]))
                {
                    $values[$i] = "NULL";
                }
                else
                {
                    $values[$i] = "'".$values[$i]."'";
                }
            }
            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';
            $ins = @mysql_query($insert);            
            if($ins)
            {
                return true; 
            }
            else
            {
                
                $this->error_message = "ERROR: INSERT ERROR -> ".$insert;
                return false; 
            }
        }
    }
    
    
    private function query_update($table,$values,$cols,$where)
    {
        if($this->tableExists($table))
        {
            // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row
            /*
            for($i = 0; $i < count($where); $i++)
            {
            if($i%2 != 0)
            {
            if(is_string($where[$i]))
            {
            if(($i+1) != null)
            $where[$i] = '"'.$where[$i].'" AND ';
            else
            $where[$i] = '"'.$where[$i].'"';
            }
            }
            }
            $where = implode('=',$where);
             */
            
            $update = 'UPDATE '.$table.' SET ';
            
            $temp = array();
            
            for($i = 0; $i < count($cols); $i++)
            {
                if(is_null($values[$i]))
                {
                    $temp[$i] = $cols[$i]. " = NULL";
                }
                else
                {
                    $temp[$i] = $cols[$i]. " = '" .$values[$i] . "'";
                }
                
            }
            $temp = implode(' , ',$temp);
            $update .= $temp;
            $update .= " WHERE ".$where;
            $query = @mysql_query($update);
            if($query)
            {
                return true; 
            }
            else
            {
                
                $this->error_message = "ERROR: UPDATE ERROR ->" .$update;
                return false; 
            }
        }
        else
        {
            return false; 
        }
    }
    
    private function stylesTOstr($_style_list)
    {
        $str = "   ";
        foreach ($_style_list as $value)
        {
            switch ($value)
            {
                case MENU_TYPE_SIMPLE :
                    $str = substr_replace($str,"T",0,1);
                    break;
                
                case MENU_TYPE_DELUXE :
                    $str = substr_replace($str,"T",1,1);
                    break;
                
                case MENU_TYPE_GRAND :
                    $str = substr_replace($str,"T",2,1);
                    break;
            }
            
        }
        return $str; 
    }
    
    
    
    private function strTOstyle($_str)
    {
        switch (trim($_str))
        {
            case DB_MENU_TYPE_SIMPLE :
                return MENU_TYPE_SIMPLE;
            case DB_MENU_TYPE_DELUXE :
                return MENU_TYPE_DELUXE;
            case DB_MENU_TYPE_GRAND :
                return MENU_TYPE_GRAND;
        }   
    }
    private function styleTOstr($_style)
    {
        switch ($_style)
        {
            case MENU_TYPE_SIMPLE :
                return DB_MENU_TYPE_SIMPLE;
            case MENU_TYPE_DELUXE :
                return DB_MENU_TYPE_DELUXE;
            case MENU_TYPE_GRAND :
                return DB_MENU_TYPE_GRAND;
        }
    }
    
    private function keyToValue($table,$key,$col_in,$col_out)
    {
        $sql = "SELECT " .$col_out." FROM " .$table. " WHERE " .$col_in. " = '" .$key. "'";
        $result = @mysql_query($sql);
        $numResults = mysql_num_rows($result);
        if($numResults == 0) return false;
        $record = mysql_fetch_row($result);
        return $record[0];
    }
    
    private function truncateAlltables()
    {
        $result_tbl = mysql_query( "SHOW TABLES FROM ".$this->db_name, $this->_db_connect ); 

        $tables = array(); 
        while ($row = mysql_fetch_row($result_tbl)) { 
            $result = mysql_query("TRUNCATE TABLE ".$row[0],$this->_db_connect);
            if(!$result) 
            {
                $this->error_message = "ERROR: truncate table error" .$row[0];
                return false;
            }
        }
    }
    
   
    private function dropAlltables()
    {
        $result_tbl = mysql_query( "SHOW TABLES FROM ".$this->db_name, $this->_db_connect ); 

        $tables = array(); 
        while ($row = mysql_fetch_row($result_tbl)) { 
            $result = mysql_query("DROP TABLE ".$row[0],$this->_db_connect); 
            if(!$result)
            {
                $this->error_message = "ERROR: drop table error" .$row[0];
                return false;
            }
        }
    }
    
    
}
?>