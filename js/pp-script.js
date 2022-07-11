//deal with mobile form entry and tab order for g form
if(document.querySelectorAll('.reg-practice input')){
	let regularFields = document.querySelectorAll('.reg-practice input, .reg-practice textarea');
	regularFields.forEach((field, index) => {
	  field.tabIndex = 1;
	});

	let altFields = document.querySelectorAll('.alt-practice input, .alt-practice textarea');
	altFields.forEach((field, index) => {
	  field.tabIndex = 2;
	});

}


//chart builder

document.addEventListener("DOMContentLoaded", function(){
	let entries = document.querySelectorAll('.accordion-item')
	var chartData = [
		['Date', 'Translation' , 'Other'],
	];

	entries.forEach((entry) => {
	  //console.log(entry.dataset.pdate)
	  let theDate = entry.dataset.pdate;
	  let entryPractice = Math.round(entry.dataset.practice);
	  let altPractice = Math.round(entry.dataset.alt);
	  let eventData = [theDate, entryPractice, altPractice];
	  chartData.push(eventData);
	  //console.log(chartData);
});


if(document.getElementById('chart')){
	google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
          chart: {
            title: 'Practice Log',
            //subtitle: 'Keep on track',
          },
          isStacked: true,
          series: {
		    0:{color:'#022543'},
		    1:{color:'#a7a7a7'},		   
		  }
        };

        var chart = new google.charts.Bar(document.getElementById('chart'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }

}

//fill form for editing
const editButtons = document.querySelectorAll('.edit-entry');
editButtons.forEach((button) => {
	button.addEventListener('click', function (event) {
//get data from previous submission	  
	  // Extract info from data-bs-* attributes
	  let practice = button.dataset.practice;
	  let altpractice = button.dataset.altpractice;
	  
	  let focus = button.dataset.focus;
	  let altfocus = button.dataset.altfocus;

	  let yea = button.dataset.yea;
	  let altyea = button.dataset.altyea;

	  let hmm = button.dataset.hmm;
	  let althmm = button.dataset.althmm;

	  // let strategy = button.dataset.strat;
	  // let altstrategy = button.dataset.altstrat;

	  let reflectLearn = button.dataset.strat;

	  let reflectBoxes = button.dataset.reflectselection;
	  console.log(reflectBoxes);

	  let entryid = button.dataset.entryid;	  		    


//modal selection
	  let formPractice = document.querySelector('#input_1_1');
	  let formAltPractice = document.querySelector('#input_1_7');
	  
	  let formFocus = document.querySelector('#input_1_3');
	  let formAltFocus = document.querySelector('#input_1_8');	

	  let formYea = document.querySelector('#input_1_4');
	  let formAltYea = document.querySelector('#input_1_9');

	  let formHmm = document.querySelector('#input_1_5');
	  let formAltHmm = document.querySelector('#input_1_10');	

	  // let formStrategy = document.querySelector('#input_1_6');
	  // let formAltStrategy = document.querySelector('#input_1_11');	
	
	  let formEntryId = document.querySelector('#input_1_15');  

//checkboxes
	  // let learningPattern = document.querySelector('#choice_1_17_1');
	  // let learningReflection = document.querySelector('#choice_1_17_2');	    
	  // let learningAssistance = document.querySelector('#choice_1_17_3');
	  // let learningRegulation = document.querySelector('#choice_1_17_4');	

	  formPractice.value = practice;
	  formAltPractice.value = altpractice;

	  formFocus.value = focus;
	  formAltFocus.value = altfocus;

	  formYea.value = yea;
	  formAltYea.value = altyea;	 

	  formHmm.value = hmm;
	  formAltHmm.value = althmm; 

	  if(reflectBoxes != ''){
		  		  pp_check_the_boxes(reflectBoxes);
		  }
	  

	  // formStrategy.value = strategy;
	  // formAltStrategy.value = altstrategy; 	
	  //document.querySelector('#choice_1_17_1').checked = true  

	  formEntryId.value = entryid;
	})

	})


});


//check the boxes
function pp_check_the_boxes(ids){
		ids = ids.split(', ');
		ids.forEach( function(id) {
			console.log(id);
			if(document.querySelector('#choice_1_17_'+id)){
				document.querySelector('#choice_1_17_'+id).checked = true;
				pp_show_box_field(id);
			}
		});
}

function pp_show_box_field(id){
	let realId = parseInt(id)+17;
	console.log(realId);
	document.querySelector('#field_1_'+realId).style = "display: block";
	document.querySelector('#input_1_'+realId).disabled = false;
}


jQuery('#logData').on('hidden.bs.modal', function () {
 location.reload();
})

const editButtons = document.querySelectorAll('.comment-entry');
editButtons.forEach((button) => {
	button.addEventListener('click', function (event) {
		let entryid = button.dataset.entryid;	 
		let comment = button.dataset.comment; 		    
		
		let formEntryId = document.querySelector('#input_4_2');  	     
	  let formComment = document.querySelector('#input_4_1');

	  formEntryId.value = entryid;
	  formComment.value = comment;
		})

	})


jQuery('#comment').on('hidden.bs.modal', function () {
 location.reload();
})
