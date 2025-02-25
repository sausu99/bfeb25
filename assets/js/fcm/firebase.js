/** Your web app's Firebase configuration 
 * Copy from Login 
 *      Firebase Console -> Select Projects From Top Naviagation 
 *      -> Left Side bar -> Project Overview -> Project Settings
 *      -> General -> Scroll Down and Choose CDN for all the details
*/
var firebaseConfig = {
    apiKey: "AIzaSyDAKyR-LR2r5Bhk36SaLWjHz9v60Iz95mk",
    authDomain: "nepaimpressions.firebaseapp.com",
    databaseURL: "https://nepaimpressions.firebaseio.com",
    projectId: "nepaimpressions",
    storageBucket: "nepaimpressions.appspot.com",
    messagingSenderId: "80160937918",
    appId: "1:80160937918:web:0a54f0f6658b9d83310bef",
    measurementId: "G-Y4TZY0T1X1"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

/**
 * We can start messaging using messaging() service with firebase object
 */
var messaging = firebase.messaging();


Notification.requestPermission().then(function (permission) {
    console.log(permission);
});

/** Register your service worker here
 *  It starts listening to incoming push notifications from here
 */
navigator.serviceWorker.register('./assets/js/fcm/firebase-messaging-sw.js')
.then(function (registration) {
    /** Since we are using our own service worker ie firebase-messaging-sw.js file */
    messaging.useServiceWorker(registration);

    /** Lets request user whether we need to send the notifications or not */
    messaging.requestPermission()
        .then(function () {
            /** Standard function to get the token */
            messaging.getToken()
            .then(function(token) {
				//console.log('====Token=====');
                /** Here I am logging to my console. This token I will use for testing with PHP Notification */
                //console.log(token);
                /** SAVE TOKEN::From here you need to store the TOKEN by AJAX request to your server */
				saveToken(token, device_id);
            })
            .catch(function(error) {
                /** If some error happens while fetching the token then handle here */
                updateUIForPushPermissionRequired();
                console.log('Error while fetching the token ' + error);
            });
        })
        .catch(function (error) {
            /** If user denies then handle something here */
            console.log('Permission denied ' + error);
			alert('Desktop notifications not available in your browser. Try Chromium.');
			//window.webkitNotifications.requestPermission(permissionGranted);
        })
})
.catch(function () {
    console.log('Error in registering service worker');
});

/** What we need to do when the existing token refreshes for a user */
messaging.onTokenRefresh(function() {
    messaging.getToken()
    .then(function(renewedToken) {
		//console.log('onTokenRefresh');
        //console.log(renewedToken);
        /** UPDATE TOKEN::From here you need to store the TOKEN by AJAX request to your server */
		//saveToken(token, device_id);
    })
    .catch(function(error) {
        /** If some error happens while fetching the token then handle here */
        console.log('Error in fetching refreshed token ' + error);
    });
});

// Handle incoming messages
messaging.onMessage(function (payload) {
        //console.log(payload);
        const notificationOption={
            body:payload.notification.body,
            icon:payload.notification.icon
        };
		
        if(Notification.permission==="granted"){
            var notification=new Notification(payload.notification.title,notificationOption);

            notification.onclick=function (ev) {
                ev.preventDefault();
                window.open(payload.notification.click_action,'_blank');
                notification.close();
            }
        }

    });

function saveToken(currentToken, deviceid) {
	$.ajax({
                    type: "POST",
                    url: FCM_TOKEN_URL,
                    data: {'device_id':deviceid, 'token':currentToken},
                    success: function (data) {
                        //console.log(data);
                    },
                    error: function (res) {
						//console.log(res);
                    }
                });
	
  }
  