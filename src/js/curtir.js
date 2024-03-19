const likeBtns = document.querySelectorAll(".like-btn");

likeBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    const movieId = btn.dataset.movieId;

    // Envia a solicitação para curtir o filme
    fetch(`../pages/filmes.php`, {
      method: "POST",
      body: JSON.stringify({ movieId }),
    })
      .then((response) => {
        if (response.status === 200) {
          // Atualize a interface para mostrar que o filme foi curtido
          btn.classList.add("liked");
        } else if (response.status === 409) {
          // Exiba uma mensagem informando que o usuário já curtiu o filme
          alert("Você já curtiu este filme!");
        } else {
          // Exiba uma mensagem de erro
          alert("Ocorreu um erro ao curtir o filme!");
        }
      });
  });
});
