function myFunction() {
  // The code below logs the HTML code of the Google home page.
  var response = UrlFetchApp.fetch("http://YOUR-AWESOME-WEBSITE.com/RgbWeatherService/ExecuteColorChange.php?caller=google");
  Logger.log(response.getContentText());
  
}