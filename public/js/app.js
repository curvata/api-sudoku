let generate = document.querySelector('#generate');
let validate = document.querySelector('#validate');
let mode = document.querySelector('#mode');

generate.addEventListener('click', (e) => {
    e.preventDefault();

    let url = "api/v1/generate?"+"mode="+mode.value+"&many=1"

});

validate.addEventListener('click', (e) => {
  
});