importScripts('https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.14.6/firebase-messaging.js');

var firebaseConfig = {
  apiKey: "AIzaSyDAKyR-LR2r5Bhk36SaLWjHz9v60Iz95mk",
  authDomain: "nepaimpressions.firebaseapp.com",
  projectId: "nepaimpressions",
  storageBucket: "nepaimpressions.appspot.com",
  messagingSenderId: "80160937918",
  appId: "1:80160937918:web:0a54f0f6658b9d83310bef",
  measurementId: "G-Y4TZY0T1X1"
};

firebase.initializeApp(firebaseConfig);
const messaging=firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log(payload);
    const notification=JSON.parse(payload);
    const notificationOption={
        body:notification.body,
        icon:notification.icon
    };
    return self.registration.showNotification(payload.notification.title,notificationOption);
});