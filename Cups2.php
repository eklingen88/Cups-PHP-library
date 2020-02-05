<?php

namespace Cups;

class Printer
{
	public function getPrinters()
	{
		$response  = $this->runCommand( 'lpstat -p' );
		$printers  = array();

		foreach( $response as $row ) 
		{
	            	preg_match( '/printer\s([^\s]+)/', $line, $printer );
	            	preg_match( '/printer\s[^\s]+\s([^\.]+)/', $line, $status_code );

			if( end( $printer ) ) 
			{
				$printers[] = array( 'name' => end( $printer ), 'status' => end( $statusCode ) );
			}
		}

		return array( 'printers' => $printers );
	}
	
	// just set printers status to accept jobs on cups server
	// needs www-data in group lpadmin
	public function cupsAcceptJobs( $printerName )
	{	
		$command = "cupsaccept " . $printerName;
		
		$this->runCommand( $command );
	}
	
	// just set printers status to reject jobs on cups server
	// needs www-data in group lpadmin
	public function cupsRejectJobs( $printerName )
	{
		$command = "cupsreject " . $printerName;
		
		$this->runCommand( $command );
	}
	
  // work on ubuntu server 18.04 LTS 02/04/2020
  // its helps when files has spaces in string destination
	public function submit( $fileName, $printerName, $qnt )
	{
	  $command = 'lpr -P '. $printerName . ' ' . $fileName . ' 2>&1 -#' . $qnt;
    
    return shell_exec($command);
	}


	protected function runCommand( $command )
	{
		exec( escapeshellcmd( $command ), $output );

		return $output;
	}
}
