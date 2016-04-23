<?php
    class DBtest 
    {
        
        private $con = false;
        //private $db_host = '127.0.0.1';
        private $db_host = '127.0.0.1:9090';
        private $db_user = 'dbclient';
        private $db_pass = 'qwerty';
        private $db_name = 'test';
        
        private $_db_connect;
        public function diplay_all_tables()
        {
            // database constants
            // make sure the information is correct

            $this->_db_connect = mysql_connect($this->db_host, $this->db_user, $this->db_pass) 
               or die("Unable to connect to MySQL"); 

            
            $selected = mysql_select_db($this->db_name, $this->_db_connect) 
               or die("Could not select examples");
            
            mysql_set_charset("utf8",$this->_db_connect);
            mysql_query("set names utf8",$this->_db_connect);
            
            mysql_query("set session character_set_connection=utf8;",$this->_db_connect);
            mysql_query("set session character_set_results=utf8;",$this->_db_connect);
            mysql_query("set session character_set_client=utf8;",$this->_db_connect);

            $result_tbl = mysql_query( "SHOW TABLES FROM ".$this->db_name, $this->_db_connect ); 

            $tables = array(); 
            while ($row = mysql_fetch_row($result_tbl)) { 
                $tables[] = $row[0]; 
            } 

            /*
            $output = "<?xml version=\"1.0\" ?>\n"; 
            $output .= "<schema>"; 
             */
            $output = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
            $output .= "<title>View All Tables &nbsp</title></head>";
            $output .= "<body><br>";
            

            foreach ( $tables as $table ) { 
                //$output .= "<table name=\"$table\">";
                $result_fld = mysql_query( "SHOW FIELDS FROM ".$table, $this->_db_connect ); 
                $num_fld = mysql_num_rows($result_fld);
                $width= 20*$num_fld;
                $output .= "<br><table width='.$width.' border=4><tr>";
                $output .= "<tr><th colspan='.$num_fld.'>Table : &nbsp $table </th></tr>";
                while( $row1 = mysql_fetch_row($result_fld) ) {
                    //$output .= "<field name=\"$row1[0]\" type=\"$row1[1]\"";
                    $output .= "<th><font size=2><b>".$row1[0]. "  type=".$row1[1]. " ";
                    $output .= ($row1[3] == "PRI") ? " (PK) " : "";
                    $output .= "</font></b></th>";
                }
                $output .= "</tr>";
                $result_row = mysql_query( "SELECT * FROM ".$table, $this->_db_connect);
                
                while( $row2 = mysql_fetch_row($result_row))
                {
                    $output .="<tr>";
                    for($i=0;$i<$num_fld;$i++)
                    {
                        $output .= "<td align=left>".$row2[$i]."</td>";
                    }
                    $output .="</tr>";
                }

                $output .= "</table><br>"; 
            } 

            $output .= "</html>"; 

            // tell the browser what kind of file is come in
            //header("Content-type: text/xml"); 
            // print out XML that describes the schema
            echo $output; 

            // close the connection 
            mysql_close($_db_connect); 
        }
    }
    header( 'Content-Type: text/html; charset=utf-8' );
    $aa = new DBtest();
    $aa->diplay_all_tables();
   
?>