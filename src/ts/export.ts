function exportUsers(filename) {
  if (confirm('Are you sure you want to delete ' + filename + '?')) {
    window.location.replace('delete.php?name=' + filename);
  }
}