let generate = document.querySelector('#generate');
let validate = document.querySelector('#validate');
let mode = document.querySelector('#mode');

generate.addEventListener('click', (e) => {
    e.preventDefault();

    let url = "api/v1/generate?"+"mode="+mode.value+"&many=1"

    let promise = fetch(url);

    promise.then((response) => {
        return response.json();
    }).then((response) => {
        let region = "A";
        let regions = [];
        let data = response.data;
    
        for (a=1; a<=9; a++) {
            for (b=1; b<=9; b++) {
                let input = document.querySelector('#'+region+'I'+b);
                input.value = "";
                regions.push(document.querySelector('#'+region+'I'+b));
            } 
            switch (a) {
                case 1: region = "B";break;
                case 2: region = "C";break;
                case 3: region = "D";break;
                case 4: region = "E";break;
                case 5: region = "F";break;
                case 6: region = "G";break;
                case 7: region = "H";break;
                case 8: region = "I";break;
            } 
        }

        let n = [];

        data[0].forEach(elem => {
            elem.forEach((value, index) => {
                n.push(value);
            });
        });

        regions.forEach((elem, index) => {
            if (n[index] != "*") {
                elem.value = n[index];
                elem.setAttribute("readonly", "readonly");
            }
        });
    }).catch(function(error) {
        console.log('Il y a eu un problème');
      });
});

validate.addEventListener('click', (e) => {
    e.preventDefault();

    let region = "A";
    let sudoku = [];
    
    for (a=1; a<=9; a++) {
        for (b=1; b<=9; b++) {
            let input = document.querySelector('#'+region+'I'+b);
            sudoku.push(input.value);
        } 
        switch (a) {
            case 1: region = "B";break;
            case 2: region = "C";break;
            case 3: region = "D";break;
            case 4: region = "E";break;
            case 5: region = "F";break;
            case 6: region = "G";break;
            case 7: region = "H";break;
            case 8: region = "I";break;
        } 
    }

    let sudokuF = chunkArray(sudoku,9);

    let url = "api/v1/validate"
    
    let promise = fetch(url, {
        method: "POST",
        body: JSON.stringify([sudokuF])
    }); 

    promise.then((response) => { return response.json(); })
    .then(response => { 
        let content = document.querySelector(".mdp_content");

        if (response.success === true && response.data[0] === true) {
            content.innerHTML = "Bravo vous avez réussi votre grille 👏"
        } else {
            content.innerHTML = "Votre grille comporte des erreurs 🙄";
        }

        let modal = document.querySelector("#modal");
        let span = document.getElementsByClassName("close")[0];

        modal.style.display = "flex";

        span.onclick = function() {
          modal.style.display = "none";
        }

        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
    });
});

function chunkArray(myArray, chunk_size){
    var index = 0;
    var arrayLength = myArray.length;
    var tempArray = [];
    
    for (index = 0; index < arrayLength; index += chunk_size) {
        myChunk = myArray.slice(index, index+chunk_size);
        tempArray.push(myChunk);
    }

    return tempArray;
}