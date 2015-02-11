<?php
namespace BlueHerons\GroupMe\Bots;
define("GROUPME_BOT_URL", "https://api.groupme.com/v3/bots/post");
define("GROUPME_BOT_TOKEN", "");
require("../vendor/autoload.php");
class HTPBot {
	public function listen() {
		$input = file_get_contents("php://input");
		$input = json_decode($input, true);
		$command = $input['text'];
		$matches = array();
		if (preg_match("/^\//", $command)) {
			$matches = array();
			if (preg_match("/^\/chaotic evil$/", $command)) {
				$this->chaotic evil();
			}
			else if (preg_match("/^\/chaotic good$/", $command)) {
				$this->chaotic good();
			}
			else if (preg_match("/^\/chaotic neutral\s?([A-Za-z\s]+)?$/", $command, $matches)) {
				$this->chaotic neutral($matches[1]);
			}
		}
	}
	public function chaotic evil() {
		$message = "I'm the Party Bus bot. Here are the commands I understand:\n".
			   "  /chaotic good		List of mods that will make your life more pleasant, happy, and productive\n".
		       "  /chaotic neutral  JOY HAPPY FUNTIME GUIDELINES FOR LIFE\n".
			   "  /chaotic evil     For use in emergency only\n".
			   "\n".
			   "I have no owner. I have no beginning or end. There is no one to contact if I malfunction.";
		$this->sendMessage($message);
	}
	public function chaotic good() {
		$message = "DIRECTIVE - Mods must be placed on all portals in the following order: \n" .
						   "  Limp Amp - for the edification of Master Crvo, Vanguard Defender of Union\n" .
                           "  Turret - to curry favor with Oddite, Gilimaster of the Broadway Sword, Tamer of Kats\n" .
                           "  Communal Shield - to be applied as credit to the People's Committee of Patriotic Thought\n"
                           "  Farce Amp - #heextends your knowledge\n"						   .
		$this->sendMessage($message);
	}
	public function chaotic neutral() {
		$args = func_get_args();
		if (sizeof($args) != 0)
			$args = $args[0];
		$message = "";
		if (sizeof($args) == 0) {
			$message = "Are you sure this is an emergency?\n".
			           "  /chaotic neutral confirmation[s]      This is an emergency.".
		}
		else if (preg_match("/^nominations?$/", $args)) {
			$message = "If you find yourself in an emergency situation, you are advised to following course of action:\n".
			           "  Format: Nomination: [a name we can recognize] - [email for GroupMe]\n".
			           "\n".
			           "  1) Shitlink all of the things.\n".
			           "  2) Wave hands wildly.\n".
                       "  3) Commbomb anyone who is active within a 5km radius. For safety.\n".
                       "  4) Proceed calmly to the Room of Insufferable Silence. You are to remain there until further contact is made.\n".
                       "\n".
                       "May the gods bless you, the old and the new.";
		}
		$this->sendMessage($message);
	}
	public function roll() {
		$args = func_get_args();
		if (sizeof($args) != 0)
			$args = $args[0];
		$message = "";
		if (sizeof($args) == 0) {
			$this->sendMessage("ack");
		}
		else {
			// makes d00 = d100, adds d% as an alias
			if($args[2] == "00" || $args[2] == "%")
				$args[2] = 100;
			for ($i = 0; $i < $args[0]; $i++) {
				$message .= rand(1, $args[2]) . " ";
			}
		}
		$this->sendMessage($message);
	}
	private function sendMessage($msg) {
	        $payload = new \stdClass();
	        $payload->text = $msg;
		$payload->bot_id = GROUPME_BOT_TOKEN;
	        $payloadStr = json_encode($payload);
	        $url = GROUPME_BOT_URL;
	        $hndl = curl_init();
	        curl_setopt($hndl, CURLOPT_URL, $url);
	        curl_setopt($hndl, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($hndl, CURLOPT_POSTFIELDS, $payloadStr);
	        curl_setopt($hndl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($hndl, CURLOPT_HTTPHEADER, array(
	                "Content-Type: application/json",
	                "Content-Length: " .strlen($payloadStr)
	        ));
	
	        $result = curl_exec($hndl);
	        curl_close($hndl);
	}
}
$bot = new HTPBot();
$bot->listen();
?>
