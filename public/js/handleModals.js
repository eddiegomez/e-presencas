// $('.participant_modal').on("click", function () {
//   $('#deleteParticipant').attr('action', $(this).data('delete-link'));
// });

document.addEventListener('DOMContentLoaded', function() {
    var myModal = document.getElementById('deleteParticipant');
    // var modalBodyContent = document.getElementById('modal-body-content');
  var modalForm = document.getElementById('deleteForm');
  
  myModal.addEventListener('show.modal', function (event) {
    console.log(event);
    var button = event.relatedTarget; // Button that triggered the modal
    var info = button.getAttribute('data-delete-link'); // Extract info from data-* attributes

    console.log(info);
    modalForm.innerHTML = info; // Populate the modal body
  });
});
