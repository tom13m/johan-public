<h1> Barcode scanner </h1>

<button onclick="start()"> Start </button>

<button onclick="stop()"> Stop </button>

<div id="yourElement">  </div>

<script>
	function start() {
		Quagga.init({
			inputStream : {
			  name : "Live",
			  type : "LiveStream",
			  target: document.querySelector('#yourElement')    // Or '#yourElement' (optional)
			},
			decoder : {
//			  readers : ["code_128_reader"]
			  readers : ["ean_reader"]
			}
		  }, function(err) {
			  if (err) {
				  console.log(err);
				  return
			  }
			  console.log("Initialization finished. Ready to start");
			
			Quagga.start();
		});
		
		Quagga.onDetected(function(result) {	
			Quagga.stop();
			
			console.log(result.codeResult.code);
		});
	}
	
	function stop() {
		Quagga.stop();
	}
	
	/* Function for adding products to database */
//	function add() {
//		fetch("<?php /*$this->Url->build(['controller' => 'test', 'action' => 'add']);*/?>", {
//            method: 'POST',
//            headers: {
//				"Content-Type": "application/json"
////                "X-CSRF-Token": <?= json_encode($this->request->getAttribute('csrfToken')); ?>
//            },
//                body: JSON.stringify({
//				    test: "test"
//				})
//			})
//            .then((res) =>
//				res.json())
//            .then((data) => {
//            
////                console.warn(data);
//                let response = data.response;
//
//                if (response.success == 1) {
//             
//                } else if (response.success == 0) {
//                
//                }
//            })
//				.catch((err) => console.log(err))
//	}
</script>