console.log("here");

function addMovie(movieID) {
    window.location.replace("./index.php?action=add&movie_id=" + movieID);
    return true;
}

function confirmCheckout() {
    let answer = confirm("Do you wish to checkout?");
    if (!answer) {
        return false;
    } else {
        window.location.replace("./index.php?action=checkout");
        return true;
    }
}

function confirmLogout() {
   let logout = confirm("Do you wish to logout?");
   if (!logout) {
       console.log("heree");
       return false;
   } else {
       console.log("here?")
       window.location.replace("./logon.php?action=logoff");
       return true;
   }
}

function confirmRemove(title, movieID) {
    let remove = confirm(`Do you wish to remove ${title} from your cart?`);
    if (!remove) {
        return false;
    } else {
        window.location.replace("./index.php?action=remove&movie_id=" + movieID);
        return true;
    }
}