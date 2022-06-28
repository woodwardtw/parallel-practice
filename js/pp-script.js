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
		    1:{color:'#0d395f'},		   
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
	  // Extract info from data-bs-* attributes
	  let practice = button.dataset.practice;
	  let altpractice = button.dataset.altpractice;
	  
	  let focus = button.dataset.focus;
	  let altfocus = button.dataset.altfocus;

	  let yea = button.dataset.yea;
	  let altyea = button.dataset.altyea;

	  let hmm = button.dataset.hmm;
	  let althmm = button.dataset.althmm;

	  let strategy = button.dataset.strat;
	  let altstrategy = button.dataset.altstrat;

	  let entryid = button.dataset.entryid;	  		    

	  let formPractice = document.querySelector('#input_1_1');
	  let formAltPractice = document.querySelector('#input_1_7');
	  
	  let formFocus = document.querySelector('#input_1_3');
	  let formAltFocus = document.querySelector('#input_1_8');	

	  let formYea = document.querySelector('#input_1_4');
	  let formAltYea = document.querySelector('#input_1_9');

	  let formHmm = document.querySelector('#input_1_5');
	  let formAltHmm = document.querySelector('#input_1_10');	

	  let formStrategy = document.querySelector('#input_1_6');
	  let formAltStrategy = document.querySelector('#input_1_11');	

	  let formEntryId = document.querySelector('#input_1_15');  	     
	  
	  formPractice.value = practice;
	  formAltPractice.value = altpractice;

	  formFocus.value = focus;
	  formAltFocus.value = altfocus;

	  formYea.value = yea;
	  formAltYea.value = altyea;	 

	  formHmm.value = hmm;
	  formAltHmm.value = althmm; 

	  formStrategy.value = strategy;
	  formAltStrategy.value = altstrategy; 	  

	  formEntryId.value = entryid;
	})

	})


});



jQuery('#logData').on('hidden.bs.modal', function () {
 location.reload();
})

