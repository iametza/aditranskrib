<?php

class DBO
{
	var $server;
	var $username;
	var $password;
	var $database;
	
	var $konexioa;
	var $emaitza;
	var $query;
	
	function DBO($server, $username, $password, $database)
	{
		$this->server=$server;
		$this->username=$username;
		$this->password=$password;
		$this->database=$database;
		
		if ($konexioa=mysql_connect($server, $username, $password))
			if (mysql_select_db($database, $konexioa))
			{
				// Hau gabe testu-kateak iso-8859 balira bezala irakurtzen ditu eta ?ak agertzen dira ñ-en ordez.
				mysql_set_charset("utf8");
				
				$this->konexioa=$konexioa;
				return true;
			}
			else
			{
				echo "Ez da $database datubasea aurkitu.";
				return false;
			}
		else
		{
			echo "Ezin izan da datubase zerbitzariarekin konektatu.";
			return false;
		}
	}
	
	function query($sql)
	{
		
		if($this->query=mysql_query($sql, $this->konexioa))
			return true;
		else 
			return false;
	}
	
	function emaitza()
	{
		$this->emaitza=mysql_fetch_array($this->query);	
		return $this->emaitza;
	}
	
	function itxi()
	{
		return mysql_close($this->konexioa);
	}
		
	function lehenengoa()
	{
		$this->emaitza=mysql_fetch_array($this->query);
		return $this->emaitza[0];
	}
	
	function ShowError()
	{
  die("Error " . mysql_errno() . " : " . mysql_error());
	}

	function emaitza_kopurua()
	{
		return mysql_num_rows($this->query);
	}

	function hasierara()
	{
		if (mysql_field_seek ($this->query, 0))
			return true;
		else
			return false;
	}


}

?>
