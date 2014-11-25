var CustomPacket = (function(){
	/**
	 * @file   CustomPacket.js
	 * @date   2014-11-03
	 * @author ChalkPE
	 * @brief  블럭런쳐에서 실행되는 CustomPacket의 클라이언트입니다.
	 */
	
	"use strict";
	
	var PORT = 19131; ///< 통신에 사용할 포트. PE의 기본 포트가 19132이므로 19131을 채택했습니다. (근데 이거 어디에 쓰이는거지)
	var DEBUG = true; ///< 디버그 모드. true이면 디버깅 메세지가 출력됩니다.
	
	var channel = null; ///< 소켓 통신에 쓰이는 객체입니다.
	
	/**
	 * @brief  디버깅 메세지를 출력합니다.
	 * @param  message 출력하는 메세지
	 * @return 없음
	 */
	function out(message){
		print(message);
	}
	
	
	/**
	 * @brief  소켓을 닫고, 모든 쓰레드를 비활성화합니다.
	 * @return 없음
	 */
	function finalize(){
		if(channel !== null){
			channel.close();
			channel = null;
		}
	}
	
	/**
	 * @brief  문자열 패킷을 보냅니다. 예외는 내부에서 처리합니다.
	 * @param  message 보내는 문자열
	 * @param  ip      수신자의 IP 문자열
	 * @param  port    수신자의 포트 번호. 생략시 PORT로 초기화.
	 * @return 보낸 패킷의 바이트 수
	 */
	function sendPacket(message, ip, port){
		new java.lang.Thread({run: function(){
			if(channel === null) throw new Error("channel is not initialized");
			
			port = port || PORT;
			
			try{
				var data = java.nio.ByteBuffer.wrap(new java.lang.String(message).getBytes());
				var receiverAddress = new java.net.InetSocketAddress(ip, port);
				var sentBytes = channel.send(data, receiverAddress);

				if(DEBUG){
					out("SENDING SUCCEEDED - TRANSFERRED " + sentBytes + " BYTES");
				}

				return sentBytes;
			}catch(e){
				if(DEBUG){
					out("SEND FAILED - " + e);
					out(e.name + " - line " + e.lineNumber + "\n" + e.stack);
				}
			}
		}}).start();
	}
	
	/**
	 * @brief  패킷을 수신했을 때 호출되는 콜백 메서드입니다.
	 * @param  result 수신한 문자열
	 * @return 없음
	 */
	function onPacketReceive(result){
		//TODO: Implement behavior when packet received

		if(DEBUG){
			out("RECIEVED DATA - " + result);
		}
	}
	
	try{
		channel = java.nio.channels.DatagramChannel.open();
		
		new java.lang.Thread({run: function(){
			
			var buffer = java.nio.ByteBuffer.allocate(65507);
			var decoder = java.nio.charset.Charset.defaultCharset().newDecoder();
			
			while(true){
				if(channel === null) continue;

				try{
					var read = channel.receive(buffer);
					if(read === -1) continue;

					buffer.flip(); // 버퍼를 플립합니다. (java.nio.ByteBuffer 클래스를 보세요)
					var data = decoder.decode(buffer).toString() + "";
					buffer.clear(); // 버퍼를 비웁니다.

					onPacketReceive(data); // 콜백을 호출합니다.

					if(DEBUG){
						out("RECIEVED FROM PMMP - BYTES: " + read);
					}
				}catch(e){
					if(DEBUG){
						out("RECEIVE FAILED - " + e);
						out(e.name + " - line " + e.lineNumber + "\n" + e.stack);
					}
				}
			}
		}}).start();
		
	}catch(e){
		if(DEBUG){
			out("CHANNEL CREATE FAILED - " + e);
			out(e.name + " - line " + e.lineNumber + "\n" + e.stack);
		}
	}
	
	return {
		PORT: PORT,
		sendPacket: sendPacket,
		finalize: finalize
	};
	
}());
