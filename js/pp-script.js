console.log('working pp form')

let regularFields = document.querySelectorAll('.reg-practice input, .reg-practice textarea');
regularFields.forEach((field, index) => {
  field.tabIndex = 1;
});

let altFields = document.querySelectorAll('.alt-practice input, .alt-practice textarea');
altFields.forEach((field, index) => {
  field.tabIndex = 2;
});