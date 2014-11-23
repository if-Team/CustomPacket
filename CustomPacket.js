var CustomPacket = {
	/**
	 * @file   CustomPacket.js
	 * @date   2014-11-03
	 * @author ChalkPE
	 * @brief  블럭런쳐에서 실행되는 CustomPacket의 클라이언트입니다.
	 */
	
	PORT: 19131, ///< 통신에 사용할 포트. PE의 기본 포트가 19132이므로 19131을 채택했습니다. (근데 이거 어디에 쓰이는거지)
	DEBUG: true, ///< 디버그 모드. true이면 디버깅 메세지가 출력됩니다.
	
	enabled: false, ///< 활성화 여부. 쓰레드 종료를 위해 사용됩니다.
	
	channel: null, ///< 소켓 통신에 쓰이는 객체입니다.
	receiveBuffer: java.nio.ByteBuffer.allocate(65507), ///< 패킷 수신에 쓰이는 버퍼입니다.
	decoder: java.nio.charset.Charset.defaultCharset().newDecoder(), ///< 버퍼를 디코딩해줍니다. 기본값은 UTF-8
	
	/**
	 * @brief  디버깅 메세지를 출력합니다.
	 * @param  message 출력하는 메세지
	 * @return 없음
	 */
	out: clientMessage,
	
	/**
	 * @brief   소켓을 열고, 패킷을 받는 쓰레드를 시작합니다.
	 * @warning 객체가 중복 선언되지 않도록 호출에 주의해주세요.
	 * @return  없음
	 */
	init: function init(){
		try{
			CustomPacket.channel = java.nio.channels.DatagramChannel.open();
			CustomPacket.startPacketReceiver();
			
			CustomPacket.enabled = true;
		}catch(e){
			CustomPacket.enabled = false;
			
			if(CustomPacket.DEBUG){
				CustomPacket.out("CHANNEL CREATE FAILED - " + e);
				CustomPacket.out(e.name + " - line " + e.lineNumber + "\n" + e.stack);
			}
		}
	},
	
	/**
	 * @brief  소켓을 닫고, 모든 쓰레드를 비활성화합니다.
	 * @return 없음
	 */
	finalize: function finalize(){
		if(CustomPacket.channel !== null){
			CustomPacket.channel.close();
			CustomPacket.channel = null;
			
			CustomPacket.enabled = false;
		}
	},
	
	/**
	 * @brief  문자열 패킷을 보냅니다. 예외는 내부에서 처리합니다.
	 * @param  ip      수신자의 IP 문자열
	 * @param  port    수신자의 포트 번호
	 * @param  message 보내는 문자열
	 * @return 보낸 패킷의 바이트 수
	 */
	sendPacket: function sendPacket(ip, port, message){
		new java.lang.Thread(){
			run: function(){
				try{
					var data = java.nio.ByteBuffer.wrap(new java.lang.String(message).getBytes());
					var receiverAddress = new java.net.InetSocketAddress(ip, port);
					var sentBytes = CustomPacket.channel.send(data, receiverAddress);
					
					if(CustomPacket.DEBUG){
						CustomPacket.out("SENDING SUCCEEDED - TRANSFERRED " + sentBytes + " BYTES");
					}
					
					return sentBytes;
				}catch(e){
					if(CustomPacket.DEBUG){
						CustomPacket.out("SEND FAILED - " + e);
						CustomPacket.out(e.name + " - line " + e.lineNumber + "\n" + e.stack);
					}
				}
			}
		}.start();
	},
	
	/**
	 * @brief   계속 패킷을 받는 쓰레드를 시작합니다.
	 * @warning 쓰레드가 중복 실행되지 않도록 호출에 주의해주세요.
	 * @return  없음
	 */
	startPacketReceiver: function startPacketReceiver(){
		new java.lang.Thread(){
			run: function(){
				while(true){
					if(CustomPacket.enabled === false || CustomPacket.channel === null) continue;
					
					try{
						var receivedBytes = CustomPacket.channel.receive(CustomPacket.receiveBuffer);
						if(receivedBytes === -1) continue;
						
						CustomPacket.receiveBuffer.flip(); //버퍼를 플립합니다. (java.nio.ByteBuffer 클래스를 보세요)
						var data = CustomPacket.decoder.decode(CustomPacket.receiveBuffer).toString() + "";
						CustomPacket.receiveBuffer.clear(); //버퍼를 비웁니다.

						CustomPacket.Callback.onPacketReceive(data); //콜백을 호출합니다.
						
						if(CustomPacket.DEBUG){
							CustomPacket.out("RECIEVED FROM PMMP - BYTES: " + receivedBytes);
						}
					}catch(e){
						if(CustomPacket.DEBUG){
							CustomPacket.out("RECEIVE FAILED - " + e);
							CustomPacket.out(e.name + " - line " + e.lineNumber + "\n" + e.stack);
						}
					}
				}
			}
		}.start();
	},
	
	/**
	 * @brief   여러 이벤트에 대한 콜백 메서드들이 포함되어 있는 패키지입니다.
	 * @warning 내부의 콜백들은 프로그램 실행 중 덮어씌워질 수도 있습니다.
	 */
	Callback: {
		
		/**
		 * @brief  패킷을 수신했을 때 호출됩니다.
		 * @param  result 수신한 문자열
		 * @return 없음
		 */
		onPacketReceive: function onPacketReceive(result){
			//TODO: Implement behavior when packet received
			
			if(CustomPacket.DEBUG){
				CustomPacket.out("RECIEVED DATA - " + result);
			}
		}
	}
};
