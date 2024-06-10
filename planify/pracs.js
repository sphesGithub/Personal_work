
/*  Mobile Menu 
-------------------------------------------------------*/

const menu_open_icon = document.getElementById("nav-menu-open");
const menu_close_icon = document.getElementById("nav-menu-close");
const mobile_menu = document.getElementById("mobile-menu");

function openMenu() {
    menu_close_icon.classList.remove("hidden");
    menu_open_icon.classList.add("hidden");
    mobile_menu.classList.remove("hidden");
    
}
function closeMenu() {
    menu_close_icon.classList.add("hidden");
    menu_open_icon.classList.remove("hidden");
    mobile_menu.classList.add("hidden");
    
}

menu_open_icon.addEventListener("click",openMenu);
menu_close_icon.addEventListener("click",closeMenu);


/*  SlideShow
-------------------------------------------------------*/
const slides = document.getElementsByClassName("mySlides");
if (slides.length > 0){

  let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[slideIndex-1].style.display = "block";
}


}
/*  Amination about us
-------------------------------------------------------*/
const spinImages = document.getElementsByClassName("spinImage");
const spinButtonStart = document.getElementsByClassName("spin_button_start")[0]; // Assuming there's only one start button
const spinButtonStop = document.getElementsByClassName("spin_button_stop")[0]; // Assuming there's only one stop button
let spinning = false; // Track the spinning state

function startSpin() {
  for (var i = 0; i < spinImages.length; i++) {
      var spinImage = spinImages[i];  
      spinImage.classList.add("spinning");
  }
  spinning = true; // Update the spinning state;
  toggleButtonState();
}

function stopSpin() {
  for (var i = 0; i < spinImages.length; i++) {
      var spinImage = spinImages[i];  
      spinImage.classList.remove("spinning");
  }
  spinning = false; // Update the spinning state
  toggleButtonState();
}

// Attach click event handlers to the start and stop buttons
if (spinButtonStart && spinButtonStop ) {
  spinButtonStart.addEventListener("click", startSpin);
  spinButtonStop.addEventListener("click", stopSpin);
  toggleButtonState();
}


// Function to toggle button state and text
function toggleButtonState() {
  if (spinning) {
    spinButtonStart.disabled = true;
    spinButtonStop.disabled = false;


  } else {
    spinButtonStart.disabled = false;
    spinButtonStop.disabled = true;
  }
}

/*  add button event form
-------------------------------------------------------*/

const add_event_button = document.getElementById("add_event_button");
const profile_pic = document.getElementById("profile_pic");
const discard_button = document.getElementById("discard_button");
const event_form = document.getElementById("event_form");
const add_event_form_section= document.getElementById("add_event_form_section");
const startTimeInput = document.querySelector('[name="start_time"]');
const endTimeInput = document.querySelector('[name="end_time"]');
const times_error_msg = document.getElementById("times_error_msg");


// Constants for form inputs by name

function openEventModal() {
    event_form.classList.remove("hidden");
}
function discardEvent(event) {
    event_form.classList.add("hidden");
    event.preventDefault();
}

if (add_event_button) {
  add_event_button.addEventListener("click", openEventModal);
}

if(profile_pic){
  profile_pic.addEventListener("click", openEventModal);
}

if (discard_button) {
  discard_button.addEventListener("click", discardEvent);
}


const contactMessage = document.querySelector('[name="message"]');
const charCount_message = document.getElementById("charCount_message");
function updateCharCount() {

  const maxLength_message = parseInt(contactMessage.getAttribute('maxlength'));
  const currentLength_message = contactMessage.value.length;
  const charactersLeft_message = maxLength_message - currentLength_message;

  if (charCount_message) {
    charCount_message.textContent = `Characters left: ${charactersLeft_message}`;
  }
}
if (contactMessage) {
  contactMessage.addEventListener('input', updateCharCount);
}
 

/*  CALENDAR
-------------------------------------------------------*/
// Handle the calendar and buttons
const calendar = document.getElementById('calendar');
const currentMonthElement = document.getElementById('current_month');
const previousMonthButton = document.querySelector('button[name="previous_month"]');
const nextMonthButton = document.querySelector('button[name="next_month"]');
const addEventButton = document.getElementById('add_event_button');

let currentDate = new Date();

// event data 
let events = [];
const user_id = document.getElementById("user_id").textContent;

// Make an AJAX request to fetch event data from the server
fetch(`get_events.php?user_id=${user_id}`)
.then(response => response.json())
.then(data => {
    events = data; // Update events with the fetched data
    generateCalendar();
})
.catch(error => console.error('Error fetching event data:', error) );

// Function to generate the calendar and display events
function generateCalendar() {
    // Clear the calendar
    calendar.innerHTML = '';

    // Get the current year and month
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Set the current month text
    currentMonthElement.textContent = new Date(year, month, 1).toLocaleDateString('en-US', {
        month: 'long',
        year: 'numeric',
    });

    // Calculate the number of days in the current month
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Create the calendar by iterating through each day
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        const dayElement = document.createElement('div');
        const dayNumElement = document.createElement('span');
        dayNumElement.textContent = day;
        dayElement.classList.add('day');
        
      const currentDate = new Date(); // Get the current date and time

        // Check if the date's year, month, and day match the current date's year, month, and day
        if (date.getFullYear() === currentDate.getFullYear() &&
            date.getMonth() === currentDate.getMonth() &&
            date.getDate() === currentDate.getDate()) {
          dayNumElement.classList.add('current_day');
        }


        dayElement.appendChild(dayNumElement);
          // Check if there are events for this date and display them
          const eventsForDate = events.filter(event => {
            const eventDate = new Date(event.date);
            return (
                eventDate.getFullYear() === date.getFullYear() &&
                eventDate.getMonth() === date.getMonth() &&
                eventDate.getDate() === date.getDate()
            );
        });

        if (eventsForDate.length > 0) {
            eventsForDate.forEach(event => {
                const eventElement = document.createElement('div');
                const deleteEventElement = document.createElement('a');
                deleteEventElement.textContent = 'X';
                deleteEventElement.href = `delete_event.php?event_id=${event.event_id}`;
                // Add an onclick event handler
                deleteEventElement.onclick = function() {
                  const confirmDelete = confirm(`Are you sure you want to delete the event "${event.title}"?`);
                  if (confirmDelete) {
                      // If the user confirms the delete, the link will proceed to the "delete_event.php" page.
                  } else {
                      // If the user cancels the delete, prevent the link from navigating to the "delete_event.php" page.
                      return false;
                  }
                };
                eventElement.style.whiteSpace = 'pre-line';
                eventElement.classList.add("event");
                //say we have this string "17:00:00" it will be formatted to "17:00"
                eventElement.textContent = event.title + '\n' + event.start_time.substring(0, 5) + ' - ' + event.end_time.substring(0, 5);;
                eventElement.appendChild(deleteEventElement);
                dayElement.appendChild(eventElement);
            });
        }

        calendar.appendChild(dayElement);
    }
}

// Initial page load


// Previous month button click event
previousMonthButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    generateCalendar();
});

// Next month button click event
nextMonthButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    generateCalendar();
});

//days of the week

const days_of_week=document.getElementById("days_of_week");
const dayList=['Sun','Mon','Tue','Wed','Thur','Fri','Sat']
function displayDays(){
  for (let index = 0; index < dayList.length; index++) {
    const dayElement = document.createElement('div'); // Create a new div for each day
    dayElement.textContent = dayList[index]; // Set the text content of the div
    days_of_week.appendChild(dayElement); // Append the div to the container
    
  }
}


if(calendar){
  displayDays();
}
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