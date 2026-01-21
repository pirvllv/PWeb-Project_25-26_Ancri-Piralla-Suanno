function aggiorna_info() {

    let fetchBody = new FormData();
    primkey = window.sessionData.username;
    fetchBody.append("action", "aggiorna");
    fetchBody.append("primkey", primkey);
    fetch("../backend/user_data_API.php", {
      method: "POST",
      body: fetchBody
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            window.location.reload();
        } else {
            console.log(data.message);
            logout();
        }
    })
    .catch(error => console.error('Errore:' + error));

}

function logout() {

    fetch("../backend/logout.php", {
        method: "POST",
        credential: "include"
    }).catch(error => console.error('Errore:' + error));
    window.location.href = "../index.php";
    
}