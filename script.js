document.addEventListener("DOMContentLoaded", function () {
  const checkboxes = document.querySelectorAll(".checkbox");
  const submitBtn = document.getElementById("submitBtn");

  function updateButtonStatus() {
    // Prüft, ob alle Checkboxen markiert sind
    const allChecked = Array.from(checkboxes).every(
      (checkbox) => checkbox.checked
    );
    submitBtn.disabled = !allChecked;
  }

  // Event-Listener für jede Checkbox
  checkboxes.forEach((checkbox) =>
    checkbox.addEventListener("change", updateButtonStatus)
  );

  // Initialer Aufruf zur Überprüfung des Button-Status
  updateButtonStatus();
});
