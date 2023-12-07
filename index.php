<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>OnlyPDF</title>
  <link rel="stylesheet" href="/styles.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
</head>

<body>
  <nav class="navbar">
    <h1 class="nav-item">OnlyPDF - Your One-stop PDF solution</h1>
  </nav>
  <h1>Convert PDF to TXT file, or vice versa</h1>
  <form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="pdfFile[]" accept=".pdf" multiple />
    <button type="submit">Convert</button>
  </form>
  <?php
  if (isset($_FILES['pdfFile']['name'])) {
    // Loop over each submitted file
    foreach ($_FILES['pdfFile']['tmp_name'] as $i => $tmp_name) {
      $name = $_FILES['pdfFile']['name'][$i];
      // echo "Uploaded file name: " . $name . "<br>";

      // Define the paths for the input file, output directory, and output file
      $inputPath = "/var/www/html/input/";
      $outputPath = "/var/www/html/output/";

      $inputFile = $inputPath . $name;
      $outputFile = $outputPath . preg_replace('/\.[^.]+$/', '.txt', $name);

      // Move the uploaded file to the input directory
      move_uploaded_file($tmp_name, $inputFile);

      session_start();

      // Define the command to compile and execute the Java program
      $executeCommand = "java -cp /var/www/html/lib/pdfbox-app-3.0.1.jar:/var/www/html/bin/ ConvertPDF \"" . $inputFile . "\" \"" . $outputPath . "\"";
      // Execute the Java program
      exec($executeCommand);

      // Check if the conversion was successful
      if (file_exists($outputFile)) {
        // echo "File exists";

        // Get the filename with extension
        // $fileName = preg_replace('/\.[^.]+$/', '.txt', basename($name));

        // Set the headers to force the browser to download the file
        // header("Content-Type: application/txt");
        // header("Content-Disposition: attachment; filename=" . $fileName);

        // Read the file content into a variable
        // readfile($outputFile);
        $url = str_replace('/var/www/html', '', $outputFile);
        echo "<a href=\"" . $url . "\" download>Download " . basename($outputFile) . "</a><br>";
        // $fileContent = file_get_contents($outputFile);

        // Send the file content to the browser
        // echo $fileContent;

        // Register a shutdown function to delete the file
        // register_shutdown_function(function () use ($outputFile, $inputFile) {
        //   unlink($outputFile);
        //   // Unlink the input file
        //   unlink($inputFile);
        // });
      } else {
        // If the conversion failed, display an error message and delete the input file
        echo "Error converting PDF!";
        if (file_exists($inputFile)) {
          unlink($inputFile);
        }
      }
    }
  } else {
    echo "Please select a PDF file to upload.";
  }
  ?>
</body>

</html>