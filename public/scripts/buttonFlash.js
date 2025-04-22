const librelButton = document.getElementById("librelButton");

let buttonFlash = startFlashing();

function startFlashing() {
    return setInterval(() => {
        librelButton.classList.add("flashButton");
        setTimeout(() => {
            librelButton.classList.remove("flashButton");
        }, 3500);
    }, 6000);
}

let isPaused = false;

librelButton.addEventListener("mouseover", function(event) {
    if (!isPaused) {
        clearInterval(buttonFlash);
        isPaused = true;
    }
});

librelButton.addEventListener("mouseout", function(event) {
    if (isPaused) {
        buttonFlash = startFlashing();
        isPaused = false;
    }
});




    document.querySelectorAll('.track-click').forEach(function (link) {
        link.addEventListener('click', function (e) {
            const linkId = this.dataset.linkId;

            navigator.sendBeacon('?route=track-click', new URLSearchParams({ id: linkId }));

        });
    });

