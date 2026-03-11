function setSearch(type, element){

    const searchBar = document.getElementById("searchBar");

    if(type === "book"){
        searchBar.placeholder = "Enter ISBN or author name here..";
    } else {
        searchBar.placeholder = "Enter supply name here..";
    }

    document.querySelectorAll(".category").forEach(cat => {
        cat.classList.remove("active");
    });

    element.classList.add("active");
}