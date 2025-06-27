function showApi(position) {
    let xhr = new XMLHttpRequest();
    let latitude = position.coords.latitude;
    let longitude = position.coords.longitude;
  
    xhr.open('GET', 'https://nominatim.openstreetmap.org/reverse.php?lat='+latitude+'&lon='+longitude+'&zoom=18&format=jsonv2');
    xhr.onload = function() {
      let data = JSON.parse(xhr.response);
      console.log(data);
    }
    xhr.send();
  }
  
  function getLocation(callback) {
    navigator.geolocation.getCurrentPosition(callback);
  }
  
  getLocation(showApi);