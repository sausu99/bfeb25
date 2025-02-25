/** Again import google libraries */
importScripts("https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/7.14.6/firebase-messaging.js");

/** Your web app's Firebase configuration 
 * Copy from Login 
 *      Firebase Console -> Select Projects From Top Naviagation 
 *      -> Left Side bar -> Project Overview -> Project Settings
 *      -> General -> Scroll Down and Choose CDN for all the details
*/
var config = {
   apiKey: "AIzaSyDAKyR-LR2r5Bhk36SaLWjHz9v60Iz95mk",
    authDomain: "nepaimpressions.firebaseapp.com",
    databaseURL: "https://nepaimpressions.firebaseio.com",
    projectId: "nepaimpressions",
    storageBucket: "nepaimpressions.appspot.com",
    messagingSenderId: "80160937918",
    appId: "1:80160937918:web:0a54f0f6658b9d83310bef",
    measurementId: "G-Y4TZY0T1X1"
};
firebase.initializeApp(config);

// Retrieve an instance of Firebase Data Messaging so that it can handle background messages.
const messaging = firebase.messaging();

/** THIS IS THE MAIN WHICH LISTENS IN BACKGROUND */
messaging.setBackgroundMessageHandler(function(payload) {
    const notificationTitle = payload.data.title;
    const notificationOptions = {
        body: payload.data.body,
        icon: 'https://c.disquscdn.com/uploads/users/34896/2802/avatar92.jpg',
        image: 'https://c.disquscdn.com/uploads/users/34896/2802/avatar92.jpg'
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});