<?
// Get ip
function get_ip()
{
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
	elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP']; }
	else {
		$ip = $_SERVER['REMOTE_ADDR']; }

	return $ip;
}

// Execute a "whois" on the host's ip address
function whois_func($srv, $port, $output_flag)
{
	if (eregi("^[a-z0-9\:\.\-]+$", $srv))
	{
		global $host_ip;

		// The whois server "whois.arin.net" requires a "+" flag to get all the details
		if ($srv == 'whois.arin.net') {
			$srv .= ' +'; }

		// Set a variable containing the command to be sent to the system
		$command = "whois -h $srv $host_ip";

		// If we passed a specific port to this function to connect to, add the necessary info to the command
		if ($port > 0) {
			$command .= " -p $port"; }

		// Send the whois command to the system
		//   Normally, the shell_exec function does not report STDERR messages.  The "2>&1" option tells the system
		//   to pipe STDERR to STDOUT so if there is an error, we can see it.
		$fp = shell_exec("$command 2>&1");

		// If the $output_flag variable is set to "TRUE", send the results to the parse_output() function...
		if ($output_flag == 'TRUE')
		{
			$output = '<b>Whois [IP] Results:</b><blockquote>';
			$output .= nl2br(htmlentities(trim($fp)));
			$output .= '</blockquote>';

			parse_output($output);
		}
		// otherwise return the results in a variable
		else {
			return $fp; }
	}
	else
	{
		echo '<b>Whois [IP] Results:</b><blockquote>';
		echo 'Invalid character(s) in the Whois [IP] Server field.';
		echo '</blockquote>';
	}
}

?>