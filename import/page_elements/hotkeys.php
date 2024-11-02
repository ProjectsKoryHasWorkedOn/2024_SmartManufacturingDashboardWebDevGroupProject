<?php
$employee_role = $_SESSION['employee_role'];
echo "<script>var employeeRole = '{$employee_role}';</script>";
?>
<script>
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "d") {
      event.preventDefault();
      window.location.href = 'dashboard.php';
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "m") {
      event.preventDefault();
      window.location.href = 'machine_status.php';
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "j") {
      event.preventDefault();
      if (employeeRole == "production operator") {
        window.location.href = 'jobs_to_do.php';
      }
      else if (employeeRole == "factory manager") {
        window.location.href = 'job_notes.php';
      }
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "o") {
      event.preventDefault();
      window.location.href = 'manage_inventory.php';
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "a") {
      event.preventDefault();
      window.location.href = 'insert_records.php';
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "c") {
      event.preventDefault();
      window.location.href = 'manage_records.php';
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "r") {
      event.preventDefault();
      window.location.href = 'report_generator.php';
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "i") {
      event.preventDefault();
      window.location.href = 'inbox.php';
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "w") {
      event.preventDefault();
      window.location.href = 'start_stop_working.php';
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.altKey && event.key === "u") {
      event.preventDefault();
      window.location.href = 'site_user_guide.php';
    }
  });
</script>