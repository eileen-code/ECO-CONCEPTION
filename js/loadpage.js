let currentPageRequest;

window.addEventListener("DOMContentLoaded", function () {
    const requestedPage = new URLSearchParams(window.location.search).get("page");
    const initialLink = getPageLink(requestedPage)
        || document.querySelector("nav a[aria-current='page']")
        || document.querySelector("nav a");

    if (initialLink) {
        loadPage(initialLink);
    }
});

window.addEventListener("popstate", function () {
    const pageName = new URLSearchParams(window.location.search).get("page");
    const link = getPageLink(pageName)
        || document.querySelector("nav a[href='?page=Accueil']");
    if (link) {
        loadPage(link);
    }
});

function getPageLink(pageName) {
    if (!pageName) {
        return null;
    }

    return Array.from(document.querySelectorAll("nav a")).find(function (link) {
        return link.textContent.trim() === pageName;
    }) || null;
}

function loadPage(link, event) {
    if (event) {
        event.preventDefault();
    }

    const pageName = link.textContent.trim();
    const content = document.getElementById("contenu");
    const subtitle = document.getElementById("soustitre");

    if (!content || !subtitle) {
        console.error("La zone de contenu ou le titre de page est introuvable.");
        return;
    }

    if (typeof stopViewer === "function") {
        stopViewer();
    }

    document.querySelectorAll("nav a").forEach(function (navLink) {
        navLink.removeAttribute("aria-current");
    });
    link.setAttribute("aria-current", "page");
    if (event && window.location.search !== link.search) {
        window.history.pushState({ page: pageName }, "", link.href);
    }
    subtitle.textContent = pageName;
    content.textContent = "Chargement…";

    if (currentPageRequest) {
        currentPageRequest.abort();
    }

    const request = new XMLHttpRequest();
    currentPageRequest = request;
    request.onload = function () {
        if (currentPageRequest !== request
            || link.getAttribute("aria-current") !== "page") {
            return;
        }

        if (this.status < 200 || this.status >= 300) {
            content.textContent = "La page n’a pas pu être chargée.";
            return;
        }

        // Le routeur n’accepte que des pages connues ; les données PHP sont échappées.
        content.innerHTML = this.responseText;
        const viewer = content.querySelector("#viewer");
        if (viewer && typeof loadImg === "function") {
            loadImg("./viewer/imgViewer.xml", viewer);
        }
    };
    request.onerror = function () {
        if (currentPageRequest !== request) {
            return;
        }
        content.textContent = "Erreur réseau : la page n’a pas pu être chargée.";
    };
    request.open(
        "GET",
        "./php/routage.php?page=" + encodeURIComponent(pageName),
        true
    );
    request.send();
}
