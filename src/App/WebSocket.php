<?php
// Thanks for great codes: https://github.com/gemblue/PHPWebsocket

namespace App;

/**
 * Class WebSocket
 * @package App
 */
class WebSocket
{
    private mixed $server;
    public string $address;
    public int $port;
    private array $clients;

    /**
     * WebSocket constructor
     *
     * @param string $address
     * @param int $port
     */
    public function __construct(string $address = '0.0.0.0', int $port = 9090)
    {
        $this->server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $this->address = $address;
        $this->port = $port;

        socket_set_option($this->server, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->server, 0, $port);
        socket_listen($this->server);
    }

    /**
     * Send messages to all clients, previously encoded in json first.
     *
     * @param $message
     * @return bool
     */
    function send($message): bool
    {
        $raw = $this->seal(json_encode([
            'message' => $message
        ]));

        foreach ($this->clients as $client) {
            @socket_write($client, $raw, strlen($raw));
        }

        return true;
    }

    /**
     * Since the receive socket is still raw, we have to unseal it first.
     *
     * @param $socketData
     * @return string
     */
    public function unseal($socketData): string
    {
        $length = ord($socketData[1]) & 127;

        if ($length == 126) {
            $masks = substr($socketData, 4, 4);
            $data = substr($socketData, 8);
        } elseif ($length == 127) {
            $masks = substr($socketData, 10, 4);
            $data = substr($socketData, 14);
        } else {
            $masks = substr($socketData, 2, 4);
            $data = substr($socketData, 6);
        }

        $socketData = '';

        for ($i = 0; $i < strlen($data); ++$i) {
            $socketData .= $data[$i] ^ $masks[$i % 4];
        }

        return $socketData;
    }

    /**
     * Send seal data.
     *
     * @param $socketData
     * @return string
     */
    function seal($socketData): string
    {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($socketData);

        if ($length <= 125)
            $header = pack('CC', $b1, $length);
        elseif ($length < 65536)
            $header = pack('CCn', $b1, 126, $length);
        else
            $header = pack('CCNN', $b1, 127, $length);

        return $header . $socketData;
    }

    /**
     * Sends handshake headers to connected clients.
     *
     * @param $header
     * @param $socket
     * @param $address
     * @param $port
     * @return void
     */
    function handshake($header, $socket, $address, $port): void
    {
        $headers = array();
        $lines = preg_split("/\r\n/", $header);
        foreach ($lines as $line) {
            $line = chop($line);
            if (preg_match("/\A(\S+): (.*)\z/", $line, $matches)) {
                $headers[$matches[1]] = $matches[2];
            }
        }

        $secKey = $headers['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

        $buffer = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n";
        $buffer .= "Upgrade: websocket\r\n";
        $buffer .= "Connection: Upgrade\r\n";
        $buffer .= "WebSocket-Origin: $address\r\n";
        $buffer .= "WebSocket-Location: ws://$address:$port/Server.php\r\n";
        $buffer .= "Sec-WebSocket-Accept:$secAccept\r\n\r\n";

        socket_write($socket, $buffer, strlen($buffer));
    }

    /**
     * Running websocket server.
     *
     * @return void
     */
    public function run(): void
    {
        $this->clients = [
            $this->server
        ];

        $address = $this->address;
        $port = $this->port;

        echo "Listening incoming request on port $this->port ..\n";

        while (true) {
            $newClients = $this->clients;

            socket_select($newClients, $null, $null, 0, 10);

            if (in_array($this->server, $newClients)) {
                $newSocket = socket_accept($this->server);
                $this->clients[] = $newSocket;
                $header = socket_read($newSocket, 1024);
                $this->handshake($header, $newSocket, $address, $port);

                socket_getpeername($newSocket, $ip);
                $this->send("Clients with IP: $ip just joined");
                echo "Clients with IP: $ip just joined \n";

                $index = array_search($this->server, $newClients);
                unset($newClients[$index]);
            }

            foreach ($newClients as $newClientsResource) {
                while (socket_recv($newClientsResource, $socketData, 1024, 0) >= 1) {
                    if ($socketData) {
                        $socketMessage = $this->unseal($socketData);
                        $messageObj = json_decode($socketMessage);

                        if (isset($messageObj->name) && isset($messageObj->message)) {
                            $this->send("$messageObj->name : $messageObj->message");
                        }

                        break 2;
                    }
                }

                $socketData = @socket_read($newClientsResource, 1024, PHP_NORMAL_READ);

                if ($socketData === false) {
                    socket_getpeername($newClientsResource, $ip);
                    $this->send("Clients with IP: $ip just came out");
                    echo "Clients with IP: $ip just came out \n";

                    $index = array_search($newClientsResource, $this->clients);
                    unset($this->clients[$index]);
                }
            }
        }

        // Unreachable statement but just in case.
        socket_close($this->server);
    }
}
