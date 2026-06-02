<header>
    <div class="header-row">
        <div class="header-column1">
        <img src="assets/logo_white.svg" id="logo">
        <h2>FESTIVAL</h2>
        </div>
        <div class="header-column2">
            <img src="assets/dark-light-icon.png" onclick="switchMode()" id="dark-light-icon">
            <img src="assets/nl-flag.svg" id="nl-flag" onclick="switchLanguage()">
        </div>
    </div>
    </header>

    <script>
        function switchMode(){
            var element = document.body;
            element.classList.toggle("dark-mode");
        };
        
    </script>
    <script>

let currentLanguage = localStorage.getItem("language") || "nl";

function switchLanguage() {

    currentLanguage = currentLanguage === "nl" ? "en" : "nl";

    localStorage.setItem("language", currentLanguage);

    location.reload(); // THIS is required
}

function updateFlag() {

    if(currentLanguage === "nl") {
        flag.src = "assets/nl-flag.svg";
    } else {
        flag.src = "assets/uk-flag.svg";
    }
}
</script>