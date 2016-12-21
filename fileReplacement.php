<?php

global $wp_filesystem;

class FileReplacement {

  private $specified;
  public $temp_name;
  public $name;
  public $size;

  function __construct(){
    $this->specified = $this->specified_in_upload() || $this->specified_in_url();
  }

  private function specified_in_upload() {
    if(!is_uploaded_file($_FILES["userfile"]["tmp_name"]))
      return false;

    $this->temp_name = $_FILES["userfile"]["tmp_name"];
    $this->name = $_FILES["userfile"]["name"];
    $this->size = $_FILES["userfile"]["size"];
    return true;
  }

  private function specified_in_url() {
    $url = $_POST['userurl'];
    if(empty($url))
      return false;

    $url_parts = parse_url($url);
    $this->name = basename($url_parts['path']);
    $this->temp_name = wp_tempnam($this->name);
    @chmod($this->temp_name, 0777);

    $data = file_get_contents($url);
    file_put_contents($this->temp_name, $data);
    $this->size = filesize($this->temp_name);

    return true;
  }

  function is_specified() {
    return $this->specified;
  }
}

?>
