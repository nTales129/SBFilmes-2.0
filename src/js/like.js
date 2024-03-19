const likeBtns = document.querySelectorAll('.like-btn');

likeBtns.forEach(btn => {
   btn.addEventListener('click', () => {
      let icon = btn.firstElementChild;
      if(icon.classList.contains('fa-regular')) {
         icon.classList.remove('fa-regular');
         icon.classList.add('fa-solid');
      } else {
         icon.classList.remove('fa-solid');
         icon.classList.add('fa-regular');
      }
   })
})