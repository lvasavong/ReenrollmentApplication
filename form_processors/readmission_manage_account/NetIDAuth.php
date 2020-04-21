<?php
//**********************************************************************************************************
//*
//*   	Class: NetIDAuth - Authentication for NetID
//*		Author: Jeffery A. White, Univeristy of Rochester Registrar's Office
//* 	Creation Date: 07/17/2017
//*		Last Modified: 07/17/2017 JAW
//*
//**********************************************************************************************************

class NetIDAuth
{
	private $m_server = NULL;
	private $m_service_account = NULL;
	
	public function __construct($account = NULL)
	{
		$this->m_service_account = $account;
		
		/*
		if($_SERVER['SERVER_NAME'] == 'secure1.wdev.rochester.edu')
			$this->m_server = 'ldaps://odsee-test.its.rochester.edu';
		else
			$this->m_server = 'ldaps://odsee.its.rochester.edu';
		*/
		$this->m_server = 'ldaps://odsee.its.rochester.edu';
	}
	
	//----------------------------------------------------------------------------
	// Authenticate a user
 	// On failure to authenticate, returns false.
 	// On success, returns an array containing:
 	// [0] -> 1 (true)
 	// [1] -> lastname
 	// [2] -> firstname
 	// [3] -> class year
 	// [4] -> URID
 	// [5] -> email address
	//----------------------------------------------------------------------------
	
	public function Authenticate($netid)
	{
		if($netid == NULL)
			return false;
			
		$ldapconn = ldap_connect($this->m_server);
		
		if(!$ldapconn)
			return false;
			
		$filter = "(|(uid=*))";
		$searchFields = array("sn","cn","givenName","urid","uremailbox");
		   
		$sfind = ldap_search($ldapconn, "uid=$netid,ou=people,dc=rochester,dc=edu", $filter, $searchFields);
		$info = ldap_get_entries($ldapconn, $sfind);

		$studentID;
		for ($i=0; $i<$info["count"]; $i++) 
		{
			$urid = $info[$i]["urid"][0];
			$studentID = $urid;
		}
		if(!empty($studentID))
		{
			return true;
		} 
		return false;
	}
}





?>