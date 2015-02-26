/**
 * @since 2015-02-26
 * @author ChalkPE <amato0617@gmail.com>
 * @file The example of CustomPacket client
 * @see https://github.com/if-Team/CustomPacket
 */

function procCmd(str){
	var cmd = str.split(" ");
	if(cmd.shift() === "send"){
		if(typeof CustomPacket === "undefined"){
			clientMessage("CustomPacket API not found; apply CustomPacket.js and enter some local world to load");
			return;
		}
		
		var command = cmd.join(" ");
		CustomPacket.get(Server.getAddress(), command, function(response){
			if(response instanceof Error){
				var error = response;
				clientMessage(error.name + ": " + error.message + " at line " + error.lineNumber + "\n" + error.stack);
				return;
			}
			
			clientMessage("Server response: " + retval);
		});
		
		clientMessage("Sending to server: " + command);
	}
}