<?php

namespace Wattanar;

class Sqlsrv
{
	public static function connect($server, $username, $password, $database)
	{
		$settings = [
			"Database" => "$database", 
			"UID" => "$username", 
			"PWD" => "$password" ,
			"CharacterSet" => "UTF-8",
			"ReturnDatesAsStrings" => true,
			"MultipleActiveResultSets" => true,
			"ConnectionPooling" => true
		];

		$conn = sqlsrv_connect($server, $settings);

		if ( false === $conn) {
			throw new \Exception("Unable to connect database.", 1);
		}
		return $conn;
	}

	public static function rows($connection, $query, array $params = null)
	{
		if ($params === null) {
			$query = sqlsrv_query($connection, $query);			
		} else {
			$query = sqlsrv_query($connection, $query, $params);
		}

		$rows = [];

		while ($nextRow = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $nextRow;
		}
		
		return $rows;
	}

	public static function hasRows($connection, $query, array $params = null)
	{
		if ($params === null) {
			$query = sqlsrv_has_rows(sqlsrv_query(
				$connection,
				$query
			));
		} else {
			$query = sqlsrv_has_rows(sqlsrv_query(
				$connection,
				$query,
				$params
			));
		}
		return $query;
	}

	public static function query($connection, $query, array $params = null)
	{
		if ($params === null) {
			$query = sqlsrv_query($connection, $query);
		} else {
			$query = sqlsrv_query($connection, $query, $params);
		}
		return $query;
	}
}
