const moviesCards = document.querySelectorAll(".cardMovie");
const moviesRated = document.querySelectorAll(".movie-rated");
const inputUsername = document.getElementById("username");
const emailWrong = document.getElementById("testEmail");
const loginSubmit = document.getElementById("loginSubmit");
const homenavBar = document.getElementById("homeNavBar");
const profilenavBar = document.getElementById("profileNavBar");
regxp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

moviesCards.forEach(card => {
    card.addEventListener("click", (e) => {
        document.location.href=`../src/detailsMovie.php?id=${e.target.id}`; 
    })
});

moviesRated.forEach(movie => {
    movie.addEventListener("click", (e) => {
        document.location.href=`../src/detailsMovie.php?id=${e.target.id}`; 
    })
});
if(inputUsername){
    inputUsername.addEventListener("input", (e) => {
        if(regxp.test(e.target.value) || e.target.value == ""){
            emailWrong.classList.add("hidden");
            loginSubmit.disabled = false;
        }else{
            emailWrong.classList.remove("hidden");
            loginSubmit.disabled = true;
            
        }
    })
}

if(homenavBar){

    if(document.location.href.includes("Home.php")){
        homenavBar.classList.add("active");
        profilenavBar.classList.remove("active");
    }else{
        profilenavBar.classList.add("active");
        homenavBar.classList.remove("active");
    }
}