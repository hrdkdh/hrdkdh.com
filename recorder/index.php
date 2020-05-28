<?
if (!$_GET["pageNo"]) {
	$_GET["pageNo"]=1;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" id="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>오디오 레코더</title>
</head>

<body>
<script type="text/javascript" src="jquery-2.1.3.min.js"></script>
<script src="recorder.js"></script>

<style>
#recordingsList {
	margin:15px 0px;
}
#recordingsList li {
	list-style:none;
}
#dataWrap, #jsonResults, #recordBtn, #stopBtn {
	display:none;
}
#jsonResults {
	width:800px;
	height:600px;
}
#dataWrap {
	margin:15px 0px;
}
#pagerButtonsWrap {
	display:block;
	margin:10px 0px 0px 0px;
}
</style>

<script type="text/javascript">
var jsonArr={};
$(document).ready(function() {
	$("#initBtn").on("click",function(){
		try {
			// webkit shim
			window.AudioContext = window.AudioContext || window.webkitAudioContext;
			navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
			window.URL = window.URL || window.webkitURL;

			audio_context = new AudioContext;
			__log('Audio context set up.');
			__log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
		} catch (e) {
			alert('No web audio support in this browser!');
		}

		navigator.getUserMedia({audio: true}, startUserMedia, function(e) {
			__log('No live audio input: ' + e);
		});
		$.get("ajax.php?pagerNo=<?=$_GET["pageNo"]?>", function(results){
			var parsedData=JSON.parse(results);
			$("#text").text(parsedData.phase);
			$("#initBtn").css("display", "none");			
			$("#recordBtn, #stopBtn").css("display", "inline-block");			
			$("#dataWrap, #jsonResults").css("display", "block");			
		});
	});
	$("#nextBtn, #prevBtn").on("click",function(){
		$("#recordingsList").html("");
		var thisPageNo=$("#pageNo").text();
		var newPageNo=parseInt(thisPageNo)+1;
		console.log($(this).attr("id")+"_"+thisPageNo+"_"+newPageNo);
		if ($(this).attr("id")!="nextBtn") {
			newPageNo=thisPageNo-1;
			if (newPageNo<=0) {
				newPageNo=1;
			}
		}
		$.get("ajax.php?pagerNo="+newPageNo, function(results){
			var parsedData=JSON.parse(results);
			$("#pageNo").text(newPageNo);
			$("#text").text(parsedData.phase);
		});
	});
	$("#recordBtn").on("click",function(){
		startRecording(this);
	});
	$("#stopBtn").on("click",function(){
		stopRecording(this);
	});
	$(document).on("click", "#downloadLink", function(){
		if ($("#text").text()!="") {
			var keyName = "./datasets/hrdkdh/audio/"+$(this).text();
			jsonArr[keyName]=$("#text").text();
			var jsonStr=JSON.stringify(jsonArr);
			console.log(jsonArr);
			console.log(jsonStr);
			$("#jsonResults").val(jsonStr);
			$("#nextBtn").trigger("click");
		}
	});
	$("#jsonResults").on("click",function(){
		$(this).select();
		document.execCommand("copy");
	});
});

var audio_context;
var recorder;

function __log(e, data) {
	console.log(e + " " + (data || ''));
}

function startUserMedia(stream) {
	var input = audio_context.createMediaStreamSource(stream);
	__log('Media stream created.');

	// Uncomment if you want the audio to feedback directly
	//input.connect(audio_context.destination);
	//__log('Input connected to audio context destination.');

	recorder = new Recorder(input);
	__log('Recorder initialised.');
}

function startRecording(button) {
	recorder && recorder.record();
	button.disabled = true;
	button.nextElementSibling.disabled = false;
	__log('Recording...');
}

function stopRecording(button) {
	recorder && recorder.stop();
	button.disabled = true;
	button.previousElementSibling.disabled = false;
	__log('Stopped recording.');

	// create WAV download link using audio data blob
	createDownloadLink();

	recorder.clear();
}

function createDownloadLink() {
	recorder && recorder.exportWAV(function(blob) {
		var url = URL.createObjectURL(blob);
		var li = document.createElement('li');
		var au = document.createElement('audio');
		var hf = document.createElement('a');

		au.controls = true;
		au.src = url;
		hf.href = url;
		hf.download = "pagerNo_"+$("#pageNo").text()+".wav";
		hf.innerHTML = hf.download;
		li.appendChild(au);
		li.appendChild(hf);
		$("#recordingsList").html(li);
		$("#recordingsList").find("a").attr("id","downloadLink");
	});
}
</script>

<div id="dataWrap">
	<div id="pager">
		No. <span id="pageNo"><?=$_GET["pageNo"]?></span>
	</div>

	<div id="text"></div>

	<div id="pagerButtonsWrap">
		<button id="prevBtn">이전</button>
		<button id="nextBtn">다음</button>
	</div>

</div>

<button id="initBtn">시작하기</button>
<button id="recordBtn">녹음</button>
<button id="stopBtn">중지</button>

<div id="recordingsList"></div>

<textarea id="jsonResults">
	
</textarea>

</body>
</html>