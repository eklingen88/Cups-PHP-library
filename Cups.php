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
	
	public function defaultSubmit( $filename, $qnt = false)
	{
		$command = 'lpr ';
		
		$command .= $filename;
		
		if ( $qnt )
		{
			$command .= " -#" . $qnt;	
		}
		
		$this->runCommand( $command );
	}

	public function submit( $filename, $printerName = false, $capabilities = array(), $qnt = false)
	{
		if( $printerName )
		{
			$command = 'lp -d ' . $printerName . ' ';
		}
		else
		{
			$command = 'lpr ';
		}

		if( $capabilities )
		{
			foreach( $capabilities  as $cap ) 
			{
				$command .= '-o ' . $cap . ' ';
			}
		}

		if( $filename )
		{
			$command .= $filename;
		}

		if ( $qnt )
		{
			$command .= " -#" . $qnt;	
		}
		
		$this->runCommand( $command );
	}


	protected function runCommand( $command )
	{
		exec( escapeshellcmd( $command ), $output );

		return $output;
	}
}
