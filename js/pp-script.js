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
		['Date', 'Practice' , 'Alt Practices'],
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
            subtitle: 'Keep on track',
          },
          isStacked: true,
        };

        var chart = new google.charts.Bar(document.getElementById('chart'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }

}

const editButtons = document.querySelectorAll('.edit-entry');
editButtons.forEach((button) => {
	button.addEventListener('click', function (event) {
		console.log(event)
	  // Extract info from data-bs-* attributes
	  let practice = button.dataset.practice;
	  let altpractice = button.dataset.altpractice;
	  
	  let focus = button.dataset.focus;
	  let altfocus = button.dataset.altfocus;

	  let formPractice = document.querySelector('#input_3_1');
	  let formAltPractice = document.querySelector('#input_3_7');
	  
	  let formFocus = document.querySelector('#input_3_3');
	  let formAltFocus = document.querySelector('#input_3_8');	  
	  
	  formPractice.value = practice;
	  formAltPractice.value = altpractice;

	  formFocus.value = focus;
	  formAltFocus.value = altfocus;

	})

})


});





