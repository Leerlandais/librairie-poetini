console.log("button flash active")
const librelButton = document.getElementById("librelButton");
console.log(librelButton);

let buttonFlash = setInterval(() => {
    librelButton.classList.add ("flashButton");
    setTimeout(() => {
        librelButton.classList.remove("flashButton");
    }, 1500);
}, 3000);