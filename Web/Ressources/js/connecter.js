$("#inscrire").click(function () {
  var radioValue = $("input[name=radio]:checked").val();
  console.log(radioValue);
  if (radioValue != undefined) {
    $("input[name=radio]").prop('checked', false);
    window.location.href = "create-compte?identifiant=" + radioValue;
  } else {
    $("#warning").css({"display" : "block", "color" : "red", "fontSize" : "18px"}).fadeOut(10000);
  }
});

$("#annuler").click(function(){
  $("input[name=radio]").prop('checked', false);
});

  /** 
   *@changelog 2023-05-19 Lansky [EVOL] Ajout fonctionnalité voir mot de passe saisi
   *
  */
  $('.eye-icon').click(function() {
    $(this).toggleClass('fa-eye fa-eye-slash');
    let changeType = $(this).parent().find('input');
    if ($(changeType).attr('type') == 'text') {
      $(changeType).attr('type','password');
    } else if ($(changeType).attr('type') == 'password') {
      $(changeType).attr('type','text');
    }
  });
/** @changelog 07/02/2022 [EVOL] (Lansky) Localiser l'utilisateur lors tentative de connexion */
// $(document).ready(function() {
//   // implement JSON.stringify serialization
//   JSON.stringify = JSON.stringify || function (obj) {
//       var t = typeof (obj);
//       if (t != "object" || obj === null) {
//           // simple data type
//           if (t == "string") obj = '"'+obj+'"';
//           return String(obj);
//       }
//       else {
//           // recurse array or object
//           var n, v, json = [], arr = (obj && obj.constructor == Array);
//           for (n in obj) {
//               v = obj[n]; t = typeof(v);
//               if (t == "string") v = '"'+v+'"';
//               else if (t == "object" && v !== null) v = JSON.stringify(v);
//               json.push((arr ? "" : '"' + n + '":') + String(v));
//           }
//           return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
//       }
//   };
//   let apiKey = '1be9a6884abd4c3ea143b59ca317c6b2';
//     $.getJSON('https://ipgeolocation.abstractapi.com/v1/?api_key=' + apiKey, function(data) {
//        // Parse json to string before assigned it to localisation value
//       $('#localisation').val(JSON.stringify(JSON.stringify(data, null, 2)));
//     });
// });
// $("#connect").click(function () {
  //   chrome.browserAction.onClicked.addListener(createFile);
  // createFile();

 /* var macAddress = "";
    var user_address_ip = "";
    var your_computernm = "";
    var wmi = GetObject("winmgmts:{impersonationLevel=impersonate}");
    e = new Enumerator(wmi.ExecQuery("SELECT * FROM Win32_NetworkAdapterConfiguration WHERE IPEnabled = True"));
    for(; !e.atEnd(); e.moveNext()) {
        var s = e.item();
        macAddress = s.MACAddress;
        user_address_ip = s.IPAddress(0);
        your_computernm = s.DNSHostName;
    }


  var blob = new Blob(["Welcome to Websparrow.org."], { type: "text/plain;charset=utf-8" });
  // saveAs(blob, "static.txt");
  var userLink = document.createElement('a');
  userLink.setAttribute('download', 'andrana.txt');
  userLink.setAttribute('href', window.URL.createObjectURL(blob));
  console.log(userLink) ;  userLink.click();*/
  

// });

// function createFile()
// {
//     chrome.tabs.getSelected(null, function(tab) {
//         window.webkitRequestFileSystem(window.TEMPORARY, 1024*1024, function(fs) {
//             fs.root.getFile('test', {create: true}, function(fileEntry) {
//                 fileEntry.createWriter(function(fileWriter) {
//                     var builder = new WebKitBlobBuilder();
//                     builder.append("Saurabh");
//                     builder.append("\n");
//                     builder.append("Saxena");

//                     var blob = builder.getBlob('text/plain');

//                     fileWriter.onwriteend = function() {
//                         chrome.tabs.create({"url":fileEntry.toURL(),"selected":true},function(tab){});
//                     };
//                     fileWriter.write(blob);
//                 }, errorHandler);
//             }, errorHandler);
//         }, errorHandler);
//     });
// }
// function errorHandler(e) {
//   var msg = '';

//   switch (e.code) {
//     case FileError.QUOTA_EXCEEDED_ERR:
//       msg = 'QUOTA_EXCEEDED_ERR';
//       break;
//     case FileError.NOT_FOUND_ERR:
//       msg = 'NOT_FOUND_ERR';
//       break;
//     case FileError.SECURITY_ERR:
//       msg = 'SECURITY_ERR';
//       break;
//     case FileError.INVALID_MODIFICATION_ERR:
//       msg = 'INVALID_MODIFICATION_ERR';
//       break;
//     case FileError.INVALID_STATE_ERR:
//       msg = 'INVALID_STATE_ERR';
//       break;
//     default:
//       msg = 'Unknown Error';
//       break;
//   };

//   Console.Log('Error: ' + msg);
// }