   <?php
   // Serve the requested resource as-is if it exists
   if (file_exists(__DIR__ . $_SERVER['REQUEST_URI'])) {
       return false;
   }
   // Custom routing logic
   if ($_SERVER[ 'REQUEST_URI' ] === '/calculate-discount') {
       require __DIR__.'/calculate_discount.php';
   } else {
       http_response_code(404);
       echo 'Not Found';
   }