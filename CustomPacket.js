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


/*********** IMPORT GUICONSOLE ***********/
var GUIConsole = {};

GUIConsole.Resources = {
	typeface: android.graphics.Typeface.MONOSPACE,
	
	uiColor: "#ff80cbc5",
	backgroundColor: "#60808080",
	buttonTextColor: "#ffffffff",
	
	logColor: "#ffffff",
	commandColor: "#0099ff",
	errorColor: "#ff5555",
	highlightColor: "#ffcc00",
	pointerColor: "#ffffff"
};

GUIConsole.Skin = {
	original: {
		uiColor: "#ff80cbc5",
		backgroundColor: "#60808080",
		buttonTextColor: "#ffffffff",

		logColor: "#ffffff",
		commandColor: "#0099ff",
		errorColor: "#ff5555",
		highlightColor: "#ffcc00",
		pointerColor: "#ffffff"
	},
	green: {
		uiColor: "#80cb92",
		backgroundColor: "#cc000000",
		buttonTextColor: "#ffffffff",

		logColor: "#80cb92",
		commandColor: "#ffffff",
		errorColor: "#356e47",
		highlightColor: "#80cb92",
		pointerColor: "#80cb92"
	},
	red: {
		uiColor: "#cb8080",
		backgroundColor: "#cc000000",
		buttonTextColor: "#ffffffff",

		logColor: "#cb8080",
		commandColor: "#ffffff",
		errorColor: "#ff5555",
		highlightColor: "#cb8080",
		pointerColor: "#cb8080"
	},
	cyan: {
		uiColor: "#80cbc5",
		backgroundColor: "#cc000000",
		buttonTextColor: "#ffffffff",

		logColor: "#80cbc5",
		commandColor: "#ffffff",
		errorColor: "#55fffd",
		highlightColor: "#80cbc5",
		pointerColor: "#80cbc5"
	},
	lemon: {
		uiColor: "#cbc880",
		backgroundColor: "#cc000000",
		buttonTextColor: "#ffffffff",

		logColor: "#cbc880",
		commandColor: "#ffffff",
		errorColor: "#edff55",
		highlightColor: "#cbc880",
		pointerColor: "#cbc880"
	}
};

GUIConsole.Callback = {
	onSystemCommand: function(cmd){
		//TODO: Implement command event
		var str = cmd.split(" ");
		switch(str.shift()){
			case "ping":
				GUIConsole.Console.append("Pong!");
				break;
			
			case "echo":
				GUIConsole.Console.append(str.join(" "));
				break;
			
			case "close":
			case "exit":
				GUIConsole.GUI.window.dismiss();
				break;
			
			case "skin":
				GUIConsole.GUI.setStyle(GUIConsole.Skin[str[0]] || GUIConsole.Resources);
				/* falls through */
				
			case "clear":
			case "cls":
				GUIConsole.Console.clear();
				break;
				
			case "resize":
				GUIConsole.GUI.extended = str[0] == "true";
				GUIConsole.GUI.updateWindow();
				break;
			
			default:
				if(typeof GUIConsole.Callback.onCommand == 'function'){
					GUIConsole.Callback.onCommand(cmd);
					return;
				}
				GUIConsole.Console.append('<font color="' + GUIConsole.Resources._errorColor + '">' + "[ERROR] NOT IMPLEMENTED</font>");
		}
	}
};

GUIConsole.GUI = {};
GUIConsole.GUI.extended = false;
GUIConsole.GUI.Values = {
	srad: dp(2.5), sxoff: dp(1), syoff: dp(1),
	xoff: dp(10), yoff: dp(20), xlim: dp(80),
	grav: android.view.Gravity.TOP | android.view.Gravity.LEFT,
	nhgt: 1/3.5, ehgt: 3/4
};

GUIConsole.GUI.getParams = function(width, height, margin, left, top, right, bottom, gravity){
	var p = margin ? new android.widget.LinearLayout.LayoutParams(width, height, margin) : new android.widget.LinearLayout.LayoutParams(width, height);
	
	if(left) p.leftMargin = left; if(right) p.rightMargin = right; if(top) p.topMargin = top; if(bottom) p.bottomMargin = bottom;
	if(gravity) p.gravity = gravity;
	
	return p;
};

GUIConsole.GUI.getRoundRectShape = function(r, color){
	var f = new java.lang.Float(r);
	var shape = new android.graphics.drawable.ShapeDrawable(new android.graphics.drawable.shapes.RoundRectShape([f, f, f, f, f, f, f, f], null, null));
	shape.getPaint().setColor(android.graphics.Color.parseColor(color));
	
	return shape;
}

GUIConsole.Console = {};
GUIConsole.Console.copyright = '<font color="%s">GUIConsole - Copyright (c) 2014 Chalk</font>';
GUIConsole.Console.command = '<font color="%s">></font> <font color="%s">%s</font>';
GUIConsole.Console.log = new java.lang.StringBuffer(1024);

GUIConsole.GUI.create = function(style){ui(function(){
	style = style || GUIConsole.Resources;
	
	ctx.setTheme(android.R.style.Theme_Holo);
	GUIConsole.GUI.display = ctx.getWindowManager().getDefaultDisplay();
    
	GUIConsole.GUI.layout = new android.widget.FrameLayout(ctx);
	
	GUIConsole.GUI.log = new android.widget.TextView(ctx);
	GUIConsole.GUI.log.setTextSize(DIP, 15);
	GUIConsole.GUI.log.setShadowLayer(GUIConsole.GUI.Values.srad, GUIConsole.GUI.Values.sxoff, GUIConsole.GUI.Values.syoff, android.graphics.Color.BLACK);
	GUIConsole.GUI.log.setPadding(dp(7), dp(7), dp(7), dp(7));
    
	GUIConsole.GUI.scroll = new android.widget.ScrollView(ctx);
	GUIConsole.GUI.scroll.addView(GUIConsole.GUI.log); 
	GUIConsole.GUI.layout.addView(GUIConsole.GUI.scroll, GUIConsole.GUI.getParams(-1, -1));
	
	var inputParam = new android.widget.FrameLayout.LayoutParams(-2, -2);
	inputParam.gravity = android.view.Gravity.RIGHT | android.view.Gravity.BOTTOM;
	inputParam.topMargin = dp(8); inputParam.bottomMargin = dp(8); inputParam.rightMargin = dp(8);
	
	GUIConsole.GUI.input = new android.widget.TextView(ctx);
	GUIConsole.GUI.input.setText("CMD");
	GUIConsole.GUI.input.setTextSize(DIP, 18);
	GUIConsole.GUI.input.setGravity(android.view.Gravity.CENTER);
	GUIConsole.GUI.input.setOnClickListener(new android.view.View.OnClickListener(){onClick: GUIConsole.GUI.showCommand});
	GUIConsole.GUI.input.setPadding(dp(9), dp(7), dp(9), dp(7));
	GUIConsole.GUI.layout.addView(GUIConsole.GUI.input, inputParam);
	
	
	GUIConsole.GUI.commandLayout = new android.widget.LinearLayout(ctx);
	GUIConsole.GUI.commandLayout.setOrientation(android.widget.LinearLayout.HORIZONTAL);
	GUIConsole.GUI.commandLayout.setGravity(android.view.Gravity.CENTER);
	GUIConsole.GUI.commandLayout.setPadding(dp(7), dp(10), dp(7), dp(10));
	
	GUIConsole.GUI.closeButton = new android.widget.TextView(ctx);
	GUIConsole.GUI.closeButton.setText("CLOSE");
	GUIConsole.GUI.closeButton.setTextSize(DIP, 18);
	GUIConsole.GUI.closeButton.setShadowLayer(GUIConsole.GUI.Values.srad, GUIConsole.GUI.Values.sxoff, GUIConsole.GUI.Values.syoff, android.graphics.Color.BLACK);
	GUIConsole.GUI.closeButton.setGravity(android.view.Gravity.CENTER);
	GUIConsole.GUI.closeButton.setOnClickListener(new android.view.View.OnClickListener(){onClick: function(){
		GUIConsole.GUI.commandPopup.dismiss();
	}});
	//GUIConsole.GUI.closeButton.setPadding(dp(9), dp(7), dp(9), dp(7));
	GUIConsole.GUI.commandLayout.addView(GUIConsole.GUI.closeButton, GUIConsole.GUI.getParams(-2, -2, 0, 0, 0, dp(10)));
	
	GUIConsole.GUI.command = new android.widget.EditText(ctx);
	GUIConsole.GUI.command.setHint("Input command...");
	GUIConsole.GUI.command.setTextSize(DIP, 15);
	GUIConsole.GUI.command.setShadowLayer(GUIConsole.GUI.Values.srad, GUIConsole.GUI.Values.sxoff, GUIConsole.GUI.Values.syoff, android.graphics.Color.BLACK);
	GUIConsole.GUI.command.setSingleLine(true);
	GUIConsole.GUI.commandLayout.addView(GUIConsole.GUI.command, GUIConsole.GUI.getParams(-2, -2, 1.0));
	
	GUIConsole.GUI.enterCommand = new android.widget.TextView(ctx);
	GUIConsole.GUI.enterCommand.setText("SEND");
	GUIConsole.GUI.enterCommand.setTextSize(DIP, 18);
	GUIConsole.GUI.enterCommand.setShadowLayer(GUIConsole.GUI.Values.srad, GUIConsole.GUI.Values.sxoff, GUIConsole.GUI.Values.syoff, android.graphics.Color.BLACK);
	GUIConsole.GUI.enterCommand.setGravity(android.view.Gravity.CENTER);
	GUIConsole.GUI.enterCommand.setOnClickListener(new android.view.View.OnClickListener(){onClick: GUIConsole.GUI.onCommand});
	//GUIConsole.GUI.enterCommand.setPadding(dp(9), dp(7), dp(9), dp(7));
	GUIConsole.GUI.commandLayout.addView(GUIConsole.GUI.enterCommand, GUIConsole.GUI.getParams(-2, -2, 0, dp(10)));
	
	GUIConsole.GUI.setStyle(style);
	
	GUIConsole.GUI.window = new android.widget.PopupWindow(GUIConsole.GUI.layout, GUIConsole.GUI.display.getWidth() - GUIConsole.GUI.Values.xlim, GUIConsole.GUI.display.getHeight() * GUIConsole.GUI.Values.nhgt);
	GUIConsole.GUI.window.showAtLocation(ctx.getWindow().getDecorView(), GUIConsole.GUI.Values.grav, GUIConsole.GUI.Values.xoff, GUIConsole.GUI.Values.yoff);
	
	GUIConsole.GUI.commandPopup = new android.widget.PopupWindow(GUIConsole.GUI.commandLayout, GUIConsole.GUI.display.getWidth() - GUIConsole.GUI.Values.xlim, -2);
	GUIConsole.GUI.commandPopup.setFocusable(true);
	
	GUIConsole.Console.clear();
});};

GUIConsole.GUI.setStyle = function(res){
	res = res || {};
	
	GUIConsole.GUI.buttonBackgroud = GUIConsole.GUI.getRoundRectShape(dp(3), res.uiColor || GUIConsole.Resources.uiColor);
	GUIConsole.GUI.background = GUIConsole.GUI.getRoundRectShape(dp(9), res.backgroundColor || GUIConsole.Resources.backgroundColor);
	
	GUIConsole.GUI.log.setTextColor(android.graphics.Color.parseColor(res.logColor || GUIConsole.Resources.logColor));
	
	GUIConsole.GUI.scroll.setBackgroundDrawable(GUIConsole.GUI.background);
			
	GUIConsole.GUI.input.setTextColor(android.graphics.Color.parseColor(res.buttonTextColor || GUIConsole.Resources.buttonTextColor));
	GUIConsole.GUI.input.setBackgroundDrawable(GUIConsole.GUI.buttonBackgroud);
	
	GUIConsole.GUI.command.setTextColor(android.graphics.Color.parseColor(res.buttonTextColor || GUIConsole.Resources.buttonTextColor));
	GUIConsole.GUI.command.setHintTextColor(android.graphics.Color.parseColor(res.buttonTextColor || GUIConsole.Resources.buttonTextColor));
	GUIConsole.GUI.command.getBackground().setColorFilter(android.graphics.Color.parseColor(res.uiColor || GUIConsole.Resources.uiColor), android.graphics.PorterDuff.Mode.SRC_IN);
	
	GUIConsole.GUI.closeButton.setTextColor(android.graphics.Color.parseColor(res.buttonTextColor || GUIConsole.Resources.buttonTextColor));
	
	GUIConsole.GUI.enterCommand.setTextColor(android.graphics.Color.parseColor(res.buttonTextColor || GUIConsole.Resources.buttonTextColor));
	
	GUIConsole.GUI.setTypeface(res);
	
	GUIConsole.Resources._commandColor = res.commandColor || GUIConsole.Resources.commandColor;
	GUIConsole.Resources._errorColor = res.errorColor || GUIConsole.Resources.errorColor;
	GUIConsole.Resources._highlightColor = res.highlightColor || GUIConsole.Resources.highlightColor;
	GUIConsole.Resources._pointerColor = res.pointerColor || GUIConsole.Resources.pointerColor;
};

GUIConsole.GUI.setTypeface = function(res){
	GUIConsole.GUI.log.setTypeface(res.typeface || GUIConsole.Resources.typeface);
	GUIConsole.GUI.input.setTypeface(res.typeface || GUIConsole.Resources.typeface);
	GUIConsole.GUI.command.setTypeface(res.typeface || GUIConsole.Resources.typeface);
	GUIConsole.GUI.closeButton.setTypeface(res.typeface || GUIConsole.Resources.typeface);
	GUIConsole.GUI.enterCommand.setTypeface(res.typeface || GUIConsole.Resources.typeface);
}

GUIConsole.GUI.showCommand = function(view){
	ui(function(){
		GUIConsole.GUI.commandPopup.showAtLocation(ctx.getWindow().getDecorView(), GUIConsole.GUI.Values.grav, GUIConsole.GUI.Values.xoff, GUIConsole.GUI.Values.yoff + (GUIConsole.GUI.extended ? 0 : GUIConsole.GUI.window.getHeight()));
	});
}

GUIConsole.GUI.updateWindow = function(){
	var winHeight = GUIConsole.GUI.extended ? GUIConsole.GUI.Values.ehgt : GUIConsole.GUI.Values.nhgt;
	var winY = GUIConsole.GUI.Values.yoff + (GUIConsole.GUI.extended ? GUIConsole.GUI.commandLayout.getHeight() - dp(5) : 0);
	
	var param = GUIConsole.GUI.input.getLayoutParams();
	param.gravity = android.view.Gravity.RIGHT | (GUIConsole.GUI.extended ? android.view.Gravity.TOP : android.view.Gravity.BOTTOM);
	GUIConsole.GUI.input.setLayoutParams(param);
	
	GUIConsole.GUI.window.update(GUIConsole.GUI.Values.xoff, winY, -1, GUIConsole.GUI.display.getHeight() * winHeight);
	GUIConsole.GUI.commandPopup.dismiss();
};

GUIConsole.GUI.onCommand = function(view){
	var cmd = GUIConsole.GUI.command.getText().toString() + "";
	if(cmd.length == 0) return;
	
	GUIConsole.GUI.command.setText("");
	GUIConsole.Console.append(fm(GUIConsole.Console.command, GUIConsole.Resources._pointerColor, GUIConsole.Resources._commandColor, cmd));
	
	GUIConsole.Callback.onSystemCommand(cmd);
}

GUIConsole.Console.set = function(newText){
	ui(function(){
		GUIConsole.GUI.log.setText(android.text.Html.fromHtml(newText));
	});
};

GUIConsole.Console.append = function(text){
	GUIConsole.Console.log.append("<br>");
	GUIConsole.Console.log.append(text);
	
	GUIConsole.Console.set(GUIConsole.Console.log.toString());
	
	dc(150, function(){
		GUIConsole.GUI.scroll.fullScroll(android.view.View.FOCUS_DOWN);
	});
}

GUIConsole.Console.clear = function(){
	GUIConsole.Console.log = new java.lang.StringBuffer(1024);
	GUIConsole.Console.log.append(fm(GUIConsole.Console.copyright, GUIConsole.Resources._highlightColor));
	
	GUIConsole.Console.set(GUIConsole.Console.log.toString());
}

/*********** IMPORT GUICONSOLE ***********/


/*********** FOR TEST ***********/
ui(function(){
	CustomPacket.out = GUIConsole.Console.append;
	GUIConsole.GUI.create();
});

function newLevel(){
	CustomPacket.init();
}

function leaveGame(){
	CustomPacket.finalize();
}

GUIConsole.Callback.onCommand = function(str){
    str = str.split(" ");
    var cmd = str.shift();
    
	switch(cmd){
	    case "send":
            var serverAddress = Server.getAddress();
            
            CustomPacket.out(fm("SENDING - IP: %s, PORT: %.0f", serverAddress, CustomPacket.PORT));
            CustomPacket.sendPacket(serverAddress, CustomPacket.PORT, str.join(" "));
            break;
	}
}

/*********** FOR TEST ***********/
