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
        let currentTheme = localStorage.getItem("theme") || "light";

        function applyTheme(theme) {
            if (theme === "dark") {
                document.body.classList.add("dark-mode");
            } else {
                document.body.classList.remove("dark-mode");
            }
            localStorage.setItem("theme", theme);
            currentTheme = theme;
        }

        function switchMode() {
            applyTheme(currentTheme === "dark" ? "light" : "dark");
        }

        document.addEventListener('DOMContentLoaded', function () {
            applyTheme(currentTheme);
        });
    </script>
    <script>

let currentLanguage = localStorage.getItem("language") || "nl";

function switchLanguage() {

    currentLanguage = currentLanguage === "nl" ? "en" : "nl";

    localStorage.setItem("language", currentLanguage);

    location.reload(); // THIS is required
}

function updateFlag() {

    const flag = document.getElementById('nl-flag');
    if (!flag) return;
    if (currentLanguage === "nl") {
        flag.src = "assets/nl-flag.svg";
        flag.alt = "NL flag";
    } else {
        flag.src = "assets/uk-flag.svg";
        flag.alt = "UK flag";
    }
}
document.addEventListener('DOMContentLoaded', updateFlag);
</script>