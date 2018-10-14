<?PHP
if ( ! empty ( $_FILES['file']['name'] ) )
{
  if ( @ copy ( $_FILES['file']['tmp_name'],
                'tmp/' . $_FILES['file']['name'] ) )
  {
    echo '<b>Upload beendet!</b><br>';

    echo 'Dateiname: ' . $_FILES['file']['name'] . '<br>';

    echo 'Dateigröße: ' . $_FILES['file']['size'] . 'Byte';
  }    
}
else
{
?>

<html>
  <head>
  </head>
  <body>
    <form action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post"
          enctype="multipart/form-data">
      <input type="file" name="file" value="" />
      <br />
      <input type="submit" name="Abschicken" value="Upload beginnen" />
    </form>
  </body>
</html>

<?PHP
}
?>