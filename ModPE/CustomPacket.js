"use strict";

/**
 * @author ChalkPE <amato17@naver.com>
 * @since 2014-11-03
 * @see https://github.com/if-Team/CustomPacket
 */

var CustomPacket = {};
CustomPacket.PORT = 19131;
CustomPacket.HEARTBEAT_SECONDS = 2;
CustomPacket.HEARTBEAT_SIGNAL = String.fromCharCode(16);

var channel = null;
var decoder = null;
var buffer = null;

/**
 * @brief  서버에 연결합니다
 * @param  address {String} 서버의 IP
 * @param  message {String} 서버로 보내는 문자열
 * @param  callback {function} 응답을 받을 콜백 메서드 (String 또는 Error)
 * @return 없음
 */
CustomPacket.get = function get(address, message, callback){
    new java.lang.Thread({run: function(){
        try{
        	if(channel === null){
                throw new Error("channel is not initialized");
            }
        	
        	if(address === null || message === null || callback === null){
                throw new Error("argument cannot be null");
            }
        	
        	if(typeof address !== "string"){
        		throw new Error("address must be a string");
        	}
        	
        	if(typeof message !== "string"){
        		throw new Error("message must be a string");
        	}
        	
        	if(typeof callback !== "function"){
        		throw new Error("callback must be a function");
        	}
        	
            var src = java.nio.ByteBuffer.wrap(new java.lang.String(message).getBytes("UTF-8"));
            channel.send(src, new java.net.InetSocketAddress(address, CustomPacket.PORT));
            
            channel.receive(buffer);
            
            buffer.flip();
            var received = decoder.decode(buffer).toString() + "";
            buffer.clear();
            
            try{
            	callback(received);
            }catch(e){}
        }catch(error){
        	try{
        		callback(error);
            }catch(e){}
        }
    }}).start();
}

CustomPacket.registerRefreshHook = function(address, callback){
    try{
        new java.lang.Thread({run: function(){
            while(Server.getAddress() !== null){
                CustomPacket.get(address, CustomPacket.HEARTBEAT_SIGNAL, callback);
                java.lang.Thread.sleep(CustomPacket.HEARTBEAT_SECONDS * 1000);
            }
        }}).start();
    }catch(e){
        print(e);
    }
};

try{
	Object.freeze(CustomPacket);
	
    channel = java.nio.channels.DatagramChannel.open();
    decoder = java.nio.charset.Charset.forName("UTF-8").newDecoder();
    buffer = java.nio.ByteBuffer.allocateDirect(65507);
}catch(e){
    print(e.name + " - " + e.message);
}

function selectLevelHook(){
	var scripts = net.zhuoweizhang.mcpelauncher.ScriptManager.scripts;
    var ScriptableObject = org.mozilla.javascript.ScriptableObject;
    
    var loaded = false;
    
	for(var i = 0; i < scripts.size(); i++) {
		var scope = scripts.get(i).scope;
		if(!ScriptableObject.hasProperty(scope, "CustomPacket")){
			ScriptableObject.putProperty(scope, "CustomPacket", CustomPacket);
			loaded = true;
		}
	}
	
	if(loaded){
		print("CustomPacket has been loaded!");
	}
}