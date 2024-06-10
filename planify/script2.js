document.addEventListener("DOMContentLoaded", function() {
   /*
 
Navigator
*/
// this is a class that collects info about the user
class UserInfo {
    constructor() {
        this.language = navigator.language;
        this.userAgent = navigator.userAgent;
        this.online = navigator.onLine;
        this.geolocation = navigator.geolocation;
        this.cookieEnabled = navigator.cookieEnabled;
    }
  }
  
  const userInfo = new UserInfo();
  // Get a reference to the user info div
  const userInfoDiv = document.getElementById("user-info");
  
  // Create a function to display user information in the user info div
  function displayUserInfo(userInfo) {
    // Populate the user info div with the user information
    userInfoDiv.innerHTML = `
      <h2>User Information:</h2>
      <p><strong>Language:</strong> ${userInfo.language}</p>
      <p><strong>User Agent:</strong> ${userInfo.userAgent}</p>
      <p><strong>Online Status:</strong> ${userInfo.online ? "Online" : "Offline"}</p>
      <p><strong>Geolocation Available:</strong> ${userInfo.geolocation ? "Yes" : "No"}</p>
      <p><strong>Cookie enabled:</strong> ${userInfo.cookieEnabled ? "Yes" : "No"}</p>
    `;
  }
  
  // Call the function to display user information
  if(userInfoDiv){
    displayUserInfo(userInfo);
  }
  });