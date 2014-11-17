var ctx = com.mojang.minecraftpe.MainActivity.currentMainActivity.get();
var root = android.os.Environment.getExternalStorageDirectory().getAbsoluteFile();
var SM = net.zhuoweizhang.mcpelauncher.ScriptManager;
var DIP = android.util.TypedValue.COMPLEX_UNIT_DIP;
var JString = java.lang.String;

function th(runFunc){new java.lang.Thread({run: runFunc}).start();};
function dc(delayMillis, runFunc){new java.lang.Thread({run: function(){java.lang.Thread.sleep(delayMillis);runFunc();}}).start();};
function ui(runFunc){ctx.runOnUiThread(new java.lang.Runnable({run: runFunc}));};
function lp(interval, runFunc){new java.lang.Thread({run: function(){while(true){java.lang.Thread.sleep(interval);runFunc();}}}).start();};
function dp(dips){return Math.ceil(dips * ctx.getResources().getDisplayMetrics().density);};
function ts(message, isLong){ui(function(){android.widget.Toast.makeText(ctx, message, isLong ? 1 : 0).show();});};
function fm(){var args = Array.slice(arguments);var message = args.shift();return JString.format(message, args);};
function cm(){var args = Array.slice(arguments);var message = args.shift();clientMessage(message = JString.format(message, args));return message;};

var CustomPacket = {};
CustomPacket.PORT = 19131;

CustomPacket.enabled = false;
CustomPacket.channel = null;
CustomPacket.receiveBuffer = java.nio.ByteBuffer.allocate(65507);
CustomPacket.decoder = java.nio.charset.Charset.defaultCharset().newDecoder();
CustomPacket.out = cm;

CustomPacket.init = function(){
	try{
		CustomPacket.channel = java.nio.channels.DatagramChannel.open();
		th(CustomPacket.startPacketReceiver);
		
		CustomPacket.enabled = true;
	}catch(e){
		CustomPacket.out(fm("CHANNEL CREATE FAILED - " + e));
        ts(fm("%s - line %.0f\n%s", e.name, e.lineNumber, e.stack));
		
		CustomPacket.enabled = false;
	}
}

CustomPacket.finalize = function(){
    if(CustomPacket.channel != null){
    	CustomPacket.enabled = false;
    	
    	CustomPacket.channel.close();
    	CustomPacket.channel = null;
    }
}

CustomPacket.sendPacket = function(ip, port, message){
    th(function(){
        try{
        	if(ip == null){
            	throw new Error("IP ADDRESS IS NULL");
            }
        	
            var sentBytes = CustomPacket.channel.send(java.nio.ByteBuffer.wrap(new JString(message).getBytes()),
            		new java.net.InetSocketAddress(ip, port));
            
            CustomPacket.out(fm("SENT SUCCESSFULLY - BYTES: %.0f", sentBytes));
        }catch(e){
        	CustomPacket.out(fm("SEND FAILED - " + e));
            ts(fm("%s - line %.0f\n%s", e.name, e.lineNumber, e.stack));
        }
    });
};

CustomPacket.startPacketReceiver = function(){th(function(){
    while(true){
        if(CustomPacket.enabled == false) continue;
        
        try{
            var receivedBytes = CustomPacket.channel.receive(CustomPacket.receiveBuffer);
            CustomPacket.receiveBuffer.flip();
            
            var data = CustomPacket.decoder.decode(CustomPacket.receiveBuffer).toString() + "";
            CustomPacket.receiveBuffer.clear();
            
            if(data != null && data.length > 0){
            	CustomPacket.out(fm("RECIEVED FROM PMMP - BYTES: %.0f", receivedBytes));
            	CustomPacket.Callback.onPacketReceive(JSON.parse(data), data);
            }
        }catch(e){
        	CustomPacket.out(fm("RECEIVE FAILED - " + e));
            ts(fm("%s - line %.0f\n%s", e.name, e.lineNumber, e.stack));
        }
    }
});};

CustomPacket.Callback = {};
CustomPacket.Callback.onPacketReceive = function(result, raw){
	//TODO: Implement behavior when packet received
	CustomPacket.out(fm("RECIEVED DATA - %s", raw));
}
