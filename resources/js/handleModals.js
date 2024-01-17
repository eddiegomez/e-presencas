$('.participant_modal').on("click", function () {
  $('#deleteParticipant').attr('action', $(this).data('delete-link'));
});