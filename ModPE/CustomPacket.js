/* Copyright 2014-2015 if(Team);
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

"use strict";

/**
 * @author ChalkPE <amato17@naver.com>
 * @since 2014-11-03
 * @copyright 2014-2015 if(Team);
 * @license Apache-2.0
 * @see https://github.com/if-Team/CustomPacket
 * @namespace
 */
var CustomPacket = {};

/**
 * 통신에 사용할 포트입니다
 * @memberof CustomPacket
 * @type {Number}
 * @const
 */
CustomPacket.PORT = 19131;

/**
 * 주기적으로 수신할 때의 딜레이 간격입니다
 * @memberof CustomPacket
 * @type {Number}
 * @const
 */
CustomPacket.HEARTBEAT_SECONDS = 2;

/**
 * 주기적으로 수신할 때 서버에 전송하는 문자열입니다
 * @memberof CustomPacket
 * @type {String}
 * @const
 */
CustomPacket.HEARTBEAT_SIGNAL = String.fromCharCode(16);

/**
 * 통신에 쓰이는 채널입니다
 * @memberof CustomPacket
 * @type {java.nio.channels.DatagramChannel}
 * @private
 */
var channel = null;

/**
 * 서버로부터 수신한 바이트버퍼를 디코드할 디코더입니다
 * @memberof CustomPacket
 * @type {java.nio.charset.CharsetDecoder}
 * @private
 */
var decoder = null;

/**
 * 수신에 쓰일 버퍼입니다
 * @memberof CustomPacket
 * @type {java.nio.ByteBuffer}
 * @private
 */
var buffer = null;

/**
 * 서버의 응답 결과를 받을 콜백입니다
 * @callback Callback
 * @param {String|Error} result - 응답 문자열 및 통신 중 발생한 에러
 */

/**
 * 서버에 연결합니다
 * @param {!String} address - 서버의 IP
 * @param {!String} message - 서버로 보내는 문자열
 * @param {!Callback} callback
 * @memberof CustomPacket
 * @public
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

/**
 * 2초에 한 번씩 지속적으로 서버에 연결합니다
 * @param {!String} address - 서버의 IP
 * @param {!Callback} callback
 * @memberof CustomPacket
 * @public
 */
CustomPacket.registerRepeatingHook = function(address, callback){
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
    channel = java.nio.channels.DatagramChannel.open();
    decoder = java.nio.charset.Charset.forName("UTF-8").newDecoder();
    buffer = java.nio.ByteBuffer.allocateDirect(65507);
    
	Object.freeze(CustomPacket);
}catch(e){
    print(error.name + ": " + error.message + " at line " + error.lineNumber + "\n" + error.stack);
}

/**
 * 맵에 입장할 때마다 라이브러리를 등록합니다
 * @static
 */
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