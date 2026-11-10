let imgPosition = 0;
let viewerTimer = null;
let viewerPaused = false;
const timeScrolling = 4000;

function stopViewer() {
    if (viewerTimer !== null) {
        window.clearInterval(viewerTimer);
        viewerTimer = null;
    }
}

function displayImg(imgNumber) {
    const slider = document.getElementById("slider");
    if (slider) {
        slider.style.transform = "translateX(-" + (imgNumber * 100) + "%)";
    }
}

function selectViewerImage(selectedIndex) {
    const selectors = document.querySelectorAll(".selectorViewer");
    const images = document.querySelectorAll("#slider > img");
    selectors.forEach(function (selector, index) {
        const isSelected = index === selectedIndex;
        selector.setAttribute("aria-pressed", String(isSelected));
        selector.classList.toggle("is-active", isSelected);
    });
    images.forEach(function (image, index) {
        image.setAttribute("aria-hidden", String(index !== selectedIndex));
    });

    imgPosition = selectedIndex;
    displayImg(imgPosition);
}

function scrollImg(imgNumber) {
    stopViewer();
    selectViewerImage(imgNumber);
    if (!viewerPaused && document.querySelectorAll(".selectorViewer").length > 1) {
        viewerTimer = window.setInterval(myTimerScrollImg, timeScrolling);
    }
}

function toggleViewerPlayback(button) {
    viewerPaused = !viewerPaused;
    stopViewer();
    button.textContent = viewerPaused ? "Reprendre le diaporama" : "Mettre en pause";

    if (!viewerPaused && document.querySelectorAll(".selectorViewer").length > 1) {
        viewerTimer = window.setInterval(myTimerScrollImg, timeScrolling);
    }
}

function myTimerScrollImg() {
    const imageCount = document.querySelectorAll(".selectorViewer").length;
    if (imageCount < 2) {
        stopViewer();
        return;
    }

    selectViewerImage((imgPosition + 1) % imageCount);
}

function loadImg(path, targetContainer) {
    if (!targetContainer) {
        console.error("Le conteneur du diaporama est introuvable.");
        return;
    }

    stopViewer();
    imgPosition = 0;
    viewerPaused = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    targetContainer.replaceChildren();

    const xhr = new XMLHttpRequest();
    xhr.onload = function () {
        const xml = xhr.responseXML;
        if (xhr.status < 200 || xhr.status >= 300 || !xml) {
            targetContainer.textContent = "Le diaporama n’a pas pu être chargé.";
            console.error("Réponse XML invalide lors du chargement du diaporama.");
            return;
        }

        const images = Array.from(xml.getElementsByTagName("image"));
        if (images.length === 0) {
            targetContainer.textContent = "Aucune image disponible.";
            return;
        }

        const containerSlider = document.createElement("div");
        containerSlider.id = "containerSlider";
        containerSlider.setAttribute("aria-label", "Diaporama de photos");

        const slider = document.createElement("div");
        slider.id = "slider";
        containerSlider.appendChild(slider);
        targetContainer.appendChild(containerSlider);

        const controls = document.createElement("div");
        controls.id = "scroll";
        controls.setAttribute("role", "group");
        controls.setAttribute("aria-label", "Commandes du diaporama");
        targetContainer.appendChild(controls);

        images.forEach(function (imageData, index) {
            const srcNode = imageData.getElementsByTagName("src")[0];
            const altNode = imageData.getElementsByTagName("alt")[0];
            if (!srcNode || !srcNode.textContent.trim()) {
                return;
            }

            const image = document.createElement("img");
            image.src = srcNode.textContent.trim();
            image.alt = altNode ? altNode.textContent.trim() : "";
            image.width = 480;
            image.height = 240;
            image.loading = index === 0 ? "eager" : "lazy";
            image.decoding = "async";
            image.setAttribute("aria-hidden", String(index !== 0));
            slider.appendChild(image);

            const selector = document.createElement("button");
            selector.type = "button";
            selector.className = "selectorViewer";
            selector.setAttribute("aria-label", "Afficher l’image " + (index + 1));
            selector.setAttribute("aria-pressed", String(index === 0));
            selector.classList.toggle("is-active", index === 0);
            selector.addEventListener("click", function () {
                scrollImg(index);
            });
            controls.appendChild(selector);
        });

        if (controls.children.length > 1) {
            const playbackButton = document.createElement("button");
            playbackButton.type = "button";
            playbackButton.className = "viewer-playback";
            playbackButton.textContent = viewerPaused
                ? "Reprendre le diaporama"
                : "Mettre en pause";
            playbackButton.addEventListener("click", function () {
                toggleViewerPlayback(playbackButton);
            });
            controls.appendChild(playbackButton);
        }

        if (!viewerPaused && controls.querySelectorAll(".selectorViewer").length > 1) {
            viewerTimer = window.setInterval(myTimerScrollImg, timeScrolling);
        }
    };
    xhr.onerror = function () {
        targetContainer.textContent = "Erreur réseau : le diaporama n’a pas pu être chargé.";
        console.error("Erreur réseau lors du chargement du XML du diaporama.");
    };
    xhr.open("GET", path, true);
    xhr.send();
}
