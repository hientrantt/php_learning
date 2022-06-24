<?php
function testInput($inputValue)
{
  $inputValue = trim($inputValue);
  $inputValue = stripslashes($inputValue);
  $inputValue = htmlspecialchars($inputValue);
  return $inputValue;
}
?>