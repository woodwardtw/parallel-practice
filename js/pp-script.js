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
if(document.getElementById('chart')){
	google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Date', 'Practice', 'Alt Practice'],
          ['2014', 1000, 400],
          ['2015', 1170, 460],
          ['2016', 660, 112],
          ['2017', 1030, 540]
        ]);

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

let entries = document.querySelectorAll('.accordion-item')
var chartData = [
	['Date', 'Practice' , 'Alt Practices']
];

entries.forEach((entry) => {
  console.log(entry.dataset.pdate)
  let theDate = entry.datatset.pdate;
  let entryPractice = entry.dataset.practice;
  let altPractice = entry.dataset.alt;
  let eventData = [theDate, entryPractice, altPractice];
  chartData.push(eventData);
  console.log(chartData);
});
