<?php  

	error_reporting(E_ALL);

	/* Permitir al script esperar para conexiones. */	
	set_time_limit(0);

	/* Activar el volcado de salida implícito, así veremos lo que estamo obteniendo
	* mientras llega. */
	ob_implicit_flush();

	$host = '192.168.1.2'; //host
	$port = '8888'; //port
	$null = NULL; //null var
	$address = '0.0.0.0';//accept from anywhere

	  	//Create TCP/IP sream socket
	if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
	 
	    echo "socket_create() falló: razón: " . socket_strerror(socket_last_error()) . "\n";

	}else{

			//reuseable port
		socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

		//bind socket to specified host
		if (socket_bind($socket, $address, $port) === false) {
		 
		    echo "socket_bind() falló: razón: " . socket_strerror(socket_last_error($socket)) . "\n";

		}

		//listen to port

		if (socket_listen($socket, 5) === false) {
		   
		    echo "socket_listen() falló: razón: " . socket_strerror(socket_last_error($socket)) . "\n";

		}

		echo"Server status: on";

		//create & add listning socket to the list
		$clients = array($socket);

		//start endless loop, so that our script doesn't stop
		while (true) {
				
			//manage multipal connections
			$changed = $clients;

			//returns the socket resources in $changed array
			socket_select($changed, $null, $null, 0, 10);
			
			//check for new socket
			if (in_array($socket, $changed)) {
			
				$socket_new = socket_accept($socket); //accpet new socket
			
				$clients[] = $socket_new; //add socket to client array
				
				$header = socket_read($socket_new, 1024); //read data sent by the socket
			
				perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake
				
				socket_getpeername($socket_new, $ip); //get ip address of connected socket
				
				//mensaje que envia al dispositivo que se conecta
				$response = mask(json_encode(array('status' => 'success',
												   'type' => 'system', 
					                               'message' => 'user connected',
					                               'data' => array('IP' =>  $ip)
					                           	  )
											)
							    ); 
			
				send_message($response); //notify all users about new connection
				
				//make room for new socket
				$found_socket = array_search($socket, $changed);
			
				unset($changed[$found_socket]);
			
			}
					
			$status = '';//que se va a registrar
			$mensaje = '';//si esta ocupada la caja
			$turno = '';//el id de la caja
			$ocupado = '';//turno
			$idCaja = '';//id de la caja


			//loop through all connected sockets
			foreach ($changed as $changed_socket) {	
						
				//check for any incomming data 
				while(socket_recv($changed_socket, $buf, 1024, 0) >= 1){
					
					$received_text = unmask($buf); //unmask data
					
					$tst_msg = json_decode($received_text); //json decode 
					
					//data from client
					if(gettype($tst_msg) === 'object'){

						$status = $tst_msg -> status;
						$mensaje = $tst_msg -> mensaje;
						$turno = $tst_msg -> turno;
						$ocupado = $tst_msg -> ocupado;
						$idCaja = $tst_msg -> idCaja;
					
					}

					//preparas los datos con los que respondera el socket a los clientes 
					$response_text = mask(json_encode(array('type' => 'data',
														    'status' => $status, 
						                                    'mensaje' => $mensaje, 
						                                    'turno' => $turno, 
						                                    'ocupado' => $ocupado, 
						                                    'idCaja' => $idCaja
						                                   )));
					
					//enviar datos a los clientes
					send_message($response_text);
					
					break 2; 
				
				}
						
				
				$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
				
				// check disconnected client
				if ($buf === false) { 
							
					// remove client for $clients array
					$found_socket = array_search($changed_socket, $clients);
					
					socket_getpeername($changed_socket, $ip);
					
					unset($clients[$found_socket]);
					
					//mensaje que envia al dispositivo que se conecta
					$response = mask(json_encode(array('status' => 'success',
												       'type' => 'system', 
					                                   'message' => 'user disconnected',
					                                   'data' => array('IP' =>  $ip)
					                           	  )
											)
							    ); 

					send_message($response);
				
					
				}

			}

		}

	}

	function send_message($msg){

		global $clients;
		
		foreach($clients as $changed_socket){

			@socket_write($changed_socket, $msg, strlen($msg));
		
		}
		
		return true;

	}

	//Unmask incoming framed message
	function unmask($text) {

		$length = ord($text[1]) & 127;
		
		if($length == 126) {
		
			$masks = substr($text, 4, 4);
			$data = substr($text, 8);
		
		}
		elseif($length == 127) {
		
			$masks = substr($text, 10, 4);
			$data = substr($text, 14);
		
		}else {
		
			$masks = substr($text, 2, 4);
			$data = substr($text, 6);
		
		}
		
		$text = "";
		
		for ($i = 0; $i < strlen($data); ++$i) {
		
			$text .= $data[$i] ^ $masks[$i%4];
		
		}
		
		return $text;

	}

	//Encode message for transfer to client.
	function mask($text){

			$b1 = 0x80 | (0x1 & 0x0f);

			$length = strlen($text);
			
			if($length <= 125)
			
				$header = pack('CC', $b1, $length);
			
			elseif($length > 125 && $length < 65536)
			
				$header = pack('CCn', $b1, 126, $length);
			
			elseif($length >= 65536)
			
				$header = pack('CCNN', $b1, 127, $length);
			
			return $header.$text;

		}

	//handshake new client.
	function perform_handshaking($receved_header,$client_conn, $host, $port){
		
		$headers = array();

		$lines = preg_split("/\r\n/", $receved_header);
		
		foreach($lines as $line){

			$line = chop($line);
			
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches)){

				$headers[$matches[1]] = $matches[2];
			
			}
		
		}

		$secKey = $headers['Sec-WebSocket-Key'];
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		
		//hand shaking header
		$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"WebSocket-Origin: $host\r\n" .
		"WebSocket-Location: ws://$host:$port/php/proyevtos/turnero/turnero/turnos.php"."\r\n".
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";

		socket_write($client_conn, $upgrade, strlen($upgrade));

	} 

	socket_close($sock);

	echo "Server status: off";

?>