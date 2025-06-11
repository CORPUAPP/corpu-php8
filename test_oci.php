<?php
$conn = oci_connect('CORPU', 'admin', 'bahia17svm02:1521/BAHIA02');
if (!$conn) {
  $e = oci_error();
  echo "❌ Error: " . $e['message'];
} else {
  echo "✅ Conexión correcta";
}
?>
