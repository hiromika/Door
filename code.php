<div class="container">
<div class="row">
		<div class="col-md-5">
			
		</div>
		<div class="col-md-2 text-center">
		<h3>Generate Code</h3>
		<div style="margin:20px;">
			<h1 id="code" style="background-color: #e0e0eb;"></h1>
		</div>
		<div>
			<h3 id="time"></h3>
		</div>
		<a href="javascript:void(0)" class="btn btn-info" id="btn" onclick="get_code()" title="">Get Code</a>
		</div>
</div>	
</div>

<script type="text/javascript">
	
	function get_code() {
		$.ajax({
			url: 'proses_link.php?action=get_code',
			type: 'POST',
			dataType: 'json',
			success:function(data){
				$("#code").text(data.data);
				countdown(1);
				$("#btn").addClass('disabled');
				function countdown(minutes) {
					
				    var seconds = 60;
				    var mins = minutes
				    function tick() {
				        //This script expects an element with an ID = "counter". You can change that to what ever you want. 
				        var counter = document.getElementById("time");
				        var current_minutes = mins-1;
				        seconds--;
				        counter.innerHTML = current_minutes.toString() + ":" + (seconds < 10 ? "0" : "") + String(seconds);
				        if(current_minutes == 0 && seconds == 0){
				        	$("#btn").removeClass('disabled');
				        }

				        if(seconds > 0 ) {
				        	
				            setTimeout(tick, 1000);
				        } else {
				            if(mins > 1){
				                countdown(mins-1);           
				            }
				        }

				    }
				    tick();
				   
				}

			}
		
	});
}

</script>