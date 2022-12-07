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
			  		},
					titlePosition: 'in', 
					axisTitlesPosition: 'in',
					hAxis: {
						textPosition: 'out',
						title: 'Dates',
						slantedText: true,
						slantedTextAngle: 45,
					}, 
					vAxis: {
						textPosition: 'in'
					},			  
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
	  let type = button.dataset.type;
	  let practice = button.dataset.practice;
	  let altpractice = button.dataset.altpractice;
	  
	  let emotion = button.dataset.satisfaction;
	  let altEmotion = button.dataset.altsatisfaction;

	  let parallel = button.dataset.parallel;
	 
	  let consecFocus = button.dataset.consecutive;
	  let simalFocus = button.dataset.simal;

	  let listening = button.dataset.listening;//*
	  let deverb = button.dataset.deverb;//
	  let notes = button.dataset.notes;//
	  let reexpress = button.dataset.reexpress;//*
	  let delivery = button.dataset.delivery;//*
	  let other = button.dataset.other;//
	  let evs = button.dataset.evs;//*
	  let multitask = button.dataset.multitask;//*
	  let share = button.dataset.share;
	  let detail = button.dataset.detail;
	  // let reflectLearn = button.dataset.strat;

	  // let reflectBoxes = button.dataset.reflectselection;

	  let entryid = button.dataset.entryid;	  		    


//modal selection
	  let formPractice = document.querySelector('#input_1_1');
	  let formAltPractice = document.querySelector('#input_1_7');
	  
	  pp_radio_check('#input_1_22', type);

	  pp_radio_check('#input_1_23', emotion);
	  pp_radio_check('#input_1_24', altEmotion);

	  pp_radio_check('#input_1_32', parallel);

	  pp_hide_show(type);

	  pp_checkbox_check('#field_1_17', consecFocus);
	  pp_checkbox_check('#field_1_25', simalFocus);
	  pp_checkbox_check('#field_1_34', share);
//get the form fields
	  let formListening = document.querySelector('#input_1_18');
  	let formDeverb = document.querySelector('#input_1_21');	  
  	let formNotes = document.querySelector('#input_1_26');
  	let formReexpress = document.querySelector('#input_1_27');	
  	let formDelivery = document.querySelector('#input_1_28');	
  	let formOther = document.querySelector('#input_1_30');	
  	let formEvs = document.querySelector('#input_1_29');	
  	let formMultitask = document.querySelector('#input_1_19');	
  	let formDetail = document.querySelector('#input_1_35');	
	
	  let formEntryId = document.querySelector('#input_1_15');  

//set the form field values
	  formPractice.value = practice;
	  formAltPractice.value = altpractice;

	  pp_optional_reflect(listening, formListening);
	  pp_optional_reflect(deverb, formDeverb);
	  pp_optional_reflect(notes, formNotes);
	  pp_optional_reflect(reexpress, formReexpress);
	  pp_optional_reflect(delivery, formDelivery);
	  pp_optional_reflect(other, formOther);
	  pp_optional_reflect(evs, formEvs);
	  pp_optional_reflect(multitask, formMultitask);
	  pp_optional_reflect(detail, formDetail);

	  formEntryId.value = entryid;
	})

	})


});

function modalStudentComments(btnSrc){
	const destination = document.querySelector('#student-input');
	console.log(btnSrc);
	let allComments = [];
	let listening = btnSrc.dataset.listening;//*
  let deverb = btnSrc.dataset.deverb;//
  let notes = btnSrc.dataset.notes;//
  let reexpress = btnSrc.dataset.reexpress;//*
  let delivery = btnSrc.dataset.delivery;//*
  let other = btnSrc.dataset.other;//
  let evs = btnSrc.dataset.evs;//*
  let multitask = btnSrc.dataset.multitask;//*
  let share = btnSrc.dataset.share;
  let detail = btnSrc.dataset.detail;
  allComments.push(listening,deverb,notes,reexpress,delivery,other,evs,multitask,share,detail);
  let comments = condenseStuComments(allComments)
  destination.innerHTML = destination.innerHTML + comments;
}

function condenseStuComments(allComments){
	let html = '';
	allComments.forEach((element) => {
			if(element != ''){
				html = html + `<p>${element}</p>`
		}
  
	});
	return html;
	
}

//optional reflections
function pp_optional_reflect(value, element){
	 if(value){
	  	element.value = value;
	  	element.disabled = false;
	  	element.parentNode.parentNode.style.display = 'block'	
	  }
}

//radio buttons
function pp_radio_check(fieldId, selectedValue){
	const formEmotion = document.querySelector(fieldId);
	const options = formEmotion.querySelectorAll('input');

	options.forEach((option) => {
	  if(option.value == selectedValue){
	    //console.log(emotion)
	    option.checked = true
	  }
	});
}

function pp_checkbox_check(fieldId, selectedChoices){
	const selectedArray = selectedChoices.split(', ');
	const field = document.querySelector(fieldId);
	const options = field.querySelectorAll('input');

	options.forEach((option) => {
	  if(selectedArray.includes(option.value)){
	    //console.log(emotion)
	    option.checked = true
	  }
	});
}


//manual hide show based on type
function pp_hide_show(type){
	if(type == 'Consecutive Interpretation'){
		let field = document.querySelector('#field_1_17');
		field.style.display = 'block';
		pp_undisable(field);
	}
	if(type == 'Simultaneous Interpretation'){
		let field = document.querySelector('#field_1_25');
		field.style.display = 'block';
		pp_undisable(field);
	}
}

//undisable 
function pp_undisable(field){
	let disabled = field.querySelectorAll(':disabled')
		disabled.forEach(function(item){
			item.disabled = false
		})
}



jQuery('#logData').on('hidden.bs.modal', function () {
 location.reload();
})

const editButtons = document.querySelectorAll('.comment-entry');
editButtons.forEach((button) => {
	button.addEventListener('click', function (event) {
		let entryid = button.dataset.entryid;	 
		let comment = button.dataset.comment; 		    
		let buttonSrc = document.querySelector('#btn-'+entryid);
		let formEntryId = document.querySelector('#input_4_2');  	     
	  let formComment = document.querySelector('#input_4_1');

	  formEntryId.value = entryid;
	  formComment.value = comment;
	  modalStudentComments(buttonSrc);
		})

	})


jQuery('#comment').on('hidden.bs.modal', function () {
 location.reload();
})

jQuery('#profile').on('hidden.bs.modal', function () {
 location.reload();
})
