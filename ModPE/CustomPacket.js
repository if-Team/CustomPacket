"use strict";

/**
 * @author ChalkPE <amato17@naver.com>
 * @since 2014-11-03
 * @see https://github.com/if-Team/CustomPacket
 */
var CustomPacket = {};
CustomPacket.PORT = 19131;

var channel = null;
var decoder = null;
var buffer = null;

/**
 * @brief  서버에 연결합니다
 * @param  address {string} 서버의 IP
 * @param  message {string} 서버로 보내는 문자열
 * @param  callback {function} 서버가 응답한 내용을 전달받을 콜백 객체
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
            channel.send(src, new java.net.InetSocketAddress(address, PORT));
            
            channel.receive(buffer);
            
            buffer.flip();
            var received = decoder.decode(buffer).toString() + "";
            buffer.clear();
            
            callback(received);
        }catch(e){
        	callback(null);
        }
    }}).start();
}

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
    
	for(var i = 0; i < scripts.size(); i++) {
		var scope = scripts.get(i).scope;
		if(!ScriptableObject.hasProperty(scope, "CustomPacket")){
			ScriptableObject.putProperty(scope, "CustomPacket", CustomPacket);
		}
	}
}